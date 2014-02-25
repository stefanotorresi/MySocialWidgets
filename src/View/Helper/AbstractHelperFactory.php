<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\View\Helper;

use MySocialWidgets\Client\AbstractClientFactory;
use MySocialWidgets\Options\ModuleOptions;
use Zend\Cache\StorageFactory;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractHelperFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @param  string                  $name
     * @param  string                  $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $seviceManager = $serviceLocator instanceof AbstractPluginManager ?
            $serviceLocator->getServiceLocator() : $serviceLocator;

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $seviceManager->get('MySocialWidgets\Options\ModuleOptions');

        $registeredHelpers = $moduleOptions->getRegisteredHelpers();

        return in_array(strtolower($requestedName), array_map('strtolower', $registeredHelpers));
    }

    /**
     * Create service with name
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $seviceManager = $serviceLocator instanceof AbstractPluginManager ?
                            $serviceLocator->getServiceLocator() : $serviceLocator;

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $seviceManager->get('MySocialWidgets\Options\ModuleOptions');

        $cacheAdapter = StorageFactory::factory($moduleOptions->getCacheOptions());

        $fqcn = '\MySocialWidgets\View\Helper\\'. ucfirst($requestedName);

        $clientName = preg_split('/(?<=[a-z])(?=[A-Z])/', $requestedName)[0];

        $client = $seviceManager->get(sprintf('%s\%s', AbstractClientFactory::CLIENT_NAMESPACE, $clientName));

        $helper = new $fqcn($client, $cacheAdapter);

        return $helper;
    }
}
