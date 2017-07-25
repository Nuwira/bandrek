<?php

namespace Nuwira\Gembok\Tests;

use Carbon\Carbon;
use GrahamCampbell\TestBench\AbstractPackageTestCase;
use Nuwira\Gembok\Gembok;

class GembokTest extends AbstractPackageTestCase
{
    public function setUp()
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2017-07-25 20:58:39'));

        $this->gembok = new Gembok();
    }

    public function tearDown()
    {
        Carbon::setTestNow();

        parent::tearDown();
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
        $token = '0yOg3K1B5bjME6RQ92ZkXVexNfEhxiKs8tLua1Jogzql7xnADLGaYovdm8J4WzpN';

        $this->assertSame($token, $this->gembok->getTokenFromCode($codeArray));
        $this->assertSame($token, $this->gembok->getTokenFromCode($codeString));
        $this->assertSame($token, $this->gembok->getTokenFromCode($codeInteger));

        $codeArray = [0, 9, 8, 7, 6, 5, 4, 3, 2, 1];
        $codeString = '0987654321';
        $token = 'PQpL50aGV1bBm76AYleb6cGHgF2C4uPtbsVinh0fNjX8kyeJjXD4yOnEzKR3k9ZN';

        $this->assertSame($token, $this->gembok->getTokenFromCode($codeArray));
        $this->assertSame($token, $this->gembok->getTokenFromCode($codeString));
    }

    /**
     * @test
     */
    public function testGetCodeFromToken()
    {
        $token = '0yOg3K1B5bjME6RQ92ZkXVexNfEhxiKs8tLua1Jogzql7xnADLGaYovdm8J4WzpN';
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
