<?php declare(strict_types=1);

/**
 * @package  ProductAlert\Controller
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace ProductAlert\ProductAlert;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
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
        return self::ENTITY_NAME;
    }

    /**
     * @return string
     */
    public function getCollectionClass(): string
    {
        return ProductAlertCollection::class;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return ProductAlertEntity::class;
    }

    /**
     * @return FieldCollection
     */
    protected function defineFields(): FieldCollection
    {
        return new FieldCollection(
            [
                (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
                (new StringField('email', 'email'))->addFlags(new Required()),
                (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new Required()),
                (new ReferenceVersionField(ProductDefinition::class))->addFlags(new Required()),

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