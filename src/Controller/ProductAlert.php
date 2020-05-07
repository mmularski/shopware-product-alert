<?php declare(strict_types=1);
/**
 * @package  ProductAlert\Controller
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace ProductAlert\Controller;

use Shopware\Core\Checkout\Customer\SalesChannel\AddressService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
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
     * @var AddressService
     */
    private $addressService;

    /**
     * ProductAlert constructor.
     *
     * @param AddressService $addressService
     */
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * @Route("/sales-channel-api/v{version}/product/alert/sign", name="sales-channel-api.action.product.alert.sign",
     *     methods={"POST"})
     * @RouteScope(scopes={"sales-channel-api"})
     *
     * @param Request $request
     * @param Context $context
     *
     * @return JsonResponse
     */
    public function signIn(Request $request, Context $context): JsonResponse
    {
        $email = $request->get('email');

        return new JsonResponse($email);
    }
}
