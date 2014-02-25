<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\Test\View\Helper;

class AbstractHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $client;

    public function setUp()
    {
        $this->client = $this->getMock('Zend\Http\Client');
    }

    public function testCacheKeyIsInfluencedByParametersAndOptions()
    {
        $helper1 = new TestAsset\Helper($this->client);

        $this->assertNotEquals($helper1->generateCacheKey('somePage'), $helper1->generateCacheKey('someOtherPage')) ;

        $helper2 = new TestAsset\Helper($this->client);
        $helper2->setOptions(['client_params' => ['someParam' => 'somevalue']]);
        $this->assertNotEquals($helper1->generateCacheKey('samePage'), $helper2->generateCacheKey('samePage')) ;
    }

}
