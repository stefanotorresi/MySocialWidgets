<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets;

use Zend\ModuleManager\Feature;
use Zend\Mvc\ModuleRouteListener;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;
use ZfcBase\Module\AbstractModule;

class Module extends AbstractModule implements
    Feature\ConfigProviderInterface
{
    public function getConfig()
    {
        $config = parent::getConfig();

        $configFiles = Glob::glob($this->getDir().'/config/*.config.php');

        foreach ($configFiles as $configFile) {
            $config = ArrayUtils::merge($config, include $configFile);
        }

        return $config;
    }

    public function getDir()
    {
        return __DIR__ . '/../..';
    }

    public function getNamespace()
    {
        return __NAMESPACE__;
    }
}
