<?php

namespace Sebastian\PhpEcommerce\Mapper;

use Sebastian\PhpEcommerce\DTO\OrderDTO;
use Sebastian\PhpEcommerce\Mapper\BaseMapper;

class OrderMapper extends BaseMapper
{
    public function mapToOrderDTO(array $order): OrderDTO
    {
        return $this->mapArrayToDTO($order, OrderDTO::class);
    }

    public function mapToOrderDTOArray(array $orders): array
    {
        return $this->mapArrayToDTOArray($orders, OrderDTO::class);
    }
}