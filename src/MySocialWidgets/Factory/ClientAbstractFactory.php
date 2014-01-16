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

class ClientAbstractFactory implements AbstractFactoryInterface
{
    const CLIENT_NAMESPACE = 'MySocialWidgets\Client';

    /**
     * Determine if we can create a service with name
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $registeredClients = $serviceLocator->get('ModuleManager')->getModule('MySocialWidgets')->getOptions('clients');

        $namespace = substr($requestedName, 0, strrpos($requestedName, '\\'));

        if ($namespace !== self::CLIENT_NAMESPACE) {
            return false;
        }

        $clientName = $this->getClientName($requestedName);

        return isset($registeredClients[$clientName]['url'])
                && isset($registeredClients[$clientName]['options']);
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
        $registeredClients = $serviceLocator->get('ModuleManager')->getModule('MySocialWidgets')->getOptions('clients');

        $clientName = $this->getClientName($requestedName);
        $config = $registeredClients[$clientName];

        $client = new Client($config['url'], $config['options']);

        return $client;
    }

    /**
     * Get the client config key from the first word of the camelcase class name
     *
     * @param $requestedName
     * @return mixed
     */
    protected function getClientName($requestedName)
    {
        $className = substr($requestedName, strrpos($requestedName, '\\') + 1);
        $clientName = preg_split('/(?<=[a-z])(?=[A-Z])/', $className)[0];

        return strtolower($clientName);
    }
}
