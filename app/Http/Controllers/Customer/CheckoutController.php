<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Promo;
use App\Models\TransaksiPenjualan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /* ===================== STEP 2 — DETAILS ===================== */

    public function details(): View
    {
        $this->ensureCartNotEmpty();
        return view('checkout.details');
    }

    public function storeDetails(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipe_pesanan' => ['required', 'in:dine_in,takeaway'],
            'nomor_meja'   => ['required_if:tipe_pesanan,dine_in', 'nullable', 'integer', 'min:1', 'max:20'],
            'catatan'      => ['nullable', 'string', 'max:500'],
        ]);

        session(['checkout.details' => $validated]);
        return redirect()->route('checkout.payment');
    }

    /* ===================== STEP 3 — PAYMENT ===================== */

    public function payment(): View
    {
        $this->ensureCartNotEmpty();
        [, $subtotal, $tax, $diskon, $total] = $this->calculateTotals();
        $paymentIntent = $this->createPaymentIntent($total);

        return view('checkout.payment', [
            'subtotal'                     => $subtotal,
            'tax'                          => $tax,
            'diskon'                       => $diskon,
            'total'                        => $total,
            'payment_intent_client_secret' => $paymentIntent['client_secret'] ?? null,
            'stripe_key'                   => config('services.stripe.key', env('STRIPE_KEY')),
        ]);
    }

    public function storePayment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'in:stripe_card,gopay,ovo,dana,qris,cash'],
            'bukti_pembayaran'  => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],
            'nama_pelanggan'    => ['required', 'string', 'max:255'],
            'no_telepon'        => ['required', 'string', 'max:20'],
            'payment_intent_id' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $fileName = 'receipt_' . Str::uuid() . '.' . $file->guessExtension();
            $validated['bukti_pembayaran'] = $file->storeAs('bukti-pembayaran', $fileName, 'public');
        }

        session(['checkout.payment' => $validated]);
        return $this->complete();
    }

    /* ===================== STEP 4 — FINISH ===================== */

    protected function complete(): RedirectResponse
    {
        [$items, $subtotal, $tax, $diskon, $total] = $this->calculateTotals();
        $details = session('checkout.details', []);
        $payment = session('checkout.payment', []);

        $transaksi = DB::transaction(function () use ($items, $subtotal, $tax, $diskon, $total, $details, $payment) {
            $isAutomated = ($payment['metode_pembayaran'] ?? '') === 'stripe_card';

            $transaksi = TransaksiPenjualan::create([
                'kode_transaksi'    => 'SLT-' . strtoupper(Str::random(6)),
                'id_user'           => Auth::user()?->id_user,
                'nama_pelanggan'    => $payment['nama_pelanggan'] ?? null,
                'no_telepon'        => $payment['no_telepon'] ?? null,
                'tipe_pesanan'      => $details['tipe_pesanan'] ?? 'takeaway',
                'no_meja'           => isset($details['nomor_meja']) ? (string) $details['nomor_meja'] : null,
                'diskon'            => $diskon,
                'total_bayar'       => $total,
                'status_pesanan'    => 'pending',
                'status_pembayaran' => $isAutomated ? 'terverifikasi' : 'menunggu',
                'metode_pembayaran' => $payment['metode_pembayaran'] ?? 'cash',
                'bukti_pembayaran'  => $payment['bukti_pembayaran'] ?? null,
                'catatan'           => $details['catatan'] ?? null,
            ]);

            foreach ($items as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_menu'      => $item['menu']->id_menu,
                    'jumlah'       => $item['qty'],
                    'harga_satuan' => $item['menu']->harga,
                    'subtotal'     => $item['qty'] * $item['menu']->harga,
                ]);
            }

            return $transaksi;
        });

        session()->forget(['cart', 'checkout.details', 'checkout.payment']);
        return redirect()->route('checkout.finish', $transaksi);
    }

    public function finish(TransaksiPenjualan $transaksi): View
    {
        $transaksi->load('detail_transaksi.menu');
        return view('checkout.finish', compact('transaksi'));
    }

    /* ===================== Helpers ===================== */

    protected function createPaymentIntent(float $amount): array
    {
        $stripeSecret = config('services.stripe.secret', env('STRIPE_SECRET'));

        if (! $stripeSecret) {
            return ['client_secret' => 'pi_mock_' . Str::random(24) . '_secret_' . Str::random(16)];
        }

        try {
            $stripe = new \Stripe\StripeClient($stripeSecret);
            $intent = $stripe->paymentIntents->create([
                'amount'                    => (int) $amount,
                'currency'                  => 'idr',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata'                  => ['user_id' => Auth::id() ?? 'guest'],
            ]);

            return ['client_secret' => $intent->client_secret, 'id' => $intent->id];
        } catch (\Exception $e) {
            report($e);
            return ['client_secret' => null];
        }
    }

    protected function calculateTotals(): array
    {
        $cart  = session('cart', []);
        $menus = Menu::whereIn('id_menu', array_keys($cart))->get()->keyBy('id_menu');

        $items = collect($cart)->map(function ($qty, $menuId) use ($menus) {
            $menu = $menus->get($menuId);
            if (! $menu) return null;
            return ['menu' => $menu, 'qty' => $qty];
        })->filter()->values();

        $subtotal = $items->sum(fn ($i) => $i['qty'] * (float) $i['menu']->harga);
        $tax      = round($subtotal * 0.10);

        $diskon = 0;
        if (Auth::check() && Auth::user()->isMember()) {
            $promo = Promo::where('minimal_poin', '<=', Auth::user()->points)
                ->orderByDesc('minimal_poin')
                ->first();

            // `potongan_harga` is a fixed discount in rupiah.
            $diskon = $promo ? min((float) $promo->potongan_harga, $subtotal) : 0;
        }

        $total = $subtotal + $tax - $diskon;

        return [$items, $subtotal, $tax, $diskon, $total];
    }

    protected function ensureCartNotEmpty(): void
    {
        if (empty(session('cart', []))) {
            abort(redirect()->route('menu.index')->with('error', 'Keranjang kamu masih kosong.'));
        }
    }
}
