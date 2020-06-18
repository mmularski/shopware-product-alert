<?php declare(strict_types=1);
/**
 * @package Divante\ProductAlert
 * @author Marek Mularczyk <mmularczyk@divante.pl>
 * @copyright 2020 Divante Sp. z o.o.
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace Divante\ProductAlert\Command;

use Divante\ProductAlert\Service\ProductAlertService;
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
     * @var ProductAlertService
     */
    private $productAlertService;

    /**
     * SendAlertsCommand constructor.
     *
     * @param ProductAlertService $productAlertService
     * @param null $name
     */
    public function __construct(ProductAlertService $productAlertService)
    {
        parent::__construct();

        $this->productAlertService = $productAlertService;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $affected = $this->productAlertService->process();

        $output->writeln("<info>{$affected} notifications has been successfully sent!</info>");
    }
}
