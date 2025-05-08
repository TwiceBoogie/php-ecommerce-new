<?php

namespace Sebastian\PhpEcommerce\Http\Request;

class RegisterRequest
{
    private array $errors = [];
    private ?string $name;
    private ?string $email;
    private ?string $password;
    private ?string $confirmPassword;

    public function __construct(array $input)
    {
        $this->name = trim($input['name'] ?? '');
        $this->email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $this->password = $input['password'] ?? '';
        $this->confirmPassword = $input['confirmPassword'] ?? '';

        $this->validate();
    }

    public function validate(): void
    {
        if (empty($this->name)) {
            $this->errors['name'] = 'Name is required';
        }

        if (!$this->email) {
            $this->errors['email'] = 'Valid email is required';
        }

        if (empty($this->password)) {
            $this->errors['password'] = 'Password is required';
        } elseif (strlen($this->password) < 8) {
            $this->errors['password'] = 'Password must be at least 8 characters long';
        }

        if ($this->password !== $this->confirmPassword) {
            $this->errors['confirmPassowrd'] = 'Passwords do not match';
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}