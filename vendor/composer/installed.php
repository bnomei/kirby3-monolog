<?php return array(
    'root' => array(
        'name' => 'bnomei/kirby3-monolog',
        'pretty_version' => '2.0.4',
        'version' => '2.0.4.0',
        'reference' => NULL,
        'type' => 'kirby-plugin',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => false,
    ),
    'versions' => array(
        'bnomei/kirby3-monolog' => array(
            'pretty_version' => '2.0.4',
            'version' => '2.0.4.0',
            'reference' => NULL,
            'type' => 'kirby-plugin',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'getkirby/composer-installer' => array(
            'pretty_version' => '1.2.1',
            'version' => '1.2.1.0',
            'reference' => 'c98ece30bfba45be7ce457e1102d1b169d922f3d',
            'type' => 'composer-plugin',
            'install_path' => __DIR__ . '/../getkirby/composer-installer',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'monolog/monolog' => array(
            'pretty_version' => '2.2.0',
            'version' => '2.2.0.0',
            'reference' => '1cb1cde8e8dd0f70cc0fe51354a59acad9302084',
            'type' => 'library',
            'install_path' => __DIR__ . '/../monolog/monolog',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'psr/log' => array(
            'pretty_version' => '1.1.4',
            'version' => '1.1.4.0',
            'reference' => 'd49695b909c3b7628b6289db5479a1c204601f11',
            'type' => 'library',
            'install_path' => __DIR__ . '/../psr/log',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'psr/log-implementation' => array(
            'dev_requirement' => false,
            'provided' => array(
                0 => '1.0.0',
            ),
        ),
    ),
);
