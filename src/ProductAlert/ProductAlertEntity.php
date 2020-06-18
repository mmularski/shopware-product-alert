<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\ProductAlert;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

/**
 * Class ProductAlertEntity
 */
class ProductAlertEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var int
     */
    protected $productId;

    /**
     * @var int
     */
    protected $salesChannelId;

    /**
     * @var bool
     */
    protected $isSent;

    /**
     * @var ProductEntity
     */
    protected $product;

    /**
     * @var SalesChannelEntity
     */
    protected $salesChannel;

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

    /**
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->isSent;
    }

    /**
     * @param bool $isSent
     */
    public function setIsSent(bool $isSent): void
    {
        $this->isSent = $isSent;
    }

    /**
     * @return ProductEntity
     */
    public function getProduct(): ?ProductEntity
    {
        return $this->product;
    }

    /**
     * @return SalesChannelEntity
     */
    public function getSalesChannel(): ?SalesChannelEntity
    {
        return $this->salesChannel;
    }
}