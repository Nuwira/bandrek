<?php

namespace Nuwira\Gembok\Tests;

use Nuwira\Gembok\Gembok;
use PHPUnit\Framework\TestCase;

class GembokTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gembok = new Gembok();
    }

    /**
     * @test
     */
    public function testGetRandomCodeReturnString()
    {
        $code = $this->gembok->getRandomCode();
        $this->assertTrue(is_string($code));
        $this->assertSame(6, strlen($code));

        $code = $this->gembok->getRandomCode(2);
        $this->assertTrue(is_string($code));
        $this->assertSame(6, strlen($code));

        $code = $this->gembok->getRandomCode(3);
        $this->assertTrue(is_string($code));
        $this->assertSame(3, strlen($code));

        $code = $this->gembok->getRandomCode(12);
        $this->assertTrue(is_string($code));
        $this->assertSame(12, strlen($code));
    }

    /**
     * @test
     */
    public function testGetRandomCodeReturnArray()
    {
        $code = $this->gembok->getRandomCode(null, false);
        $this->assertTrue(is_array($code));
        $this->assertSame(6, count($code));

        $code = $this->gembok->getRandomCode(2, false);
        $this->assertTrue(is_array($code));
        $this->assertSame(6, count($code));

        $code = $this->gembok->getRandomCode(3, false);
        $this->assertTrue(is_array($code));
        $this->assertSame(3, count($code));

        $code = $this->gembok->getRandomCode(12, false);
        $this->assertTrue(is_array($code));
        $this->assertSame(12, count($code));
    }

    /**
     * @test
     */
    public function testGetTokenFromCode()
    {
        $codeArray = [1, 2, 3, 4, 5, 6];
        $codeString = '123456';
        $codeInteger = 123456;
        $token = 'laOJdM3Z2YG5DjybnlX1mNV7pw4vfJhWi5s0tOr0ozEKBR4xPO8g6QL9WkavA67X';

        $this->assertSame($token, $this->gembok->getTokenFromCode($codeArray));
        $this->assertSame($token, $this->gembok->getTokenFromCode($codeString));
        $this->assertSame($token, $this->gembok->getTokenFromCode($codeInteger));

        $codeArray = [0, 9, 8, 7, 6, 5, 4, 3, 2, 1];
        $codeString = '0987654321';
        $token = 'ENKVZO4Mx6yPovBQgz7lYqoJcBH3FWCNuKtLsRizhJr3pd0aGm1X8nRbLWJ25Ajk';

        $this->assertSame($token, $this->gembok->getTokenFromCode($codeArray));
        $this->assertSame($token, $this->gembok->getTokenFromCode($codeString));
    }

    /**
     * @test
     */
    public function testGetCodeFromToken()
    {
        $token = 'laOJdM3Z2YG5DjybnlX1mNV7pw4vfJhWi5s0tOr0ozEKBR4xPO8g6QL9WkavA67X';
        $codeArray = [1, 2, 3, 4, 5, 6];
        $codeString = '123456';

        $this->assertTrue(is_string($this->gembok->getCodeFromToken($token)));
        $this->assertSame($codeString, $this->gembok->getCodeFromToken($token));

        $this->assertTrue(is_array($this->gembok->getCodeFromToken($token, false)));
        $this->assertSame($codeArray, $this->gembok->getCodeFromToken($token, false));
    }

    /**
     * @test
     */
    public function testGenerateToken()
    {
        $this->assertTrue(is_string($this->gembok->generateToken()));
        $this->assertSame(64, strlen($this->gembok->generateToken()));
    }

    /**
     * @test
     */
    public function testArrayToString()
    {
        $array = [0, 1, 2, 3, 4, 5];
        $string = '012345';

        $this->assertTrue(is_string($this->gembok->arrayToString($array)));
        $this->assertSame($string, $this->gembok->arrayToString($array));
    }
}
