<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets;

return [
    __NAMESPACE__ => [
//        'cache_options' => [
//            'adapter' => [
//                'name'   => 'filesystem',
//                'options' => [
//                    'ttl' => 300,
//                    'dir_level' => 0,
//                    'namespace' => __NAMESPACE__,
//                    'cache_dir' => './data/cache',
//                    'dir_permission' => 0777,
//                    'file_permission' => '0666',
//                ],
//            ],
//            'plugins' => [ 'Serializer' ],
//        ],
//        'registered_helpers' => [
//            'FacebookPageEvents',
//            'InstagramGallery',
//        ],
//        'clients' => [
//            'facebook' => [
//                'url' => 'https://graph.facebook.com/',
//                'options' => [],
//            ],
//            'foursquare' =>  [
//                'url' => 'https://api.foursquare.com/v2/',
//                'options' => [],
//            ],
//            'instagram' =>  [
//                'url' => 'https://api.instagram.com/v1/',
//                'options' => [],
//            ],
//        ],
    ],

    'service_manager' => [
        'factories' => [
            'MySocialWidgets\Options\ModuleOptions' => 'MySocialWidgets\Options\ModuleOptionsFactory',
        ],
        'abstract_factories' => [
            'MySocialWidgets\Client\AbstractClientFactory'
        ],
    ],

    'view_helpers' => [
        'abstract_factories' => [
            'MySocialWidgets\View\Helper\AbstractHelperFactory'
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [ __DIR__ . '/../view'],
    ],
];
