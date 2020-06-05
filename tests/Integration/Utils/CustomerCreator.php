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
use Shopware\Core\Framework\Test\TestCaseBase\BasicTestDataBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CustomerCreator
 */
class CustomerCreator
{
    use BasicTestDataBehaviour;

    /**
     * @var string
     */
    private $charPool = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CustomerCreator constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @return string
     */
    public function createCustomer(): string
    {
        $id = Uuid::randomHex();
        $addressId = Uuid::randomHex();

        $customer = [
            'id' => $id,
            'number' => Uuid::randomHex(),
            'salutationId' => $this->getValidSalutationId(),
            'firstName' => Uuid::randomHex(),
            'lastName' => Uuid::randomHex(),
            'customerNumber' => Uuid::randomHex(),
            'email' => Uuid::randomHex() . '@example.com',
            'password' => 'lubieplacki',
            'defaultPaymentMethodId' => $this->getValidPaymentMethodId(),
            'groupId' => Defaults::FALLBACK_CUSTOMER_GROUP,
            'salesChannelId' => Defaults::SALES_CHANNEL,
            'defaultBillingAddressId' => $addressId,
            'defaultShippingAddressId' => $addressId,
            'addresses' => [
                [
                    'id' => $addressId,
                    'customerId' => $id,
                    'countryId' => $this->getValidCountryId(),
                    'salutationId' => $this->getValidSalutationId(),
                    'firstName' => Uuid::randomHex(),
                    'lastName' => Uuid::randomHex(),
                    'street' => Uuid::randomHex(),
                    'zipcode' => Uuid::randomHex(),
                    'city' => Uuid::randomHex(),
                ],
            ],
        ];

        $this->container->get('customer.repository')->create([$customer], Context::createDefaultContext());

        return $id;
    }
}
