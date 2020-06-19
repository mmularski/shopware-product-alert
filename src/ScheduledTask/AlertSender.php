<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

/**
 * Class AlertSender
 */
class AlertSender extends ScheduledTask
{
    /**
     * @return string
     */
    public static function getTaskName(): string
    {
        return 'divante.product_alert_sender';
    }

    /**
     * @return int
     */
    public static function getDefaultInterval(): int
    {
        return 86400;
    }
}
