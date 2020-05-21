<?php declare(strict_types=1);
/**
 * @package  shopware_dev
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SendAlertsCommand
 */
class SendAlertsCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'product:alert:send';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Foo');
    }
}