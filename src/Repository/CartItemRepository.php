<?php

namespace Sebastian\PhpEcommerce\Repository;

use Sebastian\PhpEcommerce\Models\Database;

class CartItemRepository extends BaseRepository
{
    public function __construct(Database $db)
    {
        parent::__construct($db, 'cart_items');
    }

    public function getCartItemsByCartId(int $cartId): array
    {
        $sql = "SELECT
                    ci.id,
                    ci.quantity,
                    p.id AS productId,
                    p.name,
                    p.category,
                    p.price
                FROM $this->table ci
                JOIN `products` p ON ci.product_id = p.id
                WHERE ci.cart_id = :cartId
                ";
        $result = $this->db->select($sql, ['cartId' => $cartId]);
        return array_map(function ($row) {
            return [
                'id' => $row['id'],
                'quantity' => $row['quantity'],
                'product' => [
                    'id' => $row['productId'],
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'price' => $row['price']
                ]
            ];
        }, $result);
    }

    public function updateCartItemQuantity(int $cartItemId, int $quantity): void
    {
        $this->db->update(
            $this->table,
            ['quantity' => $quantity],
            'id => :cartItemId',
            ['cartItemId' => $cartItemId]
        );
    }
}