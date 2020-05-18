<?php declare(strict_types=1);
/**
 * @package Mularski\ProductAlert
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace Mularski\ProductAlert\ScheduledTask;

use Mularski\ProductAlert\ProductAlert\ProductAlertEntityDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\RangeFilter;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;

/**
 * Class AlertSenderHandler
 */
class AlertSenderHandler extends ScheduledTaskHandler
{
    /**
     * @var EntityRepositoryInterface
     */
    private $productAlertRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $productRepository;

    /**
     * AlertSenderHandler constructor.
     *
     * @param EntityRepositoryInterface $scheduledTaskRepository
     * @param EntityRepositoryInterface $productAlertRepository
     * @param EntityRepositoryInterface $productRepository
     */
    public function __construct(
        EntityRepositoryInterface $scheduledTaskRepository,
        EntityRepositoryInterface $productAlertRepository,
        EntityRepositoryInterface $productRepository
    ) {
        parent::__construct($scheduledTaskRepository);

        $this->productAlertRepository = $productAlertRepository;
        $this->productRepository = $productRepository;
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
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter(ProductAlertEntityDefinition::FIELD_IS_SENT, 0));

        $rowsToHandle = $this->productAlertRepository
            ->search($criteria, Context::createDefaultContext())
            ->getElements();

        var_dump($rowsToHandle);
    }

    /**
     * @param array $productIds
     *
     * @return array
     */
    private function getAffectedOutOfStockProduct(array $productIds): array
    {
        $criteria = new Criteria();
        //@ToDo Change statemant
        $criteria->addFilter(new RangeFilter('available_stock', [RangeFilter::GTE => 0]));
        $criteria->addFilter(new EqualsAnyFilter('id', $productIds));

        return $this->productRepository->search($criteria, Context::createDefaultContext())->getElements();
    }
}