<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * Class Migration1588571012ProductAlertCreateTable
 */
class Migration1588571012ProductAlertCreateTable extends MigrationStep
{
    /**
     * @return int
     */
    public function getCreationTimestamp(): int
    {
        return 1588571012;
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     *
     * @throws DBALException
     */
    public function update(Connection $connection): void
    {
        $query = <<<SQL
 CREATE TABLE `product_alert` (
    `id` BINARY(16) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `product_id` BINARY(16) NOT NULL,
    `product_version_id` BINARY(16) NOT NULL,
    `sales_channel_id` BINARY(16) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `updated_at` DATETIME(3) NULL,
    `is_sent` BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (`id`),
    KEY `fk.product_alert.sales_channel_id` (`sales_channel_id`),
    KEY `fk.product_alert.product_id` (`product_id`,`product_version_id`),
    CONSTRAINT `fk.product_alert.sales_channel_id` FOREIGN KEY (`sales_channel_id`) REFERENCES `sales_channel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk.product_alert.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

        $connection->executeUpdate($query);
    }

    /**
     * @inheritDoc
     */
    public function updateDestructive(Connection $connection): void
    {
    }
}
