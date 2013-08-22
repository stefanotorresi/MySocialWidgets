<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgetsTest\View\Helper;

use MySocialWidgets\View\Helper\FacebookPageEvents;
use Zend\View\Helper\Partial;
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
            FacebookPageEvents::DEFAULT_PARTIAL => __DIR__ . '/_files/facebook-page-events.phtml'
        ]));

        $partialHelper = new Partial();
        $view->getHelperPluginManager()->setService('renderPartial', $partialHelper);

        $partialHelper->setView($view);
        $this->helper->setView($view);
    }

    public function testHelper()
    {
        // @todo store test access token somewhere else, use a mock client when no access token is provided available
        $output = $this->helper->__invoke('ZendTechnologies', '610378295660439|DbyrApf0Cf2JKwjv_tTy58XBINs');
        $this->assertRegExp('/(<div>.*?<\/div>)+/', $output);
    }

    /**
     * @expectedException \MySocialWidgets\Exception\ClientException
     */
    public function testWrongAccessTokenThrowsException()
    {
        $output = $this->helper->__invoke('ZendTechnologies', 'foobar');
    }

    public function testCacheHit()
    {
        // @todo
    }
}
