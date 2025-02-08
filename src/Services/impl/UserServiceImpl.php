<?php

namespace Sebastian\PhpEcommerce\Services\Impl;

use Sebastian\PhpEcommerce\DTO\UserDetailsDTO;
use Sebastian\PhpEcommerce\Mapper\UserMapper;
use Sebastian\PhpEcommerce\Repository\UserRepository;
use Sebastian\PhpEcommerce\Services\SecureSession;
use Sebastian\PhpEcommerce\Services\UserService;

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
        $userDetail = $this->userRepository->getUserDetails(SecureSession::get('user_id'));
        return $this->userMapper->mapToUserDTO($userDetail);
    }

}