<?php

namespace Sebastian\PhpEcommerce\Http\Requests;

use Sebastian\PhpEcommerce\Services\Response;

class LoginRequest
{
    private array $errors = [];
    private ?string $email;
    private ?string $password;

    public function __construct(array $input)
    {
        $this->email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $this->password = $input['password'] ?? '';

        $this->validate();
    }

    /**
     * Validate login input fields.
     */
    private function validate(): void
    {
        if (!$this->email) {
            $this->errors['email'] = 'Valid email is required';
        }

        if (empty($this->password)) {
            $this->errors['password'] = 'Password is required';
        } elseif (strlen($this->password) < 8) {
            $this->errors['password'] = 'Password must be at least 8 characters';
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

    /**
     * Get password.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
