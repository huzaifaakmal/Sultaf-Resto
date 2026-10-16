<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\AuditLog;
use App\Models\Benefit;
use App\Models\Menu;
use App\Models\TransaksiItem;
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
                'user_id'           => Auth::id(),
                'nama_pelanggan'    => $payment['nama_pelanggan'] ?? null,
                'no_telepon'        => $payment['no_telepon'] ?? null,
                'tipe_pesanan'      => $details['tipe_pesanan'] ?? 'takeaway',
                'nomor_meja'        => $details['nomor_meja'] ?? null,
                'subtotal'          => $subtotal,
                'pajak'             => $tax,
                'service_charge'    => 0,
                'diskon_member'     => $diskon,
                'total_harga'       => $total,
                'status_pesanan'    => 'pending',
                'status_pembayaran' => $isAutomated ? 'terverifikasi' : 'menunggu',
                'metode_pembayaran' => $payment['metode_pembayaran'] ?? null,
                'bukti_pembayaran'  => $payment['bukti_pembayaran'] ?? null,
                'catatan'           => $details['catatan'] ?? null,
                'tgl_transaksi'     => now(),
            ]);

            foreach ($items as $item) {
                TransaksiItem::create([
                    'transaksi_penjualan_id' => $transaksi->id,
                    'menu_id'                => $item['menu']->id,
                    'qty'                    => $item['qty'],
                    'harga_satuan'           => $item['menu']->harga_makanan,
                ]);
            }

            // Record audit log entry within the same database transaction
            AuditLog::create([
                'tenant_id'      => $transaksi->tenant_id ?? 1,
                'user_id'        => Auth::id(),
                'action'         => 'ORDER_CREATED',
                'auditable_type' => TransaksiPenjualan::class,
                'auditable_id'   => $transaksi->id,
                'old_values'     => null,
                'new_values'     => [
                    'kode_transaksi'    => $transaksi->kode_transaksi,
                    'total_harga'       => $transaksi->total_harga,
                    'metode_pembayaran' => $transaksi->metode_pembayaran,
                    'status_pembayaran' => $transaksi->status_pembayaran,
                ],
                'ip_address'     => request()->ip(),
                'user_agent'     => request()->userAgent(),
            ]);

            return $transaksi;
        });

        // Notify or sync order to external RMS if configured
        try {
            app(\App\Services\RmsIntegrationService::class)->syncOrderToRms($transaksi);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('RMS auto-sync skipped: ' . $e->getMessage());
        }

        session()->forget(['cart', 'checkout.details', 'checkout.payment']);
        return redirect()->route('checkout.finish', $transaksi);
    }

    public function finish(TransaksiPenjualan $transaksi): View
    {
        $transaksi->load('items.menu');
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
        $menus = Menu::whereIn('id', array_keys($cart))->get()->keyBy('id');

        $items = collect($cart)->map(function ($qty, $menuId) use ($menus) {
            $menu = $menus->get($menuId);
            if (! $menu) return null;
            return ['menu' => $menu, 'qty' => $qty];
        })->filter()->values();

        $subtotal = $items->sum(fn ($i) => $i['qty'] * (float) $i['menu']->harga_makanan);
        $tax      = round($subtotal * 0.10);

        $diskon = 0;
        if (Auth::check() && Auth::user()->isMember()) {
            $benefit = Benefit::where('aktif', true)
                ->where('tipe', 'diskon')
                ->where('poin_dibutuhkan', '<=', Auth::user()->points)
                ->orderByDesc('poin_dibutuhkan')
                ->first();

            if ($benefit) {
                $diskon = round($subtotal * ((float) $benefit->nilai_diskon / 100));
            }
        }

        $total = $subtotal + $tax - $diskon;

        return [$items, $subtotal, $tax, $diskon, $total];
    }

    protected function ensureCartNotEmpty(): void
    {
        if (empty(session('cart', []))) {
            abort(redirect()->route('menu.index')->with('error', 'Keranjang kamu masih kosong.'));
        }
=======
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Your cart is empty!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required',
        ]);

        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'order_number' => 'SLTF-' . strtoupper(Str::random(8)),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'total_price' => $total,
            'payment_status' => 'pending',
        ]);

        foreach ($cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('payment.index', $order->order_number);
    }

    public function payment($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        
        if ($order->payment_status === 'paid') {
            return redirect()->route('order.success', $order_number);
        }

        // Use the real QRIS image provided
        $qrUrl = asset('images/qris.jpg');

        return view('payment', compact('order', 'qrUrl'));
    }

    public function simulate($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        $order->update(['payment_status' => 'paid']);

        return redirect()->route('order.success', $order->order_number);
    }

    public function success($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        return view('success', compact('order'));
>>>>>>> fda411f0813b9c7104ad865582a079f3a6221844
    }
}
