<?php declare(strict_types=1);
/**
 * @package  ProductAlert\Controller
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace ProductAlert\Controller;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductAlert
 *
 * @RouteScope(scopes={"api"})
 */
class ProductAlert extends AbstractController
{
    /**
     * @Route("/api/v{version}/product/alert/sign", name="api.action.product.alert.sign", methods={"POST"})
     *
     * @param Request $request
     * @param Context $context
     *
     * @return JsonResponse
     */
    public function signIn(Request $request, Context $context): JsonResponse
    {
        return new JsonResponse('OK');
    }
}
