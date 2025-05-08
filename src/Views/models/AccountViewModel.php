<?php

namespace Sebastian\PhpEcommerce\Views\Models;

use Sebastian\PhpEcommerce\DTO\UserDetailsDTO;

class AccountViewModel extends BaseViewModel
{
    private UserDetailsDTO $userDetails;

    public function __construct(bool $isAdmin, bool $isAuthenticated, UserDetailsDTO $userDetails)
    {
        parent::__construct($isAdmin, $isAuthenticated);
        $this->userDetails = $userDetails;
    }

    public function getUserDetails(): UserDetailsDTO
    {
        return $this->userDetails;
    }

    public function getName(): string
    {
        return $this->userDetails->getName();
    }

    public function getEmail(): string
    {
        return $this->userDetails->getEmail();
    }

    public function getPhone(): string
    {
        return $this->userDetails->getPhoneNumber();
    }

    public function getAddress(): string
    {
        return $this->userDetails->getAddress();
    }

    public function getCity(): string
    {
        return $this->userDetails->getCity();
    }

    public function getState(): string
    {
        return $this->userDetails->getState();
    }

    public function getCountry(): string
    {
        return $this->userDetails->getCountry();
    }

    public function getPostalCode(): string
    {
        return $this->userDetails->getPostalCode();
    }

    public function getRegisterDate(): string
    {
        return $this->userDetails->registerDate();
    }
}