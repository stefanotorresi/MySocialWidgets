<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\Factory;

use Zend\Http\Client;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ViewHelperAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $registeredHelpers = $serviceLocator->getServiceLocator()
            ->get('ModuleManager')
            ->getModule('MySocialWidgets')
            ->getOptions('registered_helpers');

        return in_array(strtolower($requestedName), array_map('strtolower', $registeredHelpers));
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $seviceManager = $serviceLocator->getServiceLocator();
        $cacheAdapter = $seviceManager->get('MySocialWidgets\CacheAdapter');
        $fqcn = '\MySocialWidgets\View\Helper\\'. $requestedName;

        $clientName = preg_split('/(?<=[a-z])(?=[A-Z])/', $requestedName)[0];

        $client = $seviceManager->get('MySocialWidgets\Client\\'.$clientName);

        $helper = new $fqcn($client, $cacheAdapter);

        return $helper;
    }
}
