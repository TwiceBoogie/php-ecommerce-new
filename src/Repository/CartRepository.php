<?php

namespace Sebastian\PhpEcommerce\Repository;

use Sebastian\PhpEcommerce\Models\Database;

class CartRepository extends BaseRepository
{
    public function __construct(Database $db)
    {
        parent::__construct($db, 'cart_items');
    }

    public function getCartBySessionId(string $sessionId)
    {
        return $this->findBy('session_id', $sessionId);
    }

    public function getCart(string $identifier, string $column)
    {
        return $this->findBy($column, $identifier);
    }

    public function insertItemIntoCart(string $identifier, string $column, int $productId, int $quantity)
    {
        $this->db->insert('cart_items', [
            $column => $identifier,
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }

    public function handleLogoutCart(int $userId, string $sessionId)
    {
        $this->db->update(
            'cart_items',
            ['user_id' => null, 'session_id' => $sessionId],
            'user_id = :user_id',
            ['user_id' => $userId]
        );
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