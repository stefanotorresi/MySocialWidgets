<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\Factory;

use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\Cache\StorageFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CacheAdapterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return AbstractAdapter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('ModuleManager')->getModule('MySocialWidgets')->getOption('cache');

        $cacheAdapter = StorageFactory::factory($config);

        return $cacheAdapter;
    }
}
