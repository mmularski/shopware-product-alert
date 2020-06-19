<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\Controller;

use Divante\ProductAlert\Service\SalesChannel\ProductAlertPersistorInterface;
use Divante\ProductAlert\Service\SalesChannel\Validation\Exception\AlreadySignedException;
use Psr\Log\LoggerInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductAlertController
 */
class ProductAlertController extends AbstractController
{
    /**
     * @var ProductAlertPersistorInterface
     */
    private $productAlertService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ProductAlert constructor.
     *
     * @param ProductAlertPersistorInterface $productAlertService
     * @param LoggerInterface $logger
     */
    public function __construct(ProductAlertPersistorInterface $productAlertService, LoggerInterface $logger)
    {
        $this->productAlertService = $productAlertService;
        $this->logger = $logger;
    }

    /**
     * @Route("/sales-channel-api/v{version}/product/alert/sign", name="sales-channel-api.action.product.alert.sign",
     *     methods={"POST"})
     * @RouteScope(scopes={"sales-channel-api"})
     *
     * @param RequestDataBag $request
     * @param Context $context
     *
     * @return JsonResponse
     */
    public function signIn(RequestDataBag $request, Context $context): JsonResponse
    {
        $response = [
            'error' => false,
            'message' => 'Successfully signed to product stock notification!',
        ];

        try {
            $this->productAlertService->subscribe($request, $context);
        } catch (AlreadySignedException $ex) {
            $response['error'] = true;
            $response['message'] = $ex->getMessage();

        } catch (\Exception $ex) {
            $this->logger->warning($ex->getMessage(), $ex->getTrace());

            $response['error'] = true;
            $response['message'] = 'Something went wrong during product alert signing';
        }

        return new JsonResponse($response);
    }
}
