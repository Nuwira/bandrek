<?php

namespace Nuwira\Bandrek\Tests;

use Nuwira\Bandrek\Bandrek;
use Nuwira\Bandrek\Hasher;
use PHPUnit\Framework\TestCase;

class HasherTest extends TestCase
{
    protected $hasher;

    public function setUp()
    {
        $this->hasher = new Hasher(new Bandrek());
    }

    /**
     * @test
     * */
    public function testInfoReturnArray()
    {
        $token = 'laOJdM3Z2YG5DjybnlX1mNV7pw4vfJhWi5s0tOr0ozEKBR4xPO8g6QL9WkavA67X';
        $info = $this->hasher->info($token);

        $this->assertTrue(is_array($info));
        $this->assertEquals([
            'algo' => 0,
            'algoName' => 'bandrek',
            'options' => [
                'code' => 6,
                'token' => 64,
                'keys' => 62,
            ],
        ], $this->hasher->info($token));
    }

    /**
     * @test
     * */
    public function testMakeReturnString()
    {
        $code = 123456;

        $token = $this->hasher->make($code);

        $this->assertTrue(is_string($token));
        $this->assertEquals(
            'laOJdM3Z2YG5DjybnlX1mNV7pw4vfJhWi5s0tOr0ozEKBR4xPO8g6QL9WkavA67X', $token);
    }

    /**
     * @test
     * */
    public function testCheckReturnBoolean()
    {
        $checked = $this->hasher->check(
            123456,
            'laOJdM3Z2YG5DjybnlX1mNV7pw4vfJhWi5s0tOr0ozEKBR4xPO8g6QL9WkavA67X'
        );

        $this->assertTrue(is_bool($checked));
        $this->assertTrue($checked);
    }

    /**
     * @test
     * */
    public function testNeedsRehashReturnString()
    {
        $code = 123456;
        $token = 'laOJdM3Z2YG5DjybnlX1mNV7pw4vfJhWi5s0tOr0ozEKBR4xPO8g6QL9WkavA67X';

        $newToken = $this->hasher->needsRehash($token);

        $this->assertTrue(is_string($newToken));
        $this->assertSame($token, $newToken);
    }
}
