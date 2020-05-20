<?php declare(strict_types=1);
/**
 * @package Mularski\ProductAlert
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace Mularski\ProductAlert\ScheduledTask;

use Mularski\ProductAlert\MailTemplate\Service\MailSender;
use Mularski\ProductAlert\ProductAlert\ProductAlertEntity;
use Mularski\ProductAlert\ProductAlert\ProductAlertEntityDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
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
     * @var MailSender
     */
    private $mailSender;

    /**
     * AlertSenderHandler constructor.
     *
     * @param EntityRepositoryInterface $scheduledTaskRepository
     * @param EntityRepositoryInterface $productAlertRepository
     * @param MailSender $mailSender
     */
    public function __construct(
        EntityRepositoryInterface $scheduledTaskRepository,
        EntityRepositoryInterface $productAlertRepository,
        MailSender $mailSender
    ) {
        parent::__construct($scheduledTaskRepository);

        $this->productAlertRepository = $productAlertRepository;
        $this->mailSender = $mailSender;
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
        $criteria->addFilter(new EqualsFilter(ProductAlertEntityDefinition::FIELD_IS_SENT, 0))
            ->addAssociation('salesChannel')
            ->addAssociation(ProductDefinition::ENTITY_NAME);

        $rowsToHandle = $this->productAlertRepository
            ->search($criteria, Context::createDefaultContext())
            ->getElements();

        /** @var ProductAlertEntity $row */
        foreach ($rowsToHandle as $row) {
            if ($row->getProduct()->getAvailableStock() > 0) {
                continue;
            }

            $this->mailSender->sendProductAlertMail($row, Context::createDefaultContext());
        }

        return;
    }
}
