<?php
/**
 * Created by PhpStorm.
 * User: jzgolinski
 * Date: 16.03.18
 * Time: 19:29
 */

namespace App\Tests\Utils;

use App\Utils\ReasonRepair;
use App\Utils\ReasonValidator;
use PHPUnit\Framework\TestCase;

class ReasonRepairTest extends TestCase
{
    /** @var ReasonRepair */
    private $object;

    public function setUp()
    {
        $this->object = new ReasonRepair(new ReasonValidator());
    }

    /**
     * @dataProvider reasonsProvider
     * @param string $corruptedReason
     * @param string $correctReason
     */
    public function testRepair(string $corruptedReason, string $correctReason)
    {
        $repairedReason = $this->object->repair($corruptedReason);
        $this->assertSame($correctReason, $repairedReason);
    }

    public function reasonsProvider()
    {
        return [
            '123' => ['123', '1,2,3'],
            '"123"' => ['"123"', '1,2,3'],
            '810' => ['810', '8,10'],
            '136' => ['136', '1,3,6'],
            '1/10' => ['1/10', '1,10'],
//            '111100000' => ['111100000', '8'],
        ];
    }
}
