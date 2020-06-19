<?php
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\Service\MailTemplate;

use Divante\ProductAlert\ProductAlert\ProductAlertEntity;
use Shopware\Core\Framework\Context;

/**
 * Interface MailSenderInterface
 */
interface MailSenderInterface
{
    /**
     * @param ProductAlertEntity $entity
     * @param Context $context
     *
     * @return void
     */
    public function sendProductAlertMail(ProductAlertEntity $entity, Context $context): void;
}