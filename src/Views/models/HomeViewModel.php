<?php

namespace Sebastian\PhpEcommerce\Views\Models;

use Sebastian\PhpEcommerce\DTO\ProductDTO;

class HomeViewModel
{
    /**
     * @var ProductDTO[]
     */
    private array $mice;

    /**
     * @var ProductDTO[]
     */
    private array $keyboards;

    private bool $isAdmin;

    /**
     * @param bool $isAdmin
     * @param ProductDTO[] $mice
     * @param ProductDTO[] $keyboards
     */
    public function __construct(bool $isAdmin, array $mice, array $keyboards)
    {
        $this->isAdmin = $isAdmin;
        $this->mice = $mice;
        $this->keyboards = $keyboards;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    /**
     * @return ProductDTO[]
     */
    public function getMice(): array
    {
        return $this->mice;
    }

    /**
     * @return ProductDTO[]
     */
    public function getKeyboards(): array
    {
        return $this->keyboards;
    }
}
