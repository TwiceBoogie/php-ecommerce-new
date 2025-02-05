<?php

namespace Sebastian\PhpEcommerce\Mapper;

use Sebastian\PhpEcommerce\DTO\ProductDTO;

class ProductMapper extends BaseMapper
{
    /**
     * Map a single product array to a ProductDTO.
     *
     * @param array $product
     * @return ProductDTO
     */
    public function mapToProductDTO(array $product): ProductDTO
    {
        return $this->mapArrayToDTO($product, ProductDTO::class);
    }

    /**
     * Map an array of product arrays to an array of ProductDTO objects.
     *
     * @param array $products
     * @return ProductDTO[]
     */
    public function mapToProductDTOArray(array $products): array
    {
        return $this->mapArrayToDTOArray($products, ProductDTO::class);
    }
}
