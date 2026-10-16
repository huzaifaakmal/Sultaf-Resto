<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessInboundBookingJob;
use App\Services\OtaChannelSyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OtaWebhookController extends Controller
{
    public function __construct(
        protected OtaChannelSyncService $syncService
    ) {}

    /**
     * Ingest and authenticate inbound OTA webhook event.
     */
    public function handle(Request $request, string $channel): JsonResponse
    {
        $allowedChannels = ['booking_com', 'agoda', 'expedia', 'airbnb'];

        if (! in_array($channel, $allowedChannels, true)) {
            return response()->json([
                'success' => false,
                'error'   => 'unsupported_channel',
                'message' => "OTA channel [{$channel}] is not supported.",
            ], 400);
        }

        // 1. Verify Cryptographic HMAC Signature
        if (! $this->syncService->verifyWebhookSignature($request, $channel)) {
            Log::warning("OTA Webhook Rejected: Invalid signature from [{$channel}]", [
                'ip'      => $request->ip(),
                'headers' => $request->headers->all(),
            ]);

            return response()->json([
                'success' => false,
                'error'   => 'invalid_signature',
                'message' => 'Cryptographic signature verification failed for ' . $channel,
            ], 401);
        }

        $payload = $request->all();

        // 2. Dispatch Asynchronous Job to Process Reservation
        ProcessInboundBookingJob::dispatch($payload, $channel);

        return response()->json([
            'success'   => true,
            'status'    => 'acknowledged',
            'channel'   => $channel,
            'reference' => $payload['reservation_id'] ?? $payload['booking_id'] ?? null,
            'timestamp' => now()->toIso8601String(),
        ], 200);
    }
}
