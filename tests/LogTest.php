<?php

require_once __DIR__.'/../vendor/autoload.php';

use Bnomei\Log;
use Monolog\Logger;

test('monolog lib exists', function () {
    expect(Logger::class)->toBeString();
});

test('construct', function () {
    $log = new Log;

    expect($log)->toBeInstanceOf(Log::class);
});

test('singleton', function () {
    // static instance does not exists
    $log = Bnomei\Log::singleton();
    expect($log)->toBeInstanceOf(Log::class);

    // static instance now does exist
    $log = Bnomei\Log::singleton();
    expect($log)->toBeInstanceOf(Log::class);
});

test('channel', function () {
    $log = new Log;

    expect($log->channel())->toBeInstanceOf(Logger::class)
        ->and($log->channel('default'))->toBeInstanceOf(Logger::class);
});

test('page channel', function () {
    $page = page('home');
    $channel = $page->monolog();

    expect($channel)->toBeInstanceOf(Logger::class);

    $dir = option('bnomei.monolog.dir')(); // @phpstan-ignore-line
    $filename = option('bnomei.monolog.filename')(
        $page->monologChannel()
    ); // @phpstan-ignore-line
    $logfile = $dir.'/'.$filename;

    // make sure its gone
    @unlink($logfile);

    // this will create the file
    $channel->info('test', [
        'title' => $page->title(),
        'page' => $page->id(),
    ]);

    expect($logfile)->toBeFile()
        // write more to the file
        ->and($channel->error('a'))->toBeNull()
        ->and($channel->info('b'))->toBeNull();
});

test('default channel', function () {
    expect(monolog()->info('hello'))->toBeNull();
});

test('custom channel', function () {
    $channel = monolog('swatch');

    expect($channel->info('world'))->toBeNull();

    $logfile = __DIR__.'/site/logs/swatch-'.date('B').'.log';
    expect($logfile)->toBeFile();
});

test('dynamic channel', function () {
    $channel = monolog('dynamic');

    expect($channel->info('hello'))->toBeNull();

    $logfile = __DIR__.'/site/logs/'.date('Y-m-d').'.log';
    expect($logfile)->toBeFile();
});
