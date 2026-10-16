<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentGatewayService
{
    /**
     * Create a payment intent. Uses Stripe when a secret key and the SDK are available,
     * otherwise returns a mock intent so the flow can be exercised locally.
     */
    public function createPaymentIntent(float $amount, string $orderReference, array $customer = []): array
    {
        $secret = config('services.stripe.secret');

        if ($secret && class_exists(\Stripe\StripeClient::class)) {
            try {
                $intent = (new \Stripe\StripeClient($secret))->paymentIntents->create([
                    'amount'                    => (int) $amount,
                    'currency'                  => 'idr',
                    'automatic_payment_methods' => ['enabled' => true],
                    'metadata'                  => ['order_reference' => $orderReference],
                ]);

                return [
                    'provider'      => 'stripe',
                    'intent_id'     => $intent->id,
                    'client_secret' => $intent->client_secret,
                    'amount'        => (int) $amount,
                    'currency'      => 'idr',
                ];
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return [
            'provider'      => 'mock',
            'intent_id'     => 'pi_mock_' . Str::random(16),
            'client_secret' => 'pi_mock_' . Str::random(24) . '_secret_' . Str::random(16),
            'amount'        => (int) $amount,
            'currency'      => 'idr',
        ];
    }

    /**
     * Verify the HMAC-SHA256 signature (header `X-Signature`) of a webhook body.
     * Always fails when no webhook secret is configured.
     */
    public function verifyWebhookSignature(Request $request): bool
    {
        $secret = config('services.payment.webhook_secret');
        $signature = (string) $request->header('X-Signature', '');

        if (! $secret || $signature === '') {
            return false;
        }

        return hash_equals(hash_hmac('sha256', $request->getContent(), $secret), $signature);
    }

    /**
     * Whether the webhook event means the payment went through.
     */
    public function isSuccessEvent(string $event): bool
    {
        return in_array($event, [
            'payment.succeeded', 'payment_intent.succeeded', 'settlement', 'capture',
        ], true);
    }
}
