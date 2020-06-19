<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\Service\MailTemplate;

use Divante\ProductAlert\ProductAlert\ProductAlertEntity;
use Shopware\Core\Content\MailTemplate\MailTemplateEntity;
use Shopware\Core\Content\MailTemplate\Service\MailServiceInterface;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Validation\DataBag\DataBag;

/**
 * Class MailSender
 */
class MailSender implements MailSenderInterface
{
    /** @var string */
    public const EMAIL_TEMPLATE_TECHNICAL_NAME = 'product.stock.alert';

    /**
     * @var MailServiceInterface
     */
    private $mailService;

    /**
     * @var EntityRepositoryInterface
     */
    private $mailTemplateRepository;

    /**
     * MailSender constructor.
     *
     * @param MailServiceInterface $mailService
     * @param EntityRepositoryInterface $mailTemplateRepository
     */
    public function __construct(MailServiceInterface $mailService, EntityRepositoryInterface $mailTemplateRepository)
    {
        $this->mailService = $mailService;
        $this->mailTemplateRepository = $mailTemplateRepository;
    }

    /**
     * @param ProductAlertEntity $entity
     * @param Context $context
     *
     * @return void
     */
    public function sendProductAlertMail(ProductAlertEntity $entity, Context $context): void
    {
        $data = new DataBag();
        $mailTemplate = $this->getMailTemplate($context);

        $data->set('recipients', [$entity->getEmail() => $entity->getEmail()]);
        $data->set('senderName', $mailTemplate->getSenderName());
        $data->set('salesChannelId', $entity->getSalesChannel()->getId());
        $data->set('contentHtml', $mailTemplate->getContentHtml());
        $data->set('contentPlain', $mailTemplate->getContentPlain());
        $data->set('subject', $mailTemplate->getSubject());
        $data->set('templateId', $mailTemplate->getId());
        $data->set('mediaIds', []);

        $this->mailService->send(
            $data->all(),
            Context::createDefaultContext(),
            [
                'product' => $entity->getProduct(),
                'salesChannel' => $entity->getSalesChannel(),
                'email' => $entity->getEmail(),
                'shopName' => $entity->getSalesChannel()->getTranslation('name'),
            ]
        );
    }

    /**
     * @param Context $context
     *
     * @return MailTemplateEntity|null
     */
    private function getMailTemplate(Context $context): ?MailTemplateEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('mailTemplateType.technicalName', self::EMAIL_TEMPLATE_TECHNICAL_NAME));
        $criteria->setLimit(1);

        /** @var MailTemplateEntity|null $mailTemplate */
        $mailTemplate = $this->mailTemplateRepository->search($criteria, $context)->first();

        return $mailTemplate;
    }
}
