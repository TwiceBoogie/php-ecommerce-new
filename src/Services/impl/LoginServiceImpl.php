<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;
use Sebastian\PhpEcommerce\Repository\CartRepository;
use Sebastian\PhpEcommerce\Repository\LoginRepository;
use Sebastian\PhpEcommerce\Repository\UserRepository;
use Sebastian\PhpEcommerce\Services\LoginService;
use Sebastian\PhpEcommerce\Services\SecureSession;

class LoginServiceImpl implements LoginService
{
    private LoginRepository $loginRepository;
    private UserRepository $userRepository;
    private CartRepository $cartRepository;

    public function __construct(
        LoginRepository $loginRepository,
        UserRepository $userRepository,
        CartRepository $cartRepository,
    ) {
        $this->loginRepository = $loginRepository;
        $this->userRepository = $userRepository;
        $this->cartRepository = $cartRepository;
    }

    public function login(array $input): ResponseDTO
    {
        $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $input['password'] ?? '';

        $errors = $this->validateLoginFields($email, $password);
        // validation errors
        if ($errors) {
            return new ResponseDTO(
                'error',
                'Validation failed',
                [],
                $errors,
                400
            );
        }
        $user = $this->userRepository->findBy('email', $email);
        if (count($user) !== 1) {
            return $this->handleInvalidLogin($email);
        }
        if (!password_verify($password, $user[0]['password'])) {
            return $this->handleInvalidLogin($email);
        }
        if ($user[0]['confirmed'] === "N") {
            return new ResponseDTO(
                'error',
                'User not confirmed',
                [],
                ['email' => '', 'password' => 'User Not Confirmed'],
                401
            );
        }
        // TODO: merge users cart from session id to user id
        // creds are valid so make new login entry
        $this->createUserSession($user[0]['user_id']);
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
        $userId = SecureSession::get('user_id');
        $cart = $this->cartRepository->getCart($userId, 'user_id');
        SecureSession::destroySession();
        SecureSession::regenerate();
        if (count($cart) !== 0) {
            $this->cartRepository->handleLogoutCart($userId, SecureSession::getSessionId());
        }
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

    private function validateLoginFields($email, $password)
    {
        $errors = [];

        if (!$email) {
            $errors['email'] = 'Email Required';
        }

        if (empty($password)) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        }

        return $errors;
    }

    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    private function createUserSession(int $userId)
    {
        SecureSession::regenerate();
        SecureSession::set('user_id', $userId);
    }

}