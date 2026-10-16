<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use App\Services\MemberPointService;
use App\Services\PaymentGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentGatewayController extends Controller
{
    public function __construct(
        protected PaymentGatewayService $paymentService,
        protected MemberPointService $memberPoints,
    ) {}

    /**
     * Create payment token or client secret for checkout.
     */
    public function createIntent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_reference' => ['required', 'string', 'exists:transaksi_penjualans,kode_transaksi'],
            'amount'          => ['required', 'numeric', 'min:1000'],
            'customer_name'   => ['nullable', 'string', 'max:255'],
            'customer_email'  => ['nullable', 'email'],
            'customer_phone'  => ['nullable', 'string'],
        ]);

        $result = $this->paymentService->createPaymentIntent(
            (float) $validated['amount'],
            $validated['order_reference'],
            [
                'name'  => $validated['customer_name'] ?? 'Guest',
                'email' => $validated['customer_email'] ?? 'guest@example.com',
                'phone' => $validated['customer_phone'] ?? '',
            ]
        );

        return response()->json(['success' => true, 'data' => $result], 200);
    }

    /**
     * Handle the asynchronous payment webhook (Stripe / Midtrans style).
     * The body must be signed with HMAC-SHA256 in the `X-Signature` header.
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        if (! $this->paymentService->verifyWebhookSignature($request)) {
            Log::warning('Payment webhook rejected: invalid signature', ['ip' => $request->ip()]);

            return response()->json(['success' => false, 'error' => 'invalid_signature'], 401);
        }

        $event = (string) ($request->input('type') ?? $request->input('transaction_status') ?? '');
        $orderRef = $request->input('data.object.metadata.order_reference') ?? $request->input('order_id');

        if ($orderRef && $this->paymentService->isSuccessEvent($event)) {
            $transaksi = TransaksiPenjualan::where('kode_transaksi', $orderRef)->first();

            if ($transaksi && $transaksi->status_pembayaran !== 'terverifikasi') {
                $transaksi->update(['status_pembayaran' => 'terverifikasi']);
                $this->memberPoints->awardFromTransaksi($transaksi, $transaksi->no_telepon);
            }
        }

        return response()->json(['success' => true, 'acknowledged' => true], 200);
    }
}
