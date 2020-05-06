<?php

namespace Fidum\VaporMetricsTile\Tests\Unit;

use Fidum\VaporMetricsTile\Tests\TestCase;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use GuzzleHttp\Client;
use Laravel\VaporCli\Helpers;

class VaporMetricsClientTest extends TestCase
{
    private VaporMetricsClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = VaporMetricsClient::make();
    }

    public function testMake()
    {
        $this->assertSame('default-secret', Helpers::config('token'));

        VaporMetricsClient::make('test-token');

        $this->assertSame('test-token', Helpers::config('token'));
    }

    public function testCacheMetricsRaw()
    {
        $this->assertSame([], $this->client->cacheMetricsRaw(1, '7d'));
    }

    public function testDatabaseMetricsRaw()
    {
        $this->assertSame([], $this->client->databaseMetricsRaw(1, '7d'));
    }

    public function testEnvironmentMetricsRaw()
    {
        $this->assertSame([], $this->client->environmentMetricsRaw(1, 'production', '7d'));
    }
}
