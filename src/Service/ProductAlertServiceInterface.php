<?php
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\Service;

/**
 * Interface ProductAlertServiceInterface
 */
interface ProductAlertServiceInterface
{
    /**
     * @return int
     */
    public function process(): int;
}