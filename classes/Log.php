<?php

declare(strict_types=1);

namespace Bnomei;

use Kirby\Toolkit\A;
use Monolog\Logger;

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
                'default' => option('bnomei.monolog.default')(),
            ], option('bnomei.monolog.channels', [])),
            'channels-extends' => option('bnomei.monolog.channels-extends'),
        ];
        $this->options = array_merge($defaults, $options);

        foreach ($this->options['channels-extends'] as $extend) {
            if (!is_string($extend)) {
                continue;
            }
            // get and expand option closure
            $extend = option($extend, []);
            $extend = !is_string($extend) && is_callable($extend) ? $extend() : $extend;
            if (is_array($extend)) {
                foreach ($extend as $key => $value) {
                    $this->options['channels'][$key] = $value;
                }
            }
        }

        foreach ($this->options['channels'] as $key => $value) {
            $this->setChannel($key, $value);
        }
    }

    public function setChannel(string $name, $value): void
    {
        $this->channels[$name] = $value;
    }

    /**
     * @return Logger
     */
    public function loadChannel(string $name = 'default'): void
    {
        // resolve closures and update
        $value = A::get($this->channels, $name);
        if ($value && !is_string($value) && is_callable($value)) {
            $this->setChannel($name, $value());
        }
    }

    /**
     * @return Logger
     */
    public function channel(string $name = 'default'): ?Logger
    {
        $this->loadChannel($name);
        return A::get($this->channels, $name);
    }

    /** @var Log */
    private static $singleton;

    /**
     * @param array $options
     * @return Log
     */
    public static function singleton(array $options = [])
    {
        if (!self::$singleton) {
            self::$singleton = new self($options);
        }

        return self::$singleton;
    }
}
