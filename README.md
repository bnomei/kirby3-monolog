# Kirby Monolog

[![Kirby 5](https://flat.badgen.net/badge/Kirby/5?color=ECC748)](https://getkirby.com)
![PHP 8.2](https://flat.badgen.net/badge/PHP/8.2?color=4E5B93&icon=php&label)
![Release](https://flat.badgen.net/packagist/v/bnomei/kirby3-monolog?color=ae81ff&icon=github&label)
![Downloads](https://flat.badgen.net/packagist/dt/bnomei/kirby3-monolog?color=272822&icon=github&label)
[![Coverage](https://flat.badgen.net/codeclimate/coverage/bnomei/kirby3-monolog?icon=codeclimate&label)](https://codeclimate.com/github/bnomei/kirby3-monolog)
[![Maintainability](https://flat.badgen.net/codeclimate/maintainability/bnomei/kirby3-monolog?icon=codeclimate&label)](https://codeclimate.com/github/bnomei/kirby3-monolog/issues)
[![Discord](https://flat.badgen.net/badge/discord/bnomei?color=7289da&icon=discord&label)](https://discordapp.com/users/bnomei)
[![Buymecoffee](https://flat.badgen.net/badge/icon/donate?icon=buymeacoffee&color=FF813F&label)](https://www.buymeacoffee.com/bnomei)

Use Monolog to log data to files/databases/notifications/...

## Installation

- unzip [master.zip](https://github.com/bnomei/kirby3-monolog/archive/master.zip) as folder `site/plugins/kirby3-monolog` or
- `git submodule add https://github.com/bnomei/kirby3-monolog.git site/plugins/kirby3-monolog` or
- `composer require bnomei/kirby3-monolog`

## Quickstart

**site/templates/home.php**
```php
monolog()->info('test-' . md5((string) time()), [
    'title' => $page->title(), // field will be normalized
    'page' => $page->id(),
]);
```

**site/logs/2024-10-27.log**
```log
[2024-10-27 19:10:30] default.INFO: test-d4a22afc0f735f551748d17c959b3339 {"title":"Home","page":"home"} []
```

**Page-Method**
This plugin also registers a Page-Method which will use the UUID of the page as a `channel`. You can use it like this:

```php
$page->monolog()->info('test-' . md5((string) time()), [/*...*/]);
```

**site/logs/2024-10-27.log**
```log
[2024-10-27 19:10:30] {UUID_of_page}.INFO: test-d4a22afc0f735f551748d17c959b3339 {} []
```

## Setup

Use the [default channel](https://github.com/bnomei/kirby3-monolog/blob/master/index.php#L11) provided by this plugin or define your own *Channels*. Monolog comes bundled with a lot of handlers, formatters and processors.

- write to file or syslogs
- send mails
- post to Slack/Discord
- insert into local or remote databases
- format as JSON
- append file/class/method Introspection
- append a UUID
- append URI, post method and IP
- ... create your own

## Usecase

### Named Channel => Logger

```php
// write to channel 'default' which writes to file 
// defined at 'bnomei.monolog.file' callback 
$log = \Bnomei\Log::singleton()->channel('default');
// is same as
$log = monolog('default');
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
monolog()->info('Adding a new user', [
    'username' => $user->name(),
]);

// increment Field `visits` in current Page
$page = $page->increment('visits');
monolog()->info('Incrementing Field', [
    'page' => $page->id(),
    'visits' => $page->visits()->toInt(),
]);
```

## Default Channel

The [default channel](https://github.com/bnomei/kirby3-monolog/blob/master/index.php#L11) provided by this plugin writes file to the `site/logs` folder. It will be using the filename format `date('Y-m-d') . '.log'` and [normalizes the data](https://github.com/bnomei/kirby3-monolog/blob/master/classes/KirbyFormatter.php) to make logging Kirby Objects easier.

> [!NOTE]
> Without that normalization you would have to call `->value()` or cast as `string` on every Kirby Field before adding its value as context data.

## Custom Channel

**site/config/config.php**
```php
return [
    // other config settings ...
    // (optional) add custom channels
    'bnomei.monolog.channels' => [
        'security' => function() {
            $logger = new \Monolog\Logger('security');
            // add handlers, formatters, processors and then...
            return $logger; 
        }
    ],
];
```

## Custom Channel Extends

**site/config/config.php**
```php
return [
    // other config settings ...
    // (optional) add custom channels from other plugins
    'bnomei.monolog.channels.extends' => [
        'myplugin.name.channels', // array of channel definitions in your other option
    ],
];
```

## Dependencies

- [monolog/monolog](https://github.com/monolog/monolog)

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby3-monolog/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.
