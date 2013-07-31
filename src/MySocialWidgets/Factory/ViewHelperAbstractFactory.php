<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\Factory;

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
        $registeredHelpers = $serviceLocator->get('ModuleManager')->getModule('MySocialWidgets')->getOptions('registered_helpers');

        return in_array($requestedName, $registeredHelpers);
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
        $cacheAdapter = $serviceLocator->get('MySocialWidgets\CacheAdapter');
        $fqcn = '\MySocialWidgets\View\Helper\\'. $requestedName;

        $helper = new $fqcn($cacheAdapter);

        return $helper;
    }
}
