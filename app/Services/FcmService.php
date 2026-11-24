<?php

namespace App\Services;

use App\Models\Notification as UserNotification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;

class FcmService
{
    public function __construct(
        protected Messaging $messaging
    ) {
    }

    /**
     * Send an FCM notification to a single device token and store it for the user.
     *
     * @param  string|null  $token
     * @param  string  $title
     * @param  string  $body
     * @param  array<string, mixed>  $data
     * @param  int|null  $userId
     */
    public function send(?string $token, string $title, string $body, array $data = [], ?int $userId = null): bool
    {
        $normalizedData = $this->normalizeData($data);
        $maskedToken = $this->maskToken($token);

        if ($userId) {
            $this->storeNotification($userId, $title, $body, $normalizedData);
        }

        if ($token === null || trim($token) === '') {
            Log::warning('FCM Notification Not Sent', [
                'reason' => 'FCM token is null or empty',
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'data' => $normalizedData,
            ]);

            return false;
        }

        try {
            $message = CloudMessage::new()
                ->toToken($token)
                ->withNotification(FcmNotification::create($title, $body))
                ->withData($normalizedData);

            $this->messaging->send($message);

            Log::info('FCM Notification Sent Successfully', [
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'data' => $normalizedData,
                'token' => $maskedToken,
                'type' => $normalizedData['type'] ?? null,
                'booking_id' => $normalizedData['booking_id'] ?? null,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('FCM Notification Failed', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'data' => $normalizedData,
                'token' => $maskedToken,
            ]);

            return false;
        }
    }

    /**
     * Persist notification for in-app history / API listing.
     *
     * @param  array<string, string>  $normalizedData
     */
    protected function storeNotification(int $userId, string $title, string $body, array $normalizedData): void
    {
        try {
            UserNotification::create([
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'type' => $normalizedData['type'] ?? null,
                'booking_id' => isset($normalizedData['booking_id']) && $normalizedData['booking_id'] !== ''
                    ? (int) $normalizedData['booking_id']
                    : null,
                'broadcast_id' => isset($normalizedData['broadcast_id']) && $normalizedData['broadcast_id'] !== ''
                    ? (int) $normalizedData['broadcast_id']
                    : null,
                'data' => $normalizedData,
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to store notification in database', [
                'error' => $e->getMessage(),
                'user_id' => $userId,
                'title' => $title,
                'body' => $body,
                'data' => $normalizedData,
            ]);
        }
    }

    /**
     * Normalize FCM data values to string-compatible scalars.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, string>
     */
    protected function normalizeData(array $data): array
    {
        $normalized = [];

        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $normalized[(string) $key] = $value ? '1' : '0';
                continue;
            }

            if (is_null($value)) {
                $normalized[(string) $key] = '';
                continue;
            }

            if (is_scalar($value)) {
                $normalized[(string) $key] = (string) $value;
                continue;
            }

            $normalized[(string) $key] = json_encode($value) ?: '';
        }

        return $normalized;
    }

    protected function maskToken(?string $token): string
    {
        if ($token === null || $token === '') {
            return '[empty]';
        }

        $length = strlen($token);

        if ($length <= 8) {
            return str_repeat('*', $length);
        }

        return substr($token, 0, 4) . str_repeat('*', max(0, $length - 8)) . substr($token, -4);
    }
}
