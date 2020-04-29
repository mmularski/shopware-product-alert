<?php declare(strict_types=1);

/**
 * @package  development
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

/**
 * Class ProductAlertEntity
 */
class ProductAlertEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    private $email;

    /**
     * @var int
     */
    private $productId;

    /**
     * @var int
     */
    private $salesChannelId;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     *
     * @return void
     */
    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getSalesChannelId(): int
    {
        return $this->salesChannelId;
    }

    /**
     * @param int $salesChannelId
     *
     * @return void
     */
    public function setSalesChannelId(int $salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }
}