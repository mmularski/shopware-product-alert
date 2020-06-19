<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

/**
 * Class DivanteProductAlert
 */
class DivanteProductAlert extends Plugin
{
    /**
     * @inheritDoc
     *
     * @throws DBALException
     */
    public function uninstall(UninstallContext $context): void
    {
        if ($context->keepUserData()) {
            parent::uninstall($context);

            return;
        }

        /** @var Connection $connection */
        $connection = $this->container->get(Connection::class);

        $this->cleanData($connection);

        parent::uninstall($context);
    }

    /**
     * @param Connection $connection
     *
     * @return void
     *
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    private function cleanData(Connection $connection): void
    {
        $connection->exec('DROP TABLE IF EXISTS `product_alert`');

        $ids = $connection->createQueryBuilder()
            ->select('id')
            ->from('mail_template_type')
            ->where('technical_name = :technicalName')
            ->setParameter('technicalName', 'product.stock.alert')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        $connection->createQueryBuilder()
            ->delete('mail_template')
            ->where('mail_template_type_id IN(:ids)')
            ->setParameter('ids', $ids, Connection::PARAM_STR_ARRAY)
            ->execute();

        $connection->delete('mail_template_type', ['technical_name' => 'product.stock.alert']);
    }
}
