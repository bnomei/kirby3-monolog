<?php

Kirby::plugin('swatch/timer', [
    'options' => [
        'channels' => function () {
            return [
                'swatch' => function () {
                    $channelName = 'swatch';
                    $file = option('bnomei.monolog.dir')().'/swatch-'.date('B').'.log';

                    return option('bnomei.monolog.new-file-logger')($channelName, $file);
                },
            ];
        },
    ],
]);
