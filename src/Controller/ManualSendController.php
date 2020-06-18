<?php declare(strict_types=1);
/**
 * @package  Mularski\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Mularski\ProductAlert\Controller;

use Mularski\ProductAlert\Service\ProductAlertService;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ManualSendController
 *
 * @RouteScope(scopes={"api"})
 */
class ManualSendController extends AbstractController
{
    /**
     * @var ProductAlertService
     */
    private $productAlertService;

    /**
     * ManualSendController constructor.
     *
     * @param ProductAlertService $productAlertService
     */
    public function __construct(ProductAlertService $productAlertService)
    {
        $this->productAlertService = $productAlertService;
    }

    /**
     * @Route("/api/v{version}/_action/product/alert/process", name="api.action.prodict.alert.process", methods={"GET"})
     */
    public function execute(): Response
    {
        $affected = $this->productAlertService->process();

        return new Response((string) $affected, Response::HTTP_OK);
    }
}
