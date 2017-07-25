<?php

namespace Nuwira\Gembok\Test;

use Carbon\Carbon;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Connection;
use Mockery;
use Nuwira\Gembok\Password\DatabaseTokenRepository;
use PHPUnit\Framework\TestCase;

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

    public function testCreateNewToken()
    {
        $token = $this->getRepo()->createNewToken();

        $this->assertTrue(is_string($token));
        $this->assertSame(64, strlen($token));
        $this->assertTrue((bool) preg_match('/[a-zA-Z0-9]{64}/', $token));
    }

    protected function getRepo()
    {
        return new DatabaseTokenRepository(
            Mockery::mock(Connection::class),
            Mockery::mock(Hasher::class),
            'table', 'key');
    }
}
