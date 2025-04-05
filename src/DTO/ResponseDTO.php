<?php

namespace Sebastian\PhpEcommerce\DTO;

class ResponseDTO
{
    private string $status;
    private ?string $message;
    private array $data;
    private ?array $errors;
    private int $statusCode;


    public function __construct(
        string $status,
        ?string $message = null,
        array $data = [],
        ?array $errors = null,
        int $statusCode = 200,
    ) {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->errors = $errors;
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors,
        ];
    }
}
