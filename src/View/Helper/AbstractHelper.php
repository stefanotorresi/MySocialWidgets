<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\View\Helper;

use MySocialWidgets\Options\HelperOptions;
use Zend\Cache\Storage\Adapter\AbstractAdapter as CacheAdapter;
use Zend\Http\Client;
use Zend\View\Helper\AbstractHelper as ZendAbstractHelper;

abstract class AbstractHelper extends ZendAbstractHelper
{
    /**
     * @var CacheAdapter
     */
    protected $cache;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var HelperOptions
     */
    protected $defaultOptions;

    /**
     * @var HelperOptions
     */
    protected $options;

    /**
     * @todo move deps in a service layer
     *
     * @param Client       $client
     * @param CacheAdapter $cacheAdapter
     */
    public function __construct(Client $client, CacheAdapter $cacheAdapter = null)
    {
        $this->client = $client;
        $this->cache = $cacheAdapter;
        $this->setOptions($this->getDefaultOptions());
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param  Client $client
     * @return $this
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return HelperOptions
     */
    public function getDefaultOptions()
    {
        if (! $this->defaultOptions) {
            $this->defaultOptions = new HelperOptions();
        }

        return $this->defaultOptions;
    }

    /**
     * @param  mixed $defaultOptions
     * @return $this
     */
    public function setDefaultOptions($defaultOptions)
    {
        if (! $defaultOptions instanceof HelperOptions) {
            $defaultOptions = new HelperOptions($defaultOptions);
        }

        $this->defaultOptions = $defaultOptions;

        return $this;
    }

    /**
     * @return CacheAdapter
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param  CacheAdapter $cache
     * @return $this
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return HelperOptions
     */
    public function getOptions()
    {
        if (! $this->options) {
            $this->options = new HelperOptions();
        }

        return $this->options;
    }

    /**
     * @param $options
     * @return $this
     */
    public function setOptions($options)
    {
        if (! $options instanceof HelperOptions) {
            $options = new HelperOptions($options);
        }

        $this->options = $options;

        return $this;
    }

    /**
     * @param $item
     * @return mixed
     */
    protected function getResponseFromCache($item)
    {
        if (! $this->cache) {
            return;
        }

        return $this->getCache()->getItem($this->generateCacheKey($item));
    }

    /**
     * @param $item
     * @param $responseContent
     */
    protected function updateCache($item, $responseContent)
    {
        if (! $this->cache) {
            return;
        }

        $this->getCache()->setItem($this->generateCacheKey($item), $responseContent);
    }

    /**
     * @param $item
     * @return string
     */
    protected function generateCacheKey($item)
    {
        return __CLASS__.'.'.$item.'.'.md5(serialize($this->getOptions()->getClientParams()));
    }
}
