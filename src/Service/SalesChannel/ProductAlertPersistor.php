<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\Service\SalesChannel;

use Divante\ProductAlert\Service\SalesChannel\Validation\Exception\AlreadySignedException;
use Divante\ProductAlert\Service\SalesChannel\Validation\ProductAlertValidator;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ProductAlertPersistor
 */
class ProductAlertPersistor
{
    /**
     * @var EntityRepositoryInterface
     */
    private $entityRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ProductAlertValidator
     */
    private $validator;

    /**
     * ProductAlertService constructor.
     *
     * @param EntityRepositoryInterface $entityRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param ProductAlertValidator $validator
     */
    public function __construct(
        EntityRepositoryInterface $entityRepository,
        EventDispatcherInterface $eventDispatcher,
        ProductAlertValidator $validator
    ) {
        $this->entityRepository = $entityRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->validator = $validator;
    }

    /**
     * @param DataBag $data
     * @param Context $context
     *
     * @return void
     *
     * @throws AlreadySignedException
     */
    public function insert(DataBag $data, Context $context): void
    {
        $this->validator->validate($data, $context);

        $entryData = [
            'email' => $data->get('email'),
            'productId' => $data->get('productId'),
            'salesChannelId' => $data->get('salesChannelId') ?? $context->getSource()->getSalesChannelId(),
        ];

        $this->entityRepository->upsert([$entryData], $context);
    }
}
