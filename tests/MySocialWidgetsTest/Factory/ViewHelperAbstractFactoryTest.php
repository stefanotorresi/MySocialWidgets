<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgetsTest\Factory;

use MySocialWidgets\Factory\ClientAbstractFactory;
use MySocialWidgets\Factory\ViewHelperAbstractFactory;
use PHPUnit_Framework_TestCase;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;
use Zend\ModuleManager\Listener\DefaultListenerAggregate;
use Zend\ModuleManager\Listener\ListenerOptions;
use Zend\ModuleManager\ModuleManager;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;

/**
 * @property \Zend\ServiceManager\ServiceManager serviceManager
 * @property DefaultListenerAggregate defaultListener
 */
class ViewHelperAbstractFactoryTest extends PHPUnit_Framework_TestCase
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

        $sm->setService('MySocialWidgets\CacheAdapter', $this->getMock('\Zend\Cache\Storage\Adapter\Filesystem'));
        $sm->addAbstractFactory(new ClientAbstractFactory());

        $helperManager = new HelperPluginManager();
        $helperManager->setServiceLocator($sm);

        $this->helperManager = $helperManager;
    }

    public function testFactory()
    {
        $factory = new ViewHelperAbstractFactory();

        $this->helperManager->addAbstractFactory($factory);

        $this->assertTrue($factory->canCreateServiceWithName($this->helperManager, '', 'InstagramGallery'));

        $this->assertInstanceOf('MySocialWidgets\View\Helper\InstagramGallery', $this->helperManager->get('instagramGallery'));
    }
}
