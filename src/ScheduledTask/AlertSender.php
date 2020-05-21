<?php declare(strict_types=1);
/**
 * @package Mularski\ProductAlert
 * @author Marek Mularczyk <mmularczyk9@gmail.com>
 */

namespace Mularski\ProductAlert\ScheduledTask;

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
        return 'mularski.alert_sender';
    }

    /**
     * @return int
     */
    public static function getDefaultInterval(): int
    {
        //@ToDO Change to 86400
        return 5;//86400;
    }
}
