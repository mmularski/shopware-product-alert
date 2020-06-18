<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

use Divante\ProductAlert\Controller\ProductAlertController;
use Divante\ProductAlert\ProductAlert\ProductAlertEntityDefinition;
use Divante\ProductAlert\Test\Integration\Utils\ProductCreator;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelDomain\SalesChannelDomainEntity;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @var ProductAlertController|object|null
     */
    protected $controller;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var ProductCreator
     */
    protected $productCreator;

    /**
     * @var SalesChannelEntity
     */
    protected $salesChannel;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->productAlertRepository = $this->getContainer()->get('product_alert.repository');
        $this->controller = $this->getContainer()->get(ProductAlertController::class);
        $this->context = Context::createDefaultContext();
        $this->salesChannel = $this->retrieveContext()->getSalesChannelId();
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
        $request->set(ProductAlertEntityDefinition::FIELD_SALES_CHANNEL_ID, $this->salesChannel);

        $result = $this->controller->signIn($request, $this->context);
        $this->assertResponse($result);

        $resultData = json_decode($result->getContent(), true);

        $this->assertFalse($resultData['error']);
    }

    /**
     *
     */
    public function testFailureSignupRequest()
    {
        $productId = $this->productCreator->createProduct();

        $request = new RequestDataBag();
        $request->set(ProductAlertEntityDefinition::FIELD_PRODUCT_ID, $productId);
        $request->set(ProductAlertEntityDefinition::FIELD_EMAIL, Uuid::randomHex() . '@example.com');
        $request->set(ProductAlertEntityDefinition::FIELD_SALES_CHANNEL_ID, $this->salesChannel);

        $result = $this->controller->signIn($request, $this->context);
        $this->assertResponse($result);
        $resultData = json_decode($result->getContent(), true);
        $this->assertFalse($resultData['error']);

        $result = $this->controller->signIn($request, $this->context);
        $resultData = json_decode($result->getContent(), true);
        $this->assertTrue($resultData['error']);
    }

    /**
     * @param JsonResponse $response
     *
     * @return void
     */
    public function assertResponse(JsonResponse $response): void
    {
        $resultData = json_decode($response->getContent(), true);

        $this->assertIsArray($resultData);
        $this->assertArrayHasKey('error', $resultData);
        $this->assertArrayHasKey('message', $resultData);
    }

    /**
     * @return SalesChannelDomainEntity
     */
    private function retrieveContext(): SalesChannelDomainEntity
    {
        /** @var EntityRepositoryInterface $repository */
        $repository = $this->getContainer()->get('sales_channel_domain.repository');

        return $repository->search(new Criteria(), Context::createDefaultContext())->first();
    }
}
