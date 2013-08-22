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
     * @todo move the whole http client logic in a service layer
     *
     * @param Client          $client
     * @param AbstractAdapter $cacheAdapter
     */
    public function __construct(Client $client, AbstractAdapter $cacheAdapter = null)
    {
        $this->client = $client;
        $this->cache = $cacheAdapter;
    }
}
