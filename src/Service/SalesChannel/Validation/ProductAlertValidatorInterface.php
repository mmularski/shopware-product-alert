<?php
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\Service\SalesChannel\Validation;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Validation\DataBag\DataBag;

/**
 * Interface ProductAlertValidatorInterface
 */
interface ProductAlertValidatorInterface
{
    /**
     * @param DataBag $data
     * @param Context $context
     *
     * @return void
     */
    public function validate(DataBag $data, Context $context): void;
}
