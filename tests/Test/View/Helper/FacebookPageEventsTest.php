<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\Test\View\Helper;

use MySocialWidgets\View\Helper\FacebookPageEvents;
use ReflectionMethod;
use Zend\Http\Response;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;

class FacebookPageEventsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $client;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var FacebookPageEvents
     */
    protected $helper;

    public function setUp()
    {
        $this->client = $this->getMock('Zend\Http\Client');
        $this->response = new Response();

        $this->client->expects($this->any())
            ->method('send')
            ->will($this->returnValue($this->response));

        $this->helper = new FacebookPageEvents($this->client);

        $view = new PhpRenderer();
        $view->setResolver(new TemplateMapResolver([
            $this->helper->getOptions()->getPartial() => __DIR__ . '/_files/facebook-page-events.phtml'
        ]));

        $this->helper->setView($view);
    }

    public function testFluentInterface()
    {
        $helper = $this->helper;
        $this->assertInstanceOf('MySocialWidgets\View\Helper\FacebookPageEvents', $helper());
    }

    public function testOptionsPassedToInvokeAreMergedWithDefaults()
    {
        $oldPartial = $this->helper->getOptions()->getPartial();
        $newClientParams = ['access_token' => 'test'];

        $this->helper->__invoke('somePage', ['client_params' => $newClientParams]);

        $this->assertEquals($oldPartial, $this->helper->getOptions()->getPartial());
        $this->assertEquals($newClientParams['access_token'], $this->helper->getOptions()->getClientParams()['access_token']);
    }

    public function testInvoke()
    {
        $this->response->setContent(json_encode([
            'data' => [
                [
                    'name' => 'ZendCon'
                ],
                [
                    'name' => 'ZendCon EU'
                ],
            ]
        ]));

        $somePageId = 'ZendTechnologies';

        $this->client->expects($this->atLeastOnce())
            ->method('setUri')
            ->with($this->matchesRegularExpression("/$somePageId\\/events$/"));

        $output = $this->helper->__invoke($somePageId);
        $this->assertEquals('<div>ZendCon</div><div>ZendCon EU</div>', $output);
    }

    public function testUnsuccessfulResponseThrowsException()
    {
        $this->response->setStatusCode(401);
        $this->response->setContent(json_encode([
            'error' => [
                'message' => 'some error message'
            ]
        ]));

        $this->setExpectedException('MySocialWidgets\Client\ClientException', 'some error message');

        $this->helper->__invoke('some_facebook_page');
    }

    public function testCacheHit()
    {
        $cache = $this->getMock('Zend\Cache\Storage\Adapter\Memory');

        $data = json_decode("{ \"data\": [ { \"name\": \"some event name\" } ] }");

        $cache->expects($this->atLeastOnce())
              ->method('getItem')
              ->will($this->returnValue($data));

        $this->helper->setCache($cache);

        $somePageId = '1234';

        $this->client->expects($this->never())->method('send');

        $output = $this->helper->__invoke($somePageId);

        $this->assertEquals('<div>some event name</div>', $output);
    }

    public function testCacheIsUpdatedAfterSuccessFullRequest()
    {
        $cache = $this->getMock('Zend\Cache\Storage\Adapter\Memory');
        $this->helper->setCache($cache);

        $this->response->setContent(json_encode([
            'data' => [
                [
                    'name' => 'event'
                ],
            ]
        ]));

        $somePageId = '1234';

        $cache->expects($this->atLeastOnce())
              ->method('setItem')
              ->with($this->anything(), json_decode($this->response->getBody()));

        $this->helper->__invoke($somePageId);
    }
}
