<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\Service;

use Divante\ProductAlert\Service\MailTemplate\MailSender;
use Divante\ProductAlert\ProductAlert\ProductAlertEntity;
use Divante\ProductAlert\ProductAlert\ProductAlertEntityDefinition;
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
     * @return int
     */
    public function process(): int
    {
        $affected = 0;

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter(ProductAlertEntityDefinition::FIELD_IS_SENT, 0))
            ->addAssociation('salesChannel')
            ->addAssociation(ProductDefinition::ENTITY_NAME);

        $rowsToHandle = $this->productAlertRepository
            ->search($criteria, Context::createDefaultContext())
            ->getElements();

        /** @var ProductAlertEntity $row */
        foreach ($rowsToHandle as $row) {
            if ($row->getProduct()->getAvailableStock() <= 0) {
                continue;
            }

            $this->mailSender->sendProductAlertMail($row, Context::createDefaultContext());

            $this->productAlertRepository->delete(
                [
                    [ProductAlertEntityDefinition::FIELD_ID => $row->getId()],
                ],
                Context::createDefaultContext()
            );

            $affected++;
        }

        return $affected;
    }
}
