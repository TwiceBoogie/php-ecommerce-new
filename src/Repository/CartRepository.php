<?php

namespace Sebastian\PhpEcommerce\Repository;

use Sebastian\PhpEcommerce\Models\Database;

class CartRepository extends BaseRepository
{
    public function __construct(Database $db)
    {
        parent::__construct($db, 'carts');
    }

    public function getCartByUserId(string $userId): array
    {
        return $this->findBy(['user_id' => $userId]);
    }

    public function insertItemIntoCart(string $identifier, string $column, int $productId, int $quantity)
    {
        $this->db->insert('cart_items', [
            $column => $identifier,
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }

    public function updateCartItemQuantity(string $identifier, string $column, int $productId, int $quantity)
    {
        $this->db->update(
            'cart_items',
            ['quantity' => "quantity + :quantity"],
            'product_id = :productId',
            ['productId' => $productId, 'quantity' => $quantity]
        );
    }
}