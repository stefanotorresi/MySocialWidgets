<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\Options;

use Zend\Stdlib\AbstractOptions;

class HelperOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $partial;

    /**
     * @var array
     */
    protected $clientParams;

    /**
     * @return string
     */
    public function getPartial()
    {
        return $this->partial;
    }

    /**
     * @param string $partial
     */
    public function setPartial($partial)
    {
        $this->partial = $partial;
    }

    /**
     * @return array
     */
    public function getClientParams()
    {
        return $this->clientParams;
    }

    /**
     * @param array $clientParams
     */
    public function setClientParams($clientParams)
    {
        $this->clientParams = $clientParams;
    }
}
