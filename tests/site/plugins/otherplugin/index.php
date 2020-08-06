<?php

Kirby::plugin('other/plugin', [
    'options' => [
        'channels' => function () {
            return [
                'other' => function () {
                    $hash = 'other';
                    $file = option('bnomei.monolog.dir')() . '/' . $hash . '-' . date('Ymdhm') . '.log';
                    return option('bnomei.monolog.default')($hash, $file);
                },
            ];
        }
    ]
]);
