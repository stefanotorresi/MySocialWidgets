<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgetsTest\Factory;

use MySocialWidgets\Factory\ClientAbstractFactory;
use PHPUnit_Framework_TestCase;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;
use Zend\ModuleManager\Listener\DefaultListenerAggregate;
use Zend\ModuleManager\Listener\ListenerOptions;
use Zend\ModuleManager\ModuleManager;
use Zend\ServiceManager\ServiceManager;

/**
 * @property \Zend\ServiceManager\ServiceManager serviceManager
 * @property DefaultListenerAggregate defaultListener
 */
class ClientAbstractFactoryTest extends PHPUnit_Framework_TestCase
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

        $module = $mm->getModule('MySocialWidgets');
        $mergedConfig = $this->defaultListener->getConfigListener()->getMergedConfig();
        $module->setMergedConfig($mergedConfig);

        $sm = new ServiceManager();
        $sm->setService('ModuleManager', $mm);

        $this->serviceManager = $sm;
    }

    public function testFactory()
    {
        $factory = new ClientAbstractFactory();

        $this->serviceManager->addAbstractFactory($factory);

        $this->assertTrue($factory->canCreateServiceWithName($this->serviceManager, '', 'MySocialWidgets\Client\Facebook'));
        $this->assertTrue($factory->canCreateServiceWithName($this->serviceManager, '', 'MySocialWidgets\Client\Foursquare'));
        $this->assertTrue($factory->canCreateServiceWithName($this->serviceManager, '', 'MySocialWidgets\Client\Instagram'));

        $this->assertInstanceOf('Zend\Http\Client', $this->serviceManager->get('MySocialWidgets\Client\Facebook'));
        $this->assertInstanceOf('Zend\Http\Client', $this->serviceManager->get('MySocialWidgets\Client\Foursquare'));
        $this->assertInstanceOf('Zend\Http\Client', $this->serviceManager->get('MySocialWidgets\Client\Instagram'));
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testFailToCreateUnsupportedService()
    {
        $factory = new ClientAbstractFactory();

        $this->serviceManager->addAbstractFactory($factory);

        $client = $this->serviceManager->get('MySocialWidgets\Client\ASocialNetworkYetToBeInvented');
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testFailToCreateServiceWithWrongNamespace()
    {
        $factory = new ClientAbstractFactory();

        $this->serviceManager->addAbstractFactory($factory);

        $client = $this->serviceManager->get('AnotherNamespace\Client\Facebook');
    }
}
