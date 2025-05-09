<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\DTO\ResponseDTO;
use Sebastian\PhpEcommerce\DTO\UserDetailsDTO;
use Sebastian\PhpEcommerce\Http\Request\UpdateEmailRequest;
use Sebastian\PhpEcommerce\Mapper\UserMapper;
use Sebastian\PhpEcommerce\Repository\UserRepository;
use Sebastian\PhpEcommerce\Services\SecureSession;
use Sebastian\PhpEcommerce\Services\UserService;
use Sebastian\PhpEcommerce\Http\Request\UpdateUserDetailsRequest;

class UserServiceImpl implements UserService
{
    private UserRepository $userRepository;
    private UserMapper $userMapper;

    public function __construct(UserRepository $userRepository, UserMapper $userMapper)
    {
        $this->userRepository = $userRepository;
        $this->userMapper = $userMapper;
    }

    public function getUserDetails(): UserDetailsDTO
    {
        $userDetail = $this->userRepository->getUserDetails(SecureSession::get('user')['id']);
        return $this->userMapper->mapToUserDTO($userDetail);
    }

    public function updateUserDetails(UpdateUserDetailsRequest $request): ResponseDTO
    {
        if ($request->fails()) {
            return new ResponseDTO(
                'error',
                'Validation failed',
                [],
                $request->errors(),
                400
            );
        }
        $name = $request->getName();
        $phone = $request->getPhone();
        $address = $request->getAddress();
        $city = $request->getCity();
        $state = $request->getState();
        $postal = $request->getPostal();
        $country = $request->getCountry();
        $userId = SecureSession::get('user')['id'];

        try {
            $this->userRepository->updateUserDetails(
                $userId,
                $name,
                $phone,
                $address,
                $city,
                $state,
                $postal,
                $country
            );
            $data = $this->userRepository->getUserDetails($userId);
            return new ResponseDTO(
                'success',
                'User details updated',
                [$this->userMapper->mapToUserDTO($data)]
            );
        } catch (\Exception $e) {
            return new ResponseDTO(
                'Internal Server Error',
                'DB error occurred',
                [],
                [],
                501
            );
        }
    }

    public function updateEmail(UpdateEmailRequest $request): ResponseDTO
    {
        if ($request->fails()) {
            return new ResponseDTO(
                'error',
                'Validation failed',
                [],
                $request->errors(),
                400
            );
        }
        $user = $this->userRepository->findById(SecureSession::get('user')['id']);
        if ($user['user_email'] === $request->getEmail()) {
            return new ResponseDTO(
                'success',
                'Email successfully updated',
                [
                    'email' => $user['user_email']
                ],
            );
        }
        $user = $this->userRepository->save([
            "user_email" => $request->getEmail(),
            "id" => SecureSession::get('user')['id']
        ]);

        return new ResponseDTO(
            'success',
            'Email successfully updated',
            [
                'email' => $user['user_email']
            ]
        );
    }

}