<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\Services\AuthService;
use Sebastian\PhpEcommerce\Repository\UserRepository;
use Sebastian\PhpEcommerce\Services\SecureSession;

class AuthServiceImpl implements AuthService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAuthenticatedUserId(): int
    {
        return $this->getUserId();
    }

    public function isAdmin(): bool
    {
        return $this->userRepository->isAdmin($this->getUserId());
    }

    private function getUserId(): int
    {
        return SecureSession::get('user_id') ?? 0;
    }
}