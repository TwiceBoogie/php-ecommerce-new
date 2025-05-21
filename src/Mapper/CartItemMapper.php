<?php

namespace Sebastian\PhpEcommerce\Mapper;

use Sebastian\PhpEcommerce\DTO\CartItemDTO;

class CartItemMapper extends BaseMapper
{
    public function mapToCartItemDTO(array $cartItem): CartItemDTO
    {
        return $this->mapArrayToDTO($cartItem, CartItemDTO::class);
    }

    /**
     * Map an array of cartItems to an array of CartItemDTO objects
     * @param array $cartItems
     * @return CartItemDTO[]
     */
    public function mapToCartItemDTOArray(array $cartItems): array
    {
        return $this->mapArrayToDTOArray($cartItems, CartItemDTO::class);
    }
}