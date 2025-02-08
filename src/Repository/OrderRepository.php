<?php

namespace Sebastian\PhpEcommerce\Repository;

use Sebastian\PhpEcommerce\Models\Database;


class OrderRepository extends BaseRepository
{
    public function __construct(Database $db)
    {
        parent::__construct($db, 'orders');
    }

    public function getOrders(int $userId): array
    {
        $result = $this->db->select(
            "SELECT * FROM `orders` WHERE `user_id` = :userId",
            ["userId" => $userId]
        );

        return $result[0] ?? [];
    }
}