<?php

    monolog()->info('test-' . md5((string) time()), [
        'title' => $page->title(), // field will be normalized
        'page' => $page->id(),
    ]);

    // [2019-10-27 19:10:30] default.INFO: test-d4a22afc0f735f551748d17c959b3339 {"title":"Home","page":"home"} []
