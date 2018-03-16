<?php
/**
 * Created by PhpStorm.
 * User: jzgolinski
 * Date: 15.03.18
 * Time: 18:29
 */

namespace App\Command;

use Symfony\Component\Console\Command\Command;
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
        $this->setName('kw:retoure:write');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $handle = fopen('corrupt_reasons.csv', "r");
        $new = fopen('corrected_reasons.csv', 'w');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                list($retourId, $retourReason) = explode(',', $line, 2);
                $originalReason = trim($retourReason);

                $repairedReason = $this->reasonRepair->repair($originalReason);
                fwrite($new, sprintf("%s,%s\n", $retourId, $repairedReason));

            }
            fclose($handle);
        }
        fclose($new);
        $output->writeln('Done.');
    }
}