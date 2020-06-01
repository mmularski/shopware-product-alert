<?php declare(strict_types=1);
/**
 * @package Mularski\ProductAlert
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace Mularski\ProductAlert\Service;

use Mularski\ProductAlert\Service\MailTemplate\MailSender;
use Mularski\ProductAlert\ProductAlert\ProductAlertEntity;
use Mularski\ProductAlert\ProductAlert\ProductAlertEntityDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

/**
 * Class ProductAlertService
 */
class ProductAlertService
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
     * ProductAlertService constructor.
     *
     * @param MailSender $mailSender
     * @param EntityRepositoryInterface $productAlertRepository
     */
    public function __construct(
        MailSender $mailSender,
        EntityRepositoryInterface $productAlertRepository
    ) {
        $this->mailSender = $mailSender;
        $this->productAlertRepository = $productAlertRepository;
    }

    /**
     * @return void
     */
    public function process(): void
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

            $this->productAlertRepository->update(
                [
                    [
                        ProductAlertEntityDefinition::FIELD_ID => $row->getId(),
                        ProductAlertEntityDefinition::FIELD_IS_SENT => true,
                    ],
                ],
                Context::createDefaultContext()
            );
        }

        return;
    }
}
