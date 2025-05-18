<?php

namespace Sebastian\PhpEcommerce\Repository;

use Sebastian\PhpEcommerce\Models\Database;

class CartItemRepository extends BaseRepository
{
    public function __construct(Database $db)
    {
        parent::__construct($db, 'cartItems');
    }
}