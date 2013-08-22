<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgetsTest\View\Helper;

use MySocialWidgets\View\Helper\FacebookPageEvents;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;

/**
 * @property FacebookPageEvents helper
 * @property mixed client
 * @property mixed cache
 */
class FacebookPageEventsTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        // @todo mock the client
        $this->client = new \Zend\Http\Client('https://graph.facebook.com/', ['sslverifypeer' => false]);
        $this->cache = new \Zend\Cache\Storage\Adapter\Memory;
        $this->helper = new FacebookPageEvents($this->client, $this->cache);

        $view = new PhpRenderer();
        $view->setResolver(new TemplateMapResolver([
            $this->helper->getOptions()['partial'] => __DIR__ . '/_files/facebook-page-events.phtml'
        ]));

        $this->helper->setView($view);
    }

    public function testFluentInterface()
    {
        $helper = $this->helper;
        $this->assertInstanceOf('MySocialWidgets\View\Helper\FacebookPageEvents', $helper());
    }

    public function testInvoke()
    {
        // @todo store test access token somewhere else, use a mock client when no access token is provided available
        $output = $this->helper->__invoke('ZendTechnologies', ['params' => ['access_token' => '610378295660439|DbyrApf0Cf2JKwjv_tTy58XBINs']]);
        $this->assertRegExp('/(<div>.*?<\/div>)+/', $output);
    }

    /**
     * @expectedException \MySocialWidgets\Exception\ClientException
     */
    public function testWrongAccessTokenThrowsException()
    {
        $output = $this->helper->__invoke('ZendTechnologies', ['params' => ['access_token' => 'foo']]);
    }

    public function testCacheHit()
    {
        // @todo
    }
}
