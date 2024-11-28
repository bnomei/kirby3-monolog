<?php

namespace Bnomei;

use Kirby\Content\Field;
use Monolog\Formatter\LineFormatter;

class KirbyFormatter extends LineFormatter
{
    protected function normalize(mixed $data, int $depth = 0): mixed
    {
        if ($data instanceof Field) {
            return $data->value(); // @phpstan-ignore-line
        }

        return parent::normalize($data, $depth);
    }
}
