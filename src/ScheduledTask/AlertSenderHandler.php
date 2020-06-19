<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\ScheduledTask;

use Divante\ProductAlert\Service\ProductAlertServiceInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;

/**
 * Class AlertSenderHandler
 */
class AlertSenderHandler extends ScheduledTaskHandler
{
    /**
     * @var ProductAlertServiceInterface
     */
    private $productAlertService;

    /**
     * AlertSenderHandler constructor.
     *
     * @param EntityRepositoryInterface $scheduledTaskRepository
     * @param ProductAlertServiceInterface $productAlertService
     */
    public function __construct(
        EntityRepositoryInterface $scheduledTaskRepository,
        ProductAlertServiceInterface $productAlertService
    ) {
        parent::__construct($scheduledTaskRepository);

        $this->productAlertService = $productAlertService;
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        return [AlertSender::class];
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $this->productAlertService->process();

        return;
    }
}
