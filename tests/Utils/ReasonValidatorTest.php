<?php
/**
 * Created by PhpStorm.
 * User: jzgolinski
 * Date: 16.03.18
 * Time: 18:51
 */

namespace App\Tests\Utils;

use App\Utils\ReasonValidator;
use PHPUnit\Framework\TestCase;

class ReasonValidatorTest extends TestCase
{

    /** @var \App\Utils\ReasonValidator */
    private $object;

    protected function setUp()
    {
        $this->object = new ReasonValidator();
    }

    /**
     * @dataProvider reasonsProvider
     * @param string $reason
     * @param bool $expected
     */
    public function testIsValid(string $reason, bool $expected)
    {

        $this->assertSame($this->object->isValid($reason), $expected);
    }

    public function reasonsProvider()
    {
        return [
            ['1', true],
            ['x1', true],
            ['0', false],
            ['1,2,3,4,5,6,7,8,9,10,x1,x2,x3,x4', true],
            ['123', false],
            ['0,1', false],
        ];
    }
}
