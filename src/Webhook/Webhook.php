<?php

namespace Riddle\Api\Webhook;

use Riddle\Api\Exception\WebhookInvalidSignatureException;

require_once(__DIR__ . '/WebhookPayload.php');
require_once(__DIR__ . '/../Exception/WebhookInvalidSignatureException.php');

/**
 * This class can parse & validate an incoming Riddle webhook request.
 */
class Webhook
{
    private ?string $signatureKey;

    /**
     * @param ?string $signatureKey the signature key you received when setting up the webhook integration; if passed NULL the signature validation will be skipped
     */
    public function __construct(?string $signatureKey)
    {
        $this->signatureKey = $signatureKey;
    }

    public function parse(): WebhookPayload
    {
        $payload = \file_get_contents('php://input');
        $this->validateSignature($payload);
        $jsonPayload = \json_decode($payload, true);

        return new WebhookPayload($jsonPayload);
    }

    public function validateSignature(string $payload): void
    {
        if (null === $this->signatureKey) {
            return;
        }

        $receivedSignature = $_SERVER['HTTP_X_RIDDLE_SIGNATURE'] ?? null;

        if (null === $receivedSignature) {
            throw new WebhookInvalidSignatureException('No signature received');
        }

        $generatedSignature = \hash_hmac('sha256', $payload, $this->signatureKey);

        if ($receivedSignature !== $generatedSignature) {
            throw new WebhookInvalidSignatureException('Invalid signature received');
        }
    }
}