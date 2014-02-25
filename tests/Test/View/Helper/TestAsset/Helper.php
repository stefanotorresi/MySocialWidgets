<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\Test\View\Helper\TestAsset;

use MySocialWidgets\View\Helper\AbstractHelper;

class Helper extends AbstractHelper
{
    public function generateCacheKey($item)
    {
        return parent::generateCacheKey($item);
    }
}
