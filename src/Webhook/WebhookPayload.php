<?php

namespace Riddle\Api\Webhook;

/**
 * This class can parse & validate an incoming Riddle webhook request.
 */
class WebhookPayload
{
    /**
     * @var array the raw payload
     */
    private array $payload;

    /**
     * @var string unique ID for this lead
     */
    private string $uniqueId;

    /**
     * @var string ID of the riddle
     */
    private string $riddleId;

    /**
     * @var ?string|null identifier for this lead - e.g. the riddle email. this can be used to uniquely identify the user
     */
    private ?string $identifier;

    /**
     * @var \DateTime date & time when the lead was created
     */
    private \DateTime $createdAt;

    /**
     * @var array data of the riddle - e.g. submitted SingleChoice answers
     */
    private array $riddleData;

    /**
     * @var array data of the form - e.g. submitted emails, names, ..
     */
    private array $formData;

    /**
     * @var array data of the result (if Riddle is a quiz)
     */
    private array $resultData;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
        $this->uniqueId = $payload['uniqueId'];
        $this->riddleId = $payload['riddleId'];
        $this->identifier = $payload['identifier'];
        $this->createdAt = new \DateTime($payload['createdAt']);
        $this->riddleData = $payload['data']['riddle'] ?? [];
        $this->formData = $payload['data']['form'] ?? [];
        $this->resultData = $payload['data']['result'] ?? [];
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getUniqueId(): string
    {
        return $this->uniqueId;
    }

    public function getRiddleId(): string
    {
        return $this->riddleId;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getRiddleData(): array
    {
        return $this->riddleData;
    }

    public function getFormData(): array
    {
        return $this->formData;
    }

    public function getResultData(): array
    {
        return $this->resultData;
    }
}