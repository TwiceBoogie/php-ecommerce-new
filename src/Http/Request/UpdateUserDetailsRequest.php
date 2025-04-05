<?php

namespace Sebastian\PhpEcommerce\Http\Request;

class UpdateUserDetailsRequest
{
    private array $errors = [];
    private string $name;
    private ?string $phone;
    private ?string $address;
    private ?string $city;
    private ?string $state;
    private ?string $postal;
    private ?string $country;

    public function __construct(array $input)
    {
        // Sanitize inputs
        $this->name = $this->sanitizeString($input['name'] ?? '');
        $this->phone = $this->sanitizePhone($input['phone'] ?? '');
        $this->address = $this->sanitizeString($input['address'] ?? '');
        $this->city = $this->sanitizeString($input['city'] ?? '');
        $this->state = $this->sanitizeString($input['state'] ?? '');
        $this->postal = $this->sanitizePostal($input['postal'] ?? '');
        $this->country = $this->sanitizeString($input['country'] ?? '');

        // Validate inputs only if they are not empty
        $this->validate();
    }

    /**
     * Validate user details.
     */
    private function validate(): void
    {
        if (empty($this->name) && strlen($this->name) < 3) {
            $this->errors['name'] = 'Name must be at least 3 characters.';
        }

        if (!empty($this->phone) && !$this->validatePhone($this->phone)) {
            $this->errors['phone'] = 'Invalid phone number format.';
        }

        if (!empty($this->postal) && !$this->validatePostal($this->postal)) {
            $this->errors['postal'] = 'Invalid postal code format.';
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
     * Getters for sanitized fields.
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    public function getAddress(): ?string
    {
        return $this->address;
    }
    public function getCity(): ?string
    {
        return $this->city;
    }
    public function getState(): ?string
    {
        return $this->state;
    }
    public function getPostal(): ?string
    {
        return $this->postal;
    }
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * Sanitize a string.
     */
    private function sanitizeString(string $value): ?string
    {
        return $value === '' ? '' : strip_tags($value);
    }

    /**
     * Sanitize and validate a phone number.
     */
    private function sanitizePhone(string $value): ?string
    {
        return $value === '' ? '' : preg_replace('/[^0-9+]/', '', $value);
    }

    /**
     * Validate phone number format (basic).
     */
    private function validatePhone(string $value): bool
    {
        return preg_match('/^\+?[0-9]{7,15}$/', $value);
    }

    /**
     * Sanitize and validate a postal code.
     */
    private function sanitizePostal(string $value): ?string
    {
        return $value === '' ? '' : preg_replace('/[^A-Za-z0-9]/', '', $value);
    }

    /**
     * Validate postal code (basic alphanumeric format).
     */
    private function validatePostal(string $value): bool
    {
        return preg_match('/^[A-Za-z0-9- ]{3,10}$/', $value);
    }
}
