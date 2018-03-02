<?php

namespace Nuwira\Bandrek\Tests;

use Nuwira\Bandrek\Bandrek;
use PHPUnit\Framework\TestCase;

class BandrekTest extends TestCase
{
    protected $bandrek;

    public function setUp()
    {
        parent::setUp();

        $this->bandrek = new Bandrek();
    }

    /**
     * @test
     */
    public function testGetRandomCodeReturnString()
    {
        $code = $this->bandrek->getRandomCode();
        $this->assertTrue(is_string($code));
        $this->assertSame(6, strlen($code));

        $code = $this->bandrek->getRandomCode(2);
        $this->assertTrue(is_string($code));
        $this->assertSame(6, strlen($code));

        $code = $this->bandrek->getRandomCode(3);
        $this->assertTrue(is_string($code));
        $this->assertSame(3, strlen($code));

        $code = $this->bandrek->getRandomCode(12);
        $this->assertTrue(is_string($code));
        $this->assertSame(12, strlen($code));
    }

    /**
     * @test
     */
    public function testGetRandomCodeReturnArray()
    {
        $code = $this->bandrek->getRandomCode(null, false);
        $this->assertTrue(is_array($code));
        $this->assertSame(6, count($code));

        $code = $this->bandrek->getRandomCode(2, false);
        $this->assertTrue(is_array($code));
        $this->assertSame(6, count($code));

        $code = $this->bandrek->getRandomCode(3, false);
        $this->assertTrue(is_array($code));
        $this->assertSame(3, count($code));

        $code = $this->bandrek->getRandomCode(12, false);
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

        $this->assertSame($token, $this->bandrek->getTokenFromCode($codeArray));
        $this->assertSame($token, $this->bandrek->getTokenFromCode($codeString));
        $this->assertSame($token, $this->bandrek->getTokenFromCode($codeInteger));

        $codeArray = [0, 9, 8, 7, 6, 5, 4, 3, 2, 1];
        $codeString = '0987654321';
        $token = 'ENKVZO4Mx6yPovBQgz7lYqoJcBH3FWCNuKtLsRizhJr3pd0aGm1X8nRbLWJ25Ajk';

        $this->assertSame($token, $this->bandrek->getTokenFromCode($codeArray));
        $this->assertSame($token, $this->bandrek->getTokenFromCode($codeString));
    }

    /**
     * @test
     */
    public function testGetCodeFromToken()
    {
        $token = 'laOJdM3Z2YG5DjybnlX1mNV7pw4vfJhWi5s0tOr0ozEKBR4xPO8g6QL9WkavA67X';
        $codeArray = [1, 2, 3, 4, 5, 6];
        $codeString = '123456';

        $this->assertTrue(is_string($this->bandrek->getCodeFromToken($token)));
        $this->assertSame($codeString, $this->bandrek->getCodeFromToken($token));

        $this->assertTrue(is_array($this->bandrek->getCodeFromToken($token, false)));
        $this->assertSame($codeArray, $this->bandrek->getCodeFromToken($token, false));
    }

    /**
     * @test
     */
    public function testGenerateToken()
    {
        $this->assertTrue(is_string($this->bandrek->generateToken()));
        $this->assertSame(64, strlen($this->bandrek->generateToken()));
    }

    /**
     * @test
     */
    public function testArrayToString()
    {
        $array = [0, 1, 2, 3, 4, 5];
        $string = '012345';

        $this->assertTrue(is_string($this->bandrek->arrayToString($array)));
        $this->assertSame($string, $this->bandrek->arrayToString($array));
    }

    /**
     * @test
     */
    public function testGetInfoReturnArray()
    {
        $token = 'laOJdM3Z2YG5DjybnlX1mNV7pw4vfJhWi5s0tOr0ozEKBR4xPO8g6QL9WkavA67X';

        $this->assertTrue(is_array($this->bandrek->getInfo($token)));
        $this->assertEquals([
            'algo' => 0,
            'algoName' => 'bandrek',
            'options' => [
                'code' => 6,
                'token' => 64,
                'keys' => 62,
            ],
        ], $this->bandrek->getInfo($token));
    }

    /**
     * @test
     */
    public function testGetInfoWithCustomReturnArray()
    {
        $token = 'bdaepzgypdmbngqoabjenwdvpmedvznyakpwlqdmneojzykvbdmpxplcqtqfpulivhesbcotpxqeognwalyakzjbvgbjwopglqykolgzqmwjzkaelvkqolnv';

        $bandrek = new Bandrek('garam', 120, 'abcdefghijklmnopqrstuvwxyz');

        $this->assertTrue(is_array($bandrek->getInfo($token)));
        $this->assertEquals([
            'algo' => 0,
            'algoName' => 'bandrek',
            'options' => [
                'code' => 10,
                'token' => 120,
                'keys' => 26,
            ],
        ], $bandrek->getInfo($token));
    }
}
