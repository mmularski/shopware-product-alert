<?php declare(strict_types=1);

/**
 * @package  development
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

/**
 * Class ProductAlertEntityDefinition
 */
class ProductAlertEntityDefinition extends EntityDefinition
{
    /** @var string */
    public const ENTITY_NAME = 'product_alert';

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        // TODO: Implement getEntityName() method.
    }

    /**
     * @return string
     */
    public function getCollectionClass(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return '';
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
                (new StringField('email', 'email'))->addFlags(new Required(), new Unique),
                (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new Required()),
                (new FkField('sales_channel_id', 'salesChannelId', SalesChannelDefinition::class))
                    ->addFlags(new Required()),
                new ManyToOneAssociationField(
                    'salesChannel',
                    'sales_channel_id',
                    SalesChannelDefinition::class,
                    'id',
                    false
                ),
                new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class, 'id', false),
            ]
        );
    }
}