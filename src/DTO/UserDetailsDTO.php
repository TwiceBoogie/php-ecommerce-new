<?php

namespace Sebastian\PhpEcommerce\DTO;

class UserDetailsDTO
{
    private int $id;
    private string $email;
    private string $name;
    private string $register_date;
    private ?string $phone;
    private ?string $address;
    private ?string $city;
    private ?string $state;
    private ?string $postal_code;
    private ?string $country;

    public function __construct(
        int $id,
        string $email,
        string $name,
        string $register_date,
        ?string $phone = null,
        ?string $address = null,
        ?string $city = null,
        ?string $state = null,
        ?string $postal_code = null,
        ?string $country = null
    ) {
        $this->id = $id;
        $this->phone = $phone;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->postal_code = $postal_code;
        $this->country = $country;
        $this->email = $email;
        $this->name = $name;
        $this->register_date = $register_date;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPhoneNumber(): string
    {
        return $this->phone ?? "";
    }

    public function getAddress(): string
    {
        return $this->address ?? "";
    }

    public function getCity(): string
    {
        return $this->city ?? "";
    }

    public function getState(): string
    {
        return $this->state ?? "";
    }

    public function getPostalCode(): string
    {
        return $this->postal_code ?? "";
    }

    public function getCountry(): string
    {
        return $this->country ?? "";
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function registerDate(): string
    {
        return $this->register_date;
    }
}