<?php

namespace Sebastian\PhpEcommerce\Mapper;

use Sebastian\PhpEcommerce\DTO\UserDetailsDTO;

class UserMapper extends BaseMapper
{
    public function mapToUserDTO(array $user): UserDetailsDTO
    {
        return $this->mapArrayToDTO($user, UserDetailsDTO::class);
    }

    public function mapToUserDTOArray(array $user): array
    {
        return $this->mapArrayToDTOArray($user, UserDetailsDTO::class);
    }
}