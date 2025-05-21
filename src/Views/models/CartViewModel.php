<?php

namespace Sebastian\PhpEcommerce\Views\Models;

use Sebastian\PhpEcommerce\DTO\CartDTO;

class CartViewModel extends BaseViewModel
{
    private CartDTO $cartDTO;

    public function __construct(bool $isAdmin, bool $isAuthenticated, CartDTO $cartDTO)
    {
        parent::__construct($isAdmin, $isAuthenticated);
        $this->cartDTO = $cartDTO;
    }

    /**
     * @return CartDTO[]
     */
    public function getCart(): CartDTO
    {
        return $this->cartDTO;
    }

}