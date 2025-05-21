<?php

namespace Sebastian\PhpEcommerce\Repository;

use Sebastian\PhpEcommerce\Models\Database;
use Exception;
use Sebastian\PhpEcommerce\Repository\Projections\ProductProjection;

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
     * @return ?ProductProjection         The product details with images.
     * @throws Exception     If the product is not found.
     */
    public function getProductById(int $productId): ?ProductProjection
    {
        $products = $this->getProductByIds([$productId]);
        return $products[0] ?? null;
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
        return array_map(function (array $row) {
            return new ProductProjection(
                (int) $row['id'],
                $row['name'],
                $row['category'],
                (float) $row['price'],
                $row['main_image'],
                json_decode($row['images'], true) ?? []
            );
        }, $result);
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
        $sql = "SELECT
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
}
