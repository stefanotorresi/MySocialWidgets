<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    protected $cacheOptions = [
        'adapter' => [
            'name'   => 'filesystem',
            'options' => [
                'ttl' => 300,
                'dir_level' => 0,
                'namespace' => 'my-social-widgets',
                'cache_dir' => './data/cache',
                'dir_permission' => 0777,
                'file_permission' => '0666',
            ],
        ],
        'plugins' => [ 'serializer' ],
    ];

    protected $registeredHelpers = [
        'FacebookPageEvents',
        'InstagramGallery',
    ];

    protected $clients = [
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
    ];

    /**
     * @param array $cache
     */
    public function setCacheOptions($cache)
    {
        $this->cacheOptions = $cache;
    }

    /**
     * @return array
     */
    public function getCacheOptions()
    {
        return $this->cacheOptions;
    }

    /**
     * @param array $clients
     */
    public function setClients($clients)
    {
        $this->clients = $clients;
    }

    /**
     * @return array
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param array $registeredHelpers
     */
    public function setRegisteredHelpers($registeredHelpers)
    {
        $this->registeredHelpers = $registeredHelpers;
    }

    /**
     * @return array
     */
    public function getRegisteredHelpers()
    {
        return $this->registeredHelpers;
    }
}
