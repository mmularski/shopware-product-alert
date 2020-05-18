<?php declare(strict_types=1);
/**
 * @package Mularski\ProductAlert
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace Mularski\ProductAlert\ProductAlert;

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
     * @var bool
     */
    private $isSent;

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
}