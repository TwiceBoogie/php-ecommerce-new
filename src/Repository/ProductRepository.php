<?php

namespace Sebastian\PhpEcommerce\Repository;

use Sebastian\PhpEcommerce\Models\Database;
use Exception;

/**
 * Class ProductRepository
 *
 * Repository for handling product-related operations.
 */
class ProductRepository extends BaseRepository
{
    /**
     * ProductRepository constructor.
     *
     * @param Database $db The database connection.
     */
    public function __construct(Database $db)
    {
        parent::__construct($db, 'products');
    }

    /**
     * Retrieves all products along with their main image.
     *
     * @return array List of products.
     */
    public function getAll(): array
    {
        $sql = "SELECT p.id, p.name, p.price, p.category,
                pi.image_url AS main_image
                FROM products p
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1";
        return $this->db->select($sql);
    }

    public function getMainImageByProductIds(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }
        $placeholders = [];
        $bindings = [];

        foreach ($productIds as $id) {
            $placeholders[] = ":product_$id";
            $bindings["product_$id"] = $id;
        }
        $sql = "SELECT `product_id` AS `productId`, `image_url`
                FROM `product_images`
                WHERE `is_main` = 1 AND `product_id` IN (" . implode(", ", $placeholders) . ")";
        $result = $this->db->select($sql, $bindings);
        return empty($result) ? [] : $result;
    }

    /**
     * Retrieves products by category with an optional limit.
     *
     * @param string   $category The product category.
     * @param int|null $limit    Optional limit on the number of products.
     * @return array            List of products.
     */
    public function getProductsByCategory(string $category, ?int $limit = null): array
    {
        $sql = "SELECT p.id, p.name, p.price, p.category, 
                pi.image_url AS main_image
                FROM products p
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                WHERE p.category = :category";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int) $limit;
        }
        return $this->db->select($sql, ['category' => $category]);
    }

    /**
     * Retrieves a product by its ID along with detailed information and images.
     *
     * @param int $productId The product ID.
     * @return array         The product details with images.
     * @throws Exception     If the product is not found.
     */
    public function getProductById(int $productId): array
    {
        $products = $this->getProductByIds([$productId]);
        return $products;
    }

    public function getProductByIds(array $productIds): array
    {
        $placeholders = [];
        $bindings = [];
        foreach ($productIds as $id) {
            $placeholders[] = ":product_$id";
            $bindings["product_$id"] = $id;
        }
        $sql = "SELECT
                    p.id,
                    p.name,
                    p.price,
                    p.category,
                    p.description,
                    p.stock_quantity,
                    -- Subquery to get main image
                    (
                        SELECT pi.image_url
                        FROM `product_images` pi
                        WHERE pi.product_id = p.id AND pi.is_main = 1
                        LIMIT 1
                    ) AS main_image,
                    -- subquery to get th rest of the images (excluding is_main)
                    (
                        SELECT JSON_ARRAYAGG(pi2.image_url)
                        FROM `product_images` pi2
                        WHERE pi2.product_id = p.id AND (pi2.is_main = 0)
                    ) AS images
                FROM `products` p
                WHERE p.id IN (" . implode(", ", $placeholders) . ")";
        $result = $this->db->select($sql, $bindings);
        // decode the json array of images since its a string
        foreach ($result as &$row) {
            $row['images'] = json_decode($row['images'], true) ?? [];
        }
        return $result;
    }

    /**
     * Checks if the specified product has enough stock for the requested quantity.
     *
     * @param int $productId The product ID.
     * @param int $quantity  The requested quantity.
     * @return bool          True if stock is sufficient, false otherwise.
     * @throws Exception     If the product is not found.
     */
    public function productStockAvailable(int $productId, int $quantity): bool
    {
        $sql = "SELECT id, stock_quantity,
                CASE 
                    WHEN stock_quantity >= :quantity THEN 1
                    ELSE 0
                END as enough_stock
                FROM products
                WHERE id = :product_id";
        $result = $this->db->select($sql, ['product_id' => $productId, 'quantity' => $quantity]);

        if (empty($result)) {
            throw new Exception("Product ID {$productId} not found.");
        }

        return $result[0]['enough_stock'] == 1;
    }

    /**
     * Updates the product's stock by subtracting the specified quantity.
     *
     * @param int $productId The product ID.
     * @param int $quantity  The quantity to subtract.
     * @return void
     */
    public function updateProductStock(int $productId, int $quantity): void
    {
        $sql = "UPDATE `products` 
            SET `stock_quantity` = `stock_quantity` - :quantity 
            WHERE `id` = :id";
        $this->db->executeWithTransaction($sql, [
            'quantity' => $quantity,
            'id' => $productId
        ]);
    }

    /**
     * Updates stock and adds items to the cart in a transactional manner.
     *
     * @param int   $userId    The user ID.
     * @param array $cartItems List of cart items, each with keys 'product_id' and 'quantity'.
     * @return void
     * @throws Exception If any operation fails.
     */
    public function updateStock(int $userId, array $cartItems): void
    {
        $this->db->beginTransaction(); // Start transaction

        try {
            foreach ($cartItems as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];

                // Step 1: Check if the product exists and has enough stock.
                $sql = "SELECT id, stock_quantity,
                        CASE 
                            WHEN stock_quantity >= :quantity THEN 1
                            ELSE 0
                        END as enough_stock
                        FROM products
                        WHERE id = :id";
                $result = $this->db->select($sql, ['id' => $productId, 'quantity' => $quantity]);

                if (empty($result)) {
                    throw new Exception("Product ID {$productId} not found.");
                }

                if ($result[0]['enough_stock'] == 0) {
                    throw new Exception("Not enough stock for product ID {$productId}. Available: {$result[0]['stock_quantity']}, Requested: {$quantity}");
                }

                // Step 2: Reduce stock quantity in products table.
                $sqlUpdate = "UPDATE `products` SET `stock_quantity` = `stock_quantity` - :quantity WHERE `id` = :id";
                $this->db->executeWithTransaction($sqlUpdate, [
                    'quantity' => $quantity,
                    'id' => $productId
                ]);

                // Step 3: Insert into cart_items table.
                $this->db->insert('cart_items', [
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
            }

            $this->db->commit(); // Commit transaction
        } catch (Exception $e) {
            $this->db->rollBack(); // Roll back if any failure occurs
            throw $e;
        }
    }
}
