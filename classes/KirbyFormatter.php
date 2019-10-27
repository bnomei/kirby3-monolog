<?php

namespace Bnomei;

final class KirbyFormatter extends \Monolog\Formatter\LineFormatter
{
    protected function normalize($data, $depth = 0)
    {
        if ($data && is_a($data, \Kirby\Cms\Field::class)) {
            return $data->value();
        }

        return parent::normalize($data, $depth);
    }
}
