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
                        'ttl'       => 300,
                        'namespace'       => __NAMESPACE__,
                        'cache_dir'       => './data/cache',
                        'dir_permission'  => 0777,
                        'file_permission' => '0666',
                    ],
                ],
            ],
        ],
        'registered_helpers' => [
            'InstagramGallery'
        ],
    ],

    'service_manager' => [
        'factoris' => [
            __NAMESPACE__ . '\CacheAdapter' => __NAMESPACE__ . '\Factory\CacheAdapterFactory',
        ],
    ],

    'view_helpers' => [
        'abstract_factories' => [
            __NAMESPACE__ . '\Factory\ViewHelperAbstractFactory'
        ],
    ],
];
