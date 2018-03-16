<?php
/**
 * Created by PhpStorm.
 * User: jzgolinski
 * Date: 15.03.18
 * Time: 18:29
 */

namespace App\Command;

use App\Utils\ReasonRepair;
use App\Utils\ReasonValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

Class Load extends Command
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
        $this->setName('kw:retoure:load')
             ->addArgument('input', InputArgument::REQUIRED, 'Input file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $firstLineSkipped = false;
        $handle = fopen($input->getArgument('input'), "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (!$firstLineSkipped) {
                    $firstLineSkipped = true;
                    continue;
                }
                $line = trim($line);
                list($retourId, $retourReason) = explode(',', $line, 2);

                if (!$this->reasonValidator->isValid($retourReason)) {
                    $repairedReason = $this->reasonRepair->repair($retourReason);
                    $output->writeln(sprintf('%s => %s,%s', $line, $retourId, $repairedReason));
                }
            }
            fclose($handle);
        }
    }

}