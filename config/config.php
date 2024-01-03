<?php

return [
    "default"  => "wx1",
    "config"   => [
        "wx1" => [
            'corpid'       => "foo",
            'secret'       => "foo",
            'private_keys' => [
                "v1" => "foo",
            ],
        ],
    ],
    'provider' => [
        'default'   => 'ext',
        'providers' => [
            'ext' => \Pkg6\WeWorkFinance\Provider\PHPExtProvider::class,
            'ffi' => \Pkg6\WeWorkFinance\Provider\FFIProvider::class,
        ],
    ],
];