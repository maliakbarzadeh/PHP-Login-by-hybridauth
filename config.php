<?php
/**
 * Build a configuration array to pass to `Hybridauth\Hybridauth`
 *
 * Set the Authorization callback URL to https://path/to/hybridauth/examples/example_07/callback.php
 * Understandably, you need to replace 'path/to/hybridauth' with the real path to this script.
 */
$config = [
    'callback' => 'http://localhost/Login/callback.php',
    'providers' => [

        'Google' => [
            'enabled' => true,
            'keys' => [
                'id' => '1030529171072-oh0sg5c0gms1jv1keu86ovoehsjo8rlq.apps.googleusercontent.com',
                'secret' => '',
            ],
        ],
		'Facebook' => [
            'enabled' => true,
            'keys' => [
                'id' => '252241653261886',
                'secret' => '',
            ],
        ],
		'Discord' => [
            'enabled' => true,
            'keys' => [
                'id' => '826166262831775804',
                'secret' => '',
            ],
        ],
		'Reddit' => [
            'enabled' => true,
            'keys' => [
                'id' => 'sAgHGSB-p4GnPw',
                'secret' => '',
            ],
        ],

        // 'Yahoo' => ['enabled' => true, 'keys' => ['key' => '...', 'secret' => '...']],
        // 'Facebook' => ['enabled' => true, 'keys' => ['id' => '...', 'secret' => '...']],
        // 'Twitter' => ['enabled' => true, 'keys' => ['key' => '...', 'secret' => '...']],
        // 'Instagram' => ['enabled' => true, 'keys' => ['id' => '...', 'secret' => '...']],

    ],
];
