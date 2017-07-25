<?php

namespace Nuwira\Gembok\Tests;

use GrahamCampbell\TestBench\AbstractPackageTestCase;
use Nuwira\Gembok\Gembok;

class GembokTest extends AbstractPackageTestCase
{
    public function setUp()
    {
        parent::setUp();
        
        $this->gembok = new Gembok();
    }
    
    /**
     * @test
     */
    public function testRandomNumberArrayReturnArray()
    {
        $result = $this->gembok->randomNumberArray();
        
        $this->assertTrue(is_array($result));
        $this->assertSame(6, count($result));
        
        $result = $this->gembok->randomNumberArray(3);
        
        $this->assertTrue(is_array($result));
        $this->assertSame(3, count($result));
        
        $result = $this->gembok->randomNumberArray(2);
        
        $this->assertTrue(is_array($result));
        $this->assertSame(6, count($result));
    }
    
    /**
     * @test
     */
    public function testGenerateToken()
    {
        $token = $this->gembok->generateToken();
        
        $this->assertTrue(is_string($token));
        $this->assertSame(40, strlen($token));
    }
    
    /**
     * @test
     */
    public function testGenerateTokenUsingSalt()
    {
        $token = (new Gembok('matriphe'))->generateToken();
        
        $this->assertTrue(is_string($token));
        $this->assertSame(40, strlen($token));
    }
    
    /**
     * @test
     */
    public function testGenerateTokenWithCustomLength()
    {
        $token = (new Gembok(null, 64))->generateToken();
        
        $this->assertTrue(is_string($token));
        $this->assertSame(64, strlen($token));
    }
    
    /**
     * @test
     */
    public function testGetCodeArrayFromToken()
    {
        $token = $this->gembok->generateToken();
        $code = $this->gembok->getCodeFromToken($token);
        
        $this->assertTrue(is_array($code));
        $this->assertSame(6, count($code));
    }
    
    /**
     * @test
     */
    public function testGetCodeStringFromToken()
    {
        $token = $this->gembok->generateToken();
        $code = $this->gembok->getCodeFromToken($token, false);
        
        $this->assertTrue(is_string($code));
        $this->assertSame(6, strlen($code));
    }
    
    /**
     * @test
     */
    public function testCodeAndStringMatched()
    {
        $token = 'yKPML2xndQw2NCYs8f4CEtQCQYv3Wvw1Vlp0ZY8J';
        $code = '741757';
        $this->assertSame($code, $this->gembok->getCodeFromToken($token, false));
        
        $token = 'E3R8O4yLKJw1Ac6fpFAtDu9t4D5RvbwXgPBpmz57';
        $code = '018565 ';
        $this->assertSame($code, $this->gembok->getCodeFromToken($token, false));
        
        $token = 'BNKbzkjQ7OqWaHJhAC0i4f3CMYDmEKeVlLX6yEZ3';
        $code = '927317';
        $this->assertSame($code, $this->gembok->getCodeFromToken($token, false));
    }

}