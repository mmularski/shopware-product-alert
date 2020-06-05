<?php declare(strict_types=1);

/**
 * @package  shopware_dev
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Mularski\ProductAlert\Test\Integration\Utils;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ProductCreator
 */
class ProductCreator
{
    /**
     * @var string
     */
    protected $categoryId;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ProductCreator constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     *
     * @throws Exception
     */
    public function createProduct(): string
    {
        if (!$this->categoryId) {
            $this->categoryId = Uuid::randomHex();
        }

        $productData = [
            'id' => Uuid::randomHex(),
            'productNumber' => Uuid::randomHex(),
            'stock' => 1,
            'name' => 'test',
            'price' => [
                [
                    'currencyId' => Defaults::CURRENCY,
                    'gross' => random_int(1, 100),
                    'net' => random_int(1, 100),
                    'linked' => false,
                ],
            ],
            'manufacturer' => ['name' => 'test'],
            'tax' => [
                'name' => 'test',
                'taxRate' => 15,
            ],
            'categories' => [
                [
                    'id' => $this->categoryId,
                    'name' => 'test',
                ],
            ],
        ];

        $this->container->get('product.repository')->create($productData, Context::createDefaultContext());

        return $productData['id'];
    }
}
