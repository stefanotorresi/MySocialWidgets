<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgetsTest\View\Helper;

use MySocialWidgets\View\Helper\InstagramGallery;

/**
 * @property InstagramGallery helper
 */
class InstagramGalleryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $cache = $this->getMock('\Zend\Cache\Storage\Adapter\Memory');
        $this->helper = new InstagramGallery($cache);
    }

    public function testHelper()
    {
        // @todo
    }
}
