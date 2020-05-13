<?php declare(strict_types=1);
/**
 * @package  ProductAlert\Controller
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace ProductAlert\Controller;

use ProductAlert\SalesChannel\ProductAlertService;
use Shopware\Core\Checkout\Customer\SalesChannel\AddressService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductAlert
 */
class ProductAlert extends AbstractController
{
    /**
     * @var ProductAlertService
     */
    private $productAlertService;

    /**
     * ProductAlert constructor.
     *
     * @param ProductAlertService $productAlertService
     */
    public function __construct(ProductAlertService $productAlertService)
    {
        $this->productAlertService = $productAlertService;
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
        $this->productAlertService->insert($request, $context);

        return new JsonResponse(true);
    }
}
