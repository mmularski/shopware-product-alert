<?php declare(strict_types=1);

/**
 * @package  shopware_dev
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

use Mularski\ProductAlert\Controller\ProductAlert;
use Mularski\ProductAlert\ProductAlert\ProductAlertEntityDefinition;
use Mularski\ProductAlert\Test\Integration\Utils\CustomerCreator;
use Mularski\ProductAlert\Test\Integration\Utils\ProductCreator;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\PlatformRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductAlertTest
 */
class ProductAlertTest extends TestCase
{
    use IntegrationTestBehaviour;

    /**
     * @var object|EntityRepository|null
     */
    protected $productAlertRepository;

    /**
     * @var ProductAlert|object|null
     */
    protected $controller;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var CustomerCreator
     */
    protected $customerCreator;

    /**
     * @var ProductCreator
     */
    protected $productCreator;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productAlertRepository = $this->getContainer()->get('product_alert.repository');
        $this->controller = $this->getContainer()->get(ProductAlert::class);
        $this->context = Context::createDefaultContext();
        $this->customerCreator = new CustomerCreator($this->getContainer());
        $this->productCreator = new ProductCreator($this->getContainer());
    }

    /**
     *
     */
    public function testSignupRequest()
    {
        $productId = $this->productCreator->createProduct();

        $request = new RequestDataBag();
        $request->set(ProductAlertEntityDefinition::FIELD_PRODUCT_ID, $productId);
        $request->set(ProductAlertEntityDefinition::FIELD_EMAIL, Uuid::randomHex() . '@example.com');

        $result = $this->controller->signIn($request, $this->context);
        $this->assertResponse($result);

        $resultData = json_decode($result->getContent());

        $this->assertTrue($resultData['error']);
    }

    /**
     * @param JsonResponse $response
     *
     * @return void
     */
    public function assertResponse(JsonResponse $response): void
    {
        $resultData = json_decode($response->getContent());

        $this->assertIsArray($resultData);
        $this->assertArrayHasKey('error', $resultData);
        $this->assertArrayHasKey('message', $resultData);
    }
}
