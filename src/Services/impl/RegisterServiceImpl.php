<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;
use Sebastian\PhpEcommerce\Http\Request\RegisterRequest;
use Sebastian\PhpEcommerce\Services\RegisterService;
use Sebastian\PhpEcommerce\Repository\UserRepository;
use Sebastian\PhpEcommerce\Services\CartService;
use Sebastian\PhpEcommerce\Services\SecureSession;

class RegisterServiceImpl implements RegisterService
{
    private UserRepository $userRepository;
    private CartService $cartService;

    public function __construct(UserRepository $userRepository, CartService $cartService)
    {
        $this->userRepository = $userRepository;
        $this->cartService = $cartService;
    }

    public function register(RegisterRequest $request): ResponseDTO
    {
        $name = $request->getName();
        $email = $request->getEmail();
        $password = $request->getPassword();

        if ($request->fails()) {
            return new ResponseDTO(
                'error',
                'Validation failed',
                [],
                $request->errors(),
                400
            );
        }

        if ($this->isEmailExist($email)) {
            return new ResponseDTO(
                "error",
                "Validation failed",
                [],
                ['email' => 'Email already in use'],
                400
            );
        }

        try {
            $this->createNewUser($name, $email, $this->hashPassword($password));
        } catch (\Exception $e) {
            return new ResponseDTO(
                "error",
                "Server Error",
                [],
                [],
                500
            );
        }

        return new ResponseDTO(
            'success',
            'Registered Successfully',
            [],
            [],
            201
        );
    }

    private function createNewUser($name, $email, $hashedPassword)
    {
        $this->userRepository->transactional(function () use ($name, $email, $hashedPassword) {
            $user = $this->userRepository->save([
                'user_name' => $name,
                'user_email' => $email,
                'user_password' => $hashedPassword,
            ]);
            $userId = $user['id'];
            $this->userRepository->insertUserDetails($userId);
            SecureSession::regenerate();
            SecureSession::set('user', [
                'id' => $userId
            ]);
        });
    }

    private function isEmailExist(string $email): bool
    {
        return $this->userRepository->isUserExistByEmail($email);
    }

    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}