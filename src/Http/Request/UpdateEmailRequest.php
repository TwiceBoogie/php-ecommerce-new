<?php

namespace Sebastian\PhpEcommerce\Http\Request;

class UpdateEmailRequest
{
    private array $errors = [];
    private ?string $email;

    public function __construct(array $input)
    {
        $this->email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);

        $this->validate();
    }

    private function validate(): void
    {
        if (!$this->email) {
            $this->errors['email'] = 'Valid email is required';
        }
    }

    /**
     * Check if validation has errors.
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Return validation errors.
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get sanitized email.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}