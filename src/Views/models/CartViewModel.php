<?php

namespace Sebastian\PhpEcommerce\Views\Models;

use Sebastian\PhpEcommerce\DTO\CartDTO;

class CartViewModel extends BaseViewModel
{
    /**
     * @var CartDTO[]
     */
    private array $cartDTO;

    public function __construct(bool $isAdmin, bool $isAuthenticated, array $cartDTO)
    {
        parent::__construct($isAdmin, $isAuthenticated);
        $this->cartDTO = $cartDTO;
    }

    /**
     * @return CartDTO[]
     */
    public function getCart(): array
    {
        return $this->cartDTO;
    }

}