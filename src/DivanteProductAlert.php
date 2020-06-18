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

        $sql = <<<SQL
DROP TABLE IF EXISTS `product_alert`;
DELETE FROM `mail_template` WHERE `mail_template_type_id` IN (SELECT `id` FROM `mail_template_type` WHERE `technical_name`=`product.stock.alert`);
DELETE FROM `mail_template_type` WHERE `technical_name`=`product.stock.alert`;
SQL;

        /** @var Connection $connection */
        $connection = $this->container->get(Connection::class);
        $connection->exec($sql);

        parent::uninstall($context);
    }
}
