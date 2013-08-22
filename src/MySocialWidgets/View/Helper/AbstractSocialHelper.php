<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\View\Helper;

use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\Http\Client;
use Zend\View\Helper\AbstractHelper;

abstract class AbstractSocialHelper extends AbstractHelper
{
    /**
     * @var AbstractAdapter
     */
    protected $cache;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param AbstractAdapter $cacheAdapter
     * @param Client          $client
     */
    public function __construct(AbstractAdapter $cacheAdapter, Client $client)
    {
        $this->cache = $cacheAdapter;
        $this->client = $client;
    }
}
