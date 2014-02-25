<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\Test\View\Helper;

use MySocialWidgets\Test\Bootstrap;
use MySocialWidgets\View\Helper\AbstractHelperFactory;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;

class AbstractHelperFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HelperPluginManager
     */
    protected $helperManager;

    /**
     * @var AbstractHelperFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->helperManager = Bootstrap::getServiceManager()->get('ViewHelperManager');
        $this->factory = new AbstractHelperFactory();
    }

    public function testFactory()
    {
        $this->helperManager->addAbstractFactory($this->factory);

        $this->assertTrue($this->factory->canCreateServiceWithName($this->helperManager, '', 'FacebookPageEvents'));

        $this->assertInstanceOf('MySocialWidgets\View\Helper\FacebookPageEvents', $this->helperManager->get('facebookPageEvents'));
    }
}
