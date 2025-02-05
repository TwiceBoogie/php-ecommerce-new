<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;
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

    public function register(array $input): ResponseDTO
    {
        $name = $input["name"] ?? '';
        $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $input['password'] ?? '';
        $confirmPassword = $input['confirmPassword'] ?? '';

        $errors = $this->validateRegisterFields($name, $email, $password, $confirmPassword);
        if ($errors) {
            return new ResponseDTO(
                'error',
                'Validation failed',
                [],
                $errors,
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
                'user_password' => $this->hashPassword($hashedPassword),
            ]);
            $userId = $user['id'];
            $this->userRepository->insertUserDetails($userId);
            SecureSession::set('user_id', $userId);
            SecureSession::regenerate();
        });
    }

    private function validateRegisterFields(string $name, string $email, string $password, string $confirmPassword): array
    {
        $errors = [];

        //check if username is not empty
        if (!$name) {
            $errors['name'] = 'Fullname Required';
        }

        //check if email is not empty
        if (!$email) {
            $errors['email'] = 'Email Required';
        }

        //check if email doesn't exist already
        if (!isset($errors['email']) && $this->userRepository->isUserExistByEmail($email)) {
            $errors['email'] = 'Email Already Exist';
        }

        // Check if password is not empty.
        if (empty($password)) {
            $errors['password'] = 'Password Required';
        }

        //check if password and confirm password are the same
        if (!isset($errors['password']) && $password !== $confirmPassword) {
            $errors['confirmPassword'] = 'Passwords Don"t Match';
        }

        return $errors;
    }

    private function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}