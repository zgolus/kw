<?php
/**
 * Created by PhpStorm.
 * User: jzgolinski
 * Date: 15.03.18
 * Time: 18:29
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Utils\ReasonRepair;
use App\Utils\ReasonValidator;

Class Write extends Command
{
    private $reasonRepair;
    private $reasonValidator;

    public function __construct(ReasonRepair $reasonRepair, ReasonValidator $reasonValidator)
    {
        parent::__construct();

        $this->reasonRepair = $reasonRepair;
        $this->reasonValidator = $reasonValidator;
    }

    protected function configure()
    {
        $this->setName('kw:retoure:write')
            ->addArgument('input', InputArgument::REQUIRED, 'Input file')
            ->addArgument('output', InputArgument::REQUIRED, 'Output file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputHandle = fopen($input->getArgument('input'), "r");
        $outputHandle = fopen($input->getArgument('output'), 'w');
        if ($inputHandle) {
            while (($line = fgets($inputHandle)) !== false) {
                $line = trim($line);
                list($retourId, $retoureReason) = explode(',', $line, 2);
                $originalReason = trim($retoureReason);

                $repairedReason = $this->reasonRepair->repair($originalReason);
                fwrite($outputHandle, sprintf("%s,%s\n", $retourId, $repairedReason));

            }
            fclose($inputHandle);
        }
        fclose($outputHandle);
        $output->writeln('Done.');
    }
}