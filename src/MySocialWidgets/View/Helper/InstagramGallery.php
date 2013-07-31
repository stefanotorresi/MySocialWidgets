<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\View\Helper;

use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\View\Helper\AbstractHelper;

class InstagramGallery extends AbstractHelper
{
    /**
     * @var AbstractAdapter
     */
    protected $cache;

    /**
     * @param AbstractAdapter $cacheAdapter
     */
    public function __construct(AbstractAdapter $cacheAdapter)
    {
        $this->cache = $cacheAdapter;
    }

    public function __invoke()
    {

    }
}
