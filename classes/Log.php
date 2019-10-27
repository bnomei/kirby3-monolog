<?php

declare(strict_types=1);

namespace Bnomei;

use Kirby\Toolkit\A;

final class Log
{
    /** @var array */
    private $channels;

    /** @var array */
    private $options;

    /**
     * Mailjet constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $defaults = [
            'debug' => option('debug'),
            'channels' => array_merge([
                'default' => option('bnomei.monolog.default'),
            ], option('bnomei.monolog.channels', [])),
        ];
        $this->options = array_merge($defaults, $options);

        foreach ($this->options['channels'] as $key => $value) {
            $this->channels[$key] = is_callable($value) ? $value() : $value;
        }
    }

    /**
     * @return \Monolog\Logger
     */
    public function channel(string $name = 'default'): ?\Monolog\Logger
    {
        return A::get($this->channels, $name);
    }

    /** @var \Bnomei\Log */
    private static $singleton;

    /**
     * @param array $options
     * @return \Bnomei\Log
     */
    public static function singleton(array $options = [])
    {
        if (! self::$singleton) {
            self::$singleton = new self($options);
        }

        return self::$singleton;
    }
}
