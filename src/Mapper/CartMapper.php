<?php

namespace Sebastian\PhpEcommerce\Mapper;

use Sebastian\PhpEcommerce\DTO\CartDTO;

class CartMapper extends BaseMapper
{
    public function mapToCartDTO(array $cart): CartDTO
    {
        return $this->mapArrayToDTO($cart, CartDTO::class);
    }

    public function mapToCartDTOArray(array $carts): array
    {
        return $this->mapArrayToDTOArray($carts, CartDTO::class);
    }
}