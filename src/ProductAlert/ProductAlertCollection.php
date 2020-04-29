<?php declare(strict_types=1);

/**
 * @package  development
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

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
