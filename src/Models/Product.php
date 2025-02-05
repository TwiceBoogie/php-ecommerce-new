<?php

namespace Sebastian\PhpEcommerce\Models;

class Product {
    public static function getAll() {
        return [
            ['id' => 1, 'name' => 'Keyboard', 'price' => 49.99],
            ['id' => 2, 'name' => 'Mouse', 'price' => 19.99],
        ];
    }
}