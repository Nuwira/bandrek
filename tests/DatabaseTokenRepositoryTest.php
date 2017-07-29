<?php

namespace Nuwira\Bandrek\Test;

use Carbon\Carbon;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Connection;
use Mockery;
use Nuwira\Bandrek\Password\DatabaseTokenRepository;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Nuwira\Bandrek\BandrekContract;

class DatabaseTokenRepositoryTest extends TestCase
{
    public function setup()
    {
        parent::setup();

        Carbon::setTestNow(Carbon::now());
    }

    public function tearDown()
    {
        Carbon::setTestNow();

        Mockery::close();

        parent::tearDown();
    }
    
    protected $token = 'yL268y21K5qwrXQmA4BDOngbYpQDFQTBCdSaH6jMGeoZaWNPvL7JlzEVk3Rd0XMG';

    /**
     * @test
     */
    public function testCreateNewTokenReturnTokenString()
    {
        $repo = $this->getRepo();
        $repo->getBandrek()->shouldReceive('generateToken')->once()->andReturn($this->token);
        
        $token = $repo->createNewToken();

        $this->assertTrue(is_string($token));
        $this->assertSame(64, strlen($token));
        $this->assertTrue((bool) preg_match('/[a-zA-Z0-9]{64}/', $token));
    }

    /**
     * @test
     */
    public function testExistReturnsFalseIfNoRowFoundForUser()
    {
        $repo = $this->getRepo();
        $repo->getConnection()->shouldReceive('table')->once()->with('table')->andReturn($query = Mockery::mock('stdClass'));
        
        $query->shouldReceive('where')->once()->with('email', 'email')->andReturn($query);
        $query->shouldReceive('first')->andReturn(null);
        
        $user = Mockery::mock('Illuminate\Contracts\Auth\CanResetPassword');
        $user->shouldReceive('getEmailForPasswordReset')->andReturn('email');
        
        $this->assertFalse($repo->exists($user, 'token'));
    }
    
    /**
     * @test
     */
    public function testExistReturnsFalseIfRecordIsExpired()
    {
        $repo = $this->getRepo();
        $repo->getHasher()->shouldReceive('check')->with('token', 'hashed-token')->andReturn(true);
        $repo->getConnection()->shouldReceive('table')->once()->with('table')->andReturn($query = Mockery::mock('stdClass'));
        
        $date = Carbon::now()->subSeconds(300000)->toDateTimeString();
        
        $query->shouldReceive('where')->once()->with('email', 'email')->andReturn($query);
        $query->shouldReceive('first')->andReturn((object) ['created_at' => $date, 'token' => 'hashed-token']);
        
        $user = Mockery::mock('Illuminate\Contracts\Auth\CanResetPassword');
        $user->shouldReceive('getEmailForPasswordReset')->andReturn('email');
        
        $this->assertFalse($repo->exists($user, 'token'));
    }
    
    /**
     * @test
     */
    public function testExistReturnsFalseIfInvalidToken()
    {
        $repo = $this->getRepo();
        $repo->getHasher()->shouldReceive('check')->with('wrong-token', 'hashed-token')->andReturn(false);
        $repo->getConnection()->shouldReceive('table')->once()->with('table')->andReturn($query = Mockery::mock('stdClass'));
        
        $date = Carbon::now()->subMinutes(10)->toDateTimeString();
        
        $query->shouldReceive('where')->once()->with('email', 'email')->andReturn($query);
        $query->shouldReceive('first')->andReturn((object) ['created_at' => $date, 'token' => 'hashed-token']);
        
        $user = Mockery::mock('Illuminate\Contracts\Auth\CanResetPassword');
        $user->shouldReceive('getEmailForPasswordReset')->andReturn('email');
        
        $this->assertFalse($repo->exists($user, 'wrong-token'));
    }
    
    /**
     * @test
     */
    public function testExistReturnsTrueIfValidRecordExists()
    {
        $repo = $this->getRepo();
        $repo->getHasher()->shouldReceive('check')->with('token', 'hashed-token')->andReturn(true);
        $repo->getConnection()->shouldReceive('table')->once()->with('table')->andReturn($query = Mockery::mock('stdClass'));
        
        $date = Carbon::now()->subMinutes(10)->toDateTimeString();
        
        $query->shouldReceive('where')->once()->with('email', 'email')->andReturn($query);
        $query->shouldReceive('first')->andReturn((object) ['created_at' => $date, 'token' => 'hashed-token']);
        
        $user = Mockery::mock('Illuminate\Contracts\Auth\CanResetPassword');
        $user->shouldReceive('getEmailForPasswordReset')->andReturn('email');
        
        $this->assertTrue($repo->exists($user, 'token'));
    }
    
    /**
     * @test
     */
    public function testExistReturnsTrueIfValidRecordExistsUsingCode()
    {
        $repo = $this->getRepo();
        $repo->getBandrek()->shouldReceive('getTokenFromCode')->with('123456')->andReturn('token');
        $repo->getHasher()->shouldReceive('check')->with('token', 'hashed-token')->andReturn(true);
        $repo->getConnection()->shouldReceive('table')->once()->with('table')->andReturn($query = Mockery::mock('stdClass'));
        
        $date = Carbon::now()->subMinutes(10)->toDateTimeString();
        
        $query->shouldReceive('where')->once()->with('email', 'email')->andReturn($query);
        $query->shouldReceive('first')->andReturn((object) ['created_at' => $date, 'token' => 'hashed-token']);
        
        $user = Mockery::mock('Illuminate\Contracts\Auth\CanResetPassword');
        $user->shouldReceive('getEmailForPasswordReset')->andReturn('email');
        
        $this->assertTrue($repo->exists($user, '123456'));
    }
    
    protected function getRepo()
    {
        $database = new DatabaseTokenRepository(
            Mockery::mock('Illuminate\Database\Connection'),
            Mockery::mock('Illuminate\Contracts\Hashing\Hasher'),
            'table', 'key');
        
        return $database->setBandrek(
            Mockery::mock('Nuwira\Bandrek\BandrekContract', [
                'generateToken' => $this->token,
            ])
        );
    }
}
