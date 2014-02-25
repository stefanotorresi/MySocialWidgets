<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\Client;

use MySocialWidgets\Options\ModuleOptions;
use Zend\Http\Client;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractClientFactory implements AbstractFactoryInterface
{
    const CLIENT_NAMESPACE = 'MySocialWidgets\Client';

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
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('MySocialWidgets\Options\ModuleOptions');

        $registeredClients = $moduleOptions->getClients();

        if (strpos($requestedName, self::CLIENT_NAMESPACE) !== 0) {
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
     * @return Client
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('MySocialWidgets\Options\ModuleOptions');

        $registeredClients = $moduleOptions->getClients();

        $clientName = $this->getClientName($requestedName);
        $config = $registeredClients[$clientName];

        $client = new Client($config['url'], $config['options']);

        return $client;
    }

    /**
     * Get the client config key from the first word of the camelcase class name
     *
     * @param $requestedName
     * @return string
     */
    protected function getClientName($requestedName)
    {
        $className = substr($requestedName, strrpos($requestedName, '\\') + 1);
        $clientName = preg_split('/(?<=[a-z])(?=[A-Z])/', $className)[0];

        return strtolower($clientName);
    }
}
