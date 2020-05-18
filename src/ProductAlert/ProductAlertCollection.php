<?php declare(strict_types=1);
/**
 * @package Mularski\ProductAlert
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace Mularski\ProductAlert\ProductAlert;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * Class ProductAlertCollection
 *
 * @method void              add(ProductAlertEntity $entity)
 * @method void              set(string $key, ProductAlertEntity $entity)
 * @method ProductAlertEntity[]    getIterator()
 * @method ProductAlertEntity[]    getElements()
 * @method ProductAlertEntity|null get(string $key)
 * @method ProductAlertEntity|null first()
 * @method ProductAlertEntity|null last()
 *
 */
class ProductAlertCollection extends EntityCollection
{
    /**
     * @return string
     */
    protected function getExpectedClass(): string
    {
        return ProductAlertEntity::class;
    }
}
