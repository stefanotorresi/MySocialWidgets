<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets;

return [
    __NAMESPACE__ => [
        'options' => [
            'cache' => [
                'adapter' => [
                    'name'   => 'filesystem',
                    'options' => [
                        'ttl' => 300,
                        'dir_level' => 0,
                        'namespace' => __NAMESPACE__,
                        'cache_dir' => './data/cache',
                        'dir_permission' => 0777,
                        'file_permission' => '0666',
                    ],
                ],
                'plugins' => [ 'Serializer' ],
            ],
        ],
        'registered_helpers' => [
            'FacebookPageEvents',
            'InstagramGallery',
        ],
        'clients' => [
            'facebook' => [
                'url' => 'https://graph.facebook.com/',
                'options' => [],
            ],
            'foursquare' =>  [
                'url' => 'https://api.foursquare.com/v2/',
                'options' => [],
            ],
            'instagram' =>  [
                'url' => 'https://api.instagram.com/v1/',
                'options' => [],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            __NAMESPACE__ . '\CacheAdapter' => __NAMESPACE__ . '\Factory\CacheAdapterFactory',
        ],
        'abstract_factories' => [
            __NAMESPACE__ . '\Factory\ClientAbstractFactory'
        ],
    ],

    'view_helpers' => [
        'abstract_factories' => [
            __NAMESPACE__ . '\Factory\ViewHelperAbstractFactory'
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [ __DIR__ . '/../view'],
    ],
];
