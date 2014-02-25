<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\Test\Client;

use MySocialWidgets\Client\AbstractClientFactory;
use MySocialWidgets\Test\Bootstrap;
use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;

class AbstractClientFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var AbstractClientFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->factory = new AbstractClientFactory();
    }

    public function testFactory()
    {
        $this->serviceManager->addAbstractFactory($this->factory);

        $this->assertTrue($this->factory->canCreateServiceWithName($this->serviceManager, '', 'MySocialWidgets\Client\Facebook'));
        $this->assertTrue($this->factory->canCreateServiceWithName($this->serviceManager, '', 'MySocialWidgets\Client\Foursquare'));
        $this->assertTrue($this->factory->canCreateServiceWithName($this->serviceManager, '', 'MySocialWidgets\Client\Instagram'));

        $this->assertInstanceOf('Zend\Http\Client', $this->serviceManager->get('MySocialWidgets\Client\Facebook'));
        $this->assertInstanceOf('Zend\Http\Client', $this->serviceManager->get('MySocialWidgets\Client\Foursquare'));
        $this->assertInstanceOf('Zend\Http\Client', $this->serviceManager->get('MySocialWidgets\Client\Instagram'));
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testFailToCreateUnsupportedService()
    {
        $this->serviceManager->addAbstractFactory($this->factory);

        $client = $this->serviceManager->get('MySocialWidgets\Client\ASocialNetworkYetToBeInvented');
    }

    /**
     * @expectedException \Zend\ServiceManager\Exception\ServiceNotFoundException
     */
    public function testFailToCreateServiceWithWrongNamespace()
    {
        $this->serviceManager->addAbstractFactory($this->factory);

        $client = $this->serviceManager->get('AnotherNamespace\Client\Facebook');
    }
}
