<?php

namespace Sebastian\PhpEcommerce\Views\Models;

use Sebastian\PhpEcommerce\DTO\ProductDTO;

class HomeViewModel extends BaseViewModel
{
    /**
     * @var ProductDTO[]
     */
    private array $mice;

    /**
     * @var ProductDTO[]
     */
    private array $keyboards;

    /**
     * @param bool $isAdmin
     * @param bool $isAuthenticated
     * @param ProductDTO[] $mice
     * @param ProductDTO[] $keyboards
     */
    public function __construct(bool $isAdmin, bool $isAuthenticated, array $mice, array $keyboards)
    {
        parent::__construct($isAdmin, $isAuthenticated);
        $this->mice = $mice;
        $this->keyboards = $keyboards;
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
