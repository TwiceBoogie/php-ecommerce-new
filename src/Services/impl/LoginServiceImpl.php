<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;
use Sebastian\PhpEcommerce\Http\Request\LoginRequest;
use Sebastian\PhpEcommerce\Repository\LoginRepository;
use Sebastian\PhpEcommerce\Repository\UserRepository;
use Sebastian\PhpEcommerce\Services\CartService;
use Sebastian\PhpEcommerce\Services\LoginService;
use Sebastian\PhpEcommerce\Services\SecureSession;

class LoginServiceImpl implements LoginService
{
    private LoginRepository $loginRepository;
    private UserRepository $userRepository;
    private CartService $cartService;

    public function __construct(
        LoginRepository $loginRepository,
        UserRepository $userRepository,
        CartService $cartService,
    ) {
        $this->loginRepository = $loginRepository;
        $this->userRepository = $userRepository;
        $this->cartService = $cartService;
    }

    public function login(LoginRequest $request): ResponseDTO
    {
        // validation errors
        if ($request->fails()) {
            return new ResponseDTO(
                'error',
                'Validation failed',
                [],
                $request->errors(),
                400
            );
        }

        $email = $request->getEmail();
        $password = $request->getPassword();
        $user = $this->userRepository->findUserByEmail($email);
        if (count($user) !== 1) {
            return $this->handleInvalidLogin($email);
        }
        if (!password_verify($password, $user[0]['user_password'])) {
            return $this->handleInvalidLogin($email);
        }
        if (ENVIRONMENT === "production") {
            if ($user[0]['confirmed'] === "N") {
                return new ResponseDTO(
                    'error',
                    'User not confirmed',
                    [],
                    ['email' => '', 'password' => 'User Not Confirmed'],
                    401
                );
            }
        }
        // TODO: merge users cart from session id to user id
        $this->cartService->getCart();
        // creds are valid so make new login entry
        $this->createUserSession($user[0]['id']);
        return new ResponseDTO(
            'success',
            'Login successful',
            ['page' => '/account'],
            [],
            200
        );
    }

    public function logout(): ResponseDTO
    {
        $userId = SecureSession::get('user')['id'];
        if (!$userId) {
            return new ResponseDTO(
                'error',
                'Bad error request',
                [],
                [],
                400
            );
        }
        // TODO: persist cart into db
        SecureSession::destroySession();
        return new ResponseDTO(
            'success',
            'Successfully logged out',
        );
    }

    private function handleInvalidLogin(string $email): ResponseDTO
    {
        if ($this->loginRepository->isLoginAttemptExceeded($email)) {
            return new ResponseDTO(
                'error',
                'Login attempt exceeded, please try again in 15 minutes',
                [],
                [],
                400
            );
        }
        $this->loginRepository->insertFailedLogin($email);
        return new ResponseDTO(
            'error',
            'Invalid credentials',
            [],
            ['email' => '', 'password' => 'Invalid email or password'],
            401
        );
    }

    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private function createUserSession(int $userId)
    {
        SecureSession::regenerate();
        SecureSession::set('user', [
            'id' => $userId
        ]);
    }

}