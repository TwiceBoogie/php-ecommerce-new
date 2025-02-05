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
        $sql = "SELECT p.id, p.product_name, p.product_price, p.product_category,
                pi.image_url AS main_image
                FROM products p
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1";
        return $this->db->select($sql);
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
        $sql = "SELECT p.id, p.product_name, p.product_price, p.product_category, 
                pi.image_url AS main_image
                FROM products p
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                WHERE p.product_category = :category";
        if ($limit !== null) {
            $sql .= " LIMIT " . (int) $limit;
        }
        return $this->db->select($sql, ['category' => $category]);
    }

    /**
     * Retrieves a product by its ID along with detailed information and images.
     *
     * @param int $productId The product ID.
     * @return array         The product details.
     * @throws Exception     If the product is not found.
     */
    public function getProductById(int $productId): array
    {
        $sql = "SELECT p.id, p.product_name, p.product_price, p.product_category, p.product_description, 
                p.stock_quantity,
                pi.image_url AS main_image
                FROM products p
                LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_main = 1
                WHERE p.id = :id";
        $result = $this->db->select($sql, ['id' => $productId]);

        if (empty($result)) {
            throw new Exception("Product not found");
        }

        // Retrieve all images associated with the product.
        $images = $this->db->select(
            "SELECT `image_url` FROM `product_images` WHERE `product_id` = :id",
            ['id' => $productId]
        );
        $result[0]['images'] = array_column($images, 'image_url');

        return $result[0];
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
