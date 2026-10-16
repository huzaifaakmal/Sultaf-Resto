<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\TransaksiPenjualan;
use App\Services\PaymentGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentGatewayController extends Controller
{
    public function __construct(
        protected PaymentGatewayService $paymentService
    ) {}

    /**
     * Create payment token or client secret for PCI-compliant checkout.
     */
    public function createIntent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_reference' => ['required', 'string'],
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

        return response()->json([
            'success' => true,
            'data'    => $result,
        ], 200);
    }

    /**
     * Handle incoming asynchronous webhook from Stripe / Midtrans.
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->all();
        $event = $request->input('type') ?? $request->input('transaction_status') ?? 'payment.succeeded';
        $orderRef = $request->input('data.object.metadata.order_reference') ?? $request->input('order_id');

        Log::info("Payment Webhook Ingested: Event [{$event}] for Order [{$orderRef}]");

        if ($orderRef) {
            $transaksi = TransaksiPenjualan::where('kode_transaksi', $orderRef)->first();
            if ($transaksi) {
                $transaksi->update(['status_pembayaran' => 'terverifikasi']);

                AuditLog::create([
                    'tenant_id'      => $transaksi->tenant_id ?? 1,
                    'user_id'        => null,
                    'action'         => 'PAYMENT_WEBHOOK_VERIFIED',
                    'auditable_type' => TransaksiPenjualan::class,
                    'auditable_id'   => $transaksi->id,
                    'new_values'     => ['status' => 'terverifikasi', 'gateway_event' => $event],
                    'ip_address'     => $request->ip(),
                    'user_agent'     => $request->userAgent() ?? 'PaymentGateway-Webhook',
                ]);
            }
        }

        return response()->json(['status' => 'success', 'acknowledged' => true], 200);
    }
}
