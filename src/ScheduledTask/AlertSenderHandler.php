<?php declare(strict_types=1);
/**
 * @package Mularski\ProductAlert
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace Mularski\ProductAlert\ScheduledTask;

use Mularski\ProductAlert\Service\ProductAlertService;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;

/**
 * Class AlertSenderHandler
 */
class AlertSenderHandler extends ScheduledTaskHandler
{
    /**
     * @var ProductAlertService
     */
    private $productAlertService;

    /**
     * AlertSenderHandler constructor.
     *
     * @param EntityRepositoryInterface $scheduledTaskRepository
     * @param ProductAlertService $productAlertService
     */
    public function __construct(
        EntityRepositoryInterface $scheduledTaskRepository,
        ProductAlertService $productAlertService
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
