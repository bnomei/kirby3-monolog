# Kirby 3 Monolog

![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-monolog?color=ae81ff)
![Stars](https://flat.badgen.net/packagist/ghs/bnomei/kirby3-monolog?color=272822)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-monolog?color=272822)
![Issues](https://flat.badgen.net/packagist/ghi/bnomei/kirby3-monolog?color=e6db74)
[![Build Status](https://flat.badgen.net/travis/bnomei/kirby3-monolog)](https://travis-ci.com/bnomei/kirby3-monolog)
[![Coverage Status](https://flat.badgen.net/coveralls/c/github/bnomei/kirby3-monolog)](https://coveralls.io/github/bnomei/kirby3-monolog) 
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-monolog)](https://codeclimate.com/github/bnomei/kirby3-monolog) 
[![Demo](https://flat.badgen.net/badge/website/examples?color=f92672)](https://kirby3-plugins.bnomei.com/monolog) 
[![Gitter](https://flat.badgen.net/badge/gitter/chat?color=982ab3)](https://gitter.im/bnomei-kirby-3-plugins/community) 
[![Twitter](https://flat.badgen.net/badge/twitter/bnomei?color=66d9ef)](https://twitter.com/bnomei)

Use Monolog to log data to files/databases/notifications/...


## Quickstart

**/site/templates/home.php**
```php
monolog()->info('test-' . md5((string) time()), [
    'title' => $page->title(), // field will be normalized
    'page' => $page->id(),
]);
```

> [2019-10-27 19:10:30] default.INFO: test-d4a22afc0f735f551748d17c959b3339 {"title":"Home","page":"home"} []

## Commercial Usage

This plugin is free but if you use it in a commercial project please consider to 
- [make a donation 🍻](https://www.paypal.me/bnomei/5) or
- [buy me ☕](https://buymeacoff.ee/bnomei) or
- [buy a Kirby license using this affiliate link](https://a.paddle.com/v2/click/1129/35731?link=1170)

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-monolog/archive/master.zip) as folder `site/plugins/kirby3-monolog` or
- `git submodule add https://github.com/bnomei/kirby3-monolog.git site/plugins/kirby3-monolog` or
- `composer require bnomei/kirby3-monolog`

## Similar Plugin

- [Log](https://github.com/bvdputte/kirby-log) is simpler and can just write to files

## Setup

Use the [default channel](https://github.com/bnomei/kirby3-monolog/blob/master/index.php#L11) provided by this plugin or define your own *Channels*. Monolog comes bundled with a lot of [handlers, formatters and processors](https://github.com/Seldaek/monolog/blob/master/doc/02-handlers-formatters-processors.md).

- write to file or syslogs
- send mails
- post to slack
- insert into local or remote databases
- format as JSON
- append file/class/method Introspection
- append a UUID
- append URI, post method and IP
- ... create your own

**site/config/config.php**
```php
return [
    // other config settings ...
    'bnomei.monolog.channels' => [
        'security' => function() {
            $logger = new \Monolog\Logger('security');
            // add handlers, formatters, processors and then...
            return $logger; 
        }
    ],
];
``` 

## Usecase

### Named Channel => Logger

```php
$log = \Bnomei\Log::singleton()->channel('default');
// or simply
$log = monolog();

// get a logger instance by channel by name
$securityLogger = monolog('security');
```

### Add records to the Logger

#### Message
```php
$log = monolog();
$log->warning('Foo');

// or with method chaining
monolog()->error('Bar');
```

#### Message and Context
```php
monolog('security')->info('Adding a new user', [
    'username' => $user->name(),
]);

// increment Field `visits` in current Page
$page->increment('visits');
monolog()->info('Incrementing Field', [
    'page' => $page->id(),
    'visits' => $page->visits()->toInt(),
]);
```

## Default Channel

The [default channel](https://github.com/bnomei/kirby3-monolog/blob/master/index.php#L11) provided by this plugin [writes file](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Handler/StreamHandler.php) to the `site/logs` folder using the filename format `date('Y-m-d') . '.log'` and [normalizes the data](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Formatter/NormalizerFormatter.php) to make logging Kirby Objects easier.

> HINT: Without normalization you would have to call `->toValue()` or cast as `string` on every Kirby Field before adding its value as context data.

## Dependencies

- [Seldaek/monolog](https://github.com/Seldaek/monolog)

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-monolog/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
