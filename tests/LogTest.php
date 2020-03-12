<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Bnomei\Log;
use PHPUnit\Framework\TestCase;

final class LogTest extends TestCase
{
    public function testMonologLibExists()
    {
        $this->assertIsString(\Monolog\Logger::class);
    }

    public function testConstruct()
    {
        $log = new Log();

        $this->assertInstanceOf(Log::class, $log);
    }

    public function testSingleton()
    {
        // static instance does not exists
        $log = Bnomei\Log::singleton();
        $this->assertInstanceOf(Log::class, $log);

        // static instance now does exist
        $log = Bnomei\Log::singleton();
        $this->assertInstanceOf(Log::class, $log);
    }

    public function testChannel()
    {
        $log = new Log();

        $this->assertInstanceOf(\Monolog\Logger::class, $log->channel());
        $this->assertInstanceOf(\Monolog\Logger::class, $log->channel('default'));
    }

    public function testPageChannel()
    {
        $page = page('home');
        $channel = $page->monolog();

        $this->assertInstanceOf(\Monolog\Logger::class, $channel);

        $logfile = __DIR__ . '/site/logs/vl2sb4.log';
        @unlink($logfile);
        $channel->info('test');
        $this->assertFileExists($logfile);
    }
}
