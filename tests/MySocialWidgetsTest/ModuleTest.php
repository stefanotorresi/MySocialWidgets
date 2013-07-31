<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgetsTest\MySocialWidgetsTest;

use MySocialWidgets\Module;
use PHPUnit_Framework_TestCase;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;
use Zend\ModuleManager\Listener\DefaultListenerAggregate;
use Zend\ModuleManager\Listener\ListenerOptions;
use Zend\ModuleManager\ModuleManager;

/**
 * @property \Zend\ModuleManager\ModuleManager moduleManager
 * @property DefaultListenerAggregate defaultListener
 */
class ModuleTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->defaultListener = new DefaultListenerAggregate(
            new ListenerOptions(array(
                'module_paths'         => array(
                    '../src'
                ),
            ))
        );

        $em = new EventManager();
        $em->setSharedManager(new SharedEventManager());
        $mm = new ModuleManager(['MySocialWidgets'], $em);
        $mm->getEventManager()->attachAggregate($this->defaultListener);
        $mm->loadModules();

        $this->moduleManager = $mm;
    }

    public function testCanLoadModule()
    {
        $module = $this->moduleManager->getModule('MySocialWidgets');
        $this->assertInstanceOf('\MySocialWidgets\Module', $module);

        return $module;
    }

    /**
     * @depends testCanLoadModule
     * @param $module
     */
    public function testModuleHasOptions(Module $module)
    {
        $mergedConfig = $this->defaultListener->getConfigListener()->getMergedConfig();
        $module->setMergedConfig($mergedConfig);
        $options = $module->getOptions();
        $this->assertNotEmpty($options);
    }
}
