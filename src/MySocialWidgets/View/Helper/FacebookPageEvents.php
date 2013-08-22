<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\View\Helper;

use MySocialWidgets\Exception\ClientException;
use Zend\Stdlib\ArrayUtils;

class FacebookPageEvents extends AbstractSocialHelper
{
    protected $options = [
        'partial' => 'my-social-widgets/facebook-page-events',
        'params' => [
            'access_token' => null, // @todo make access_token retrievable from the module config
            'fields' => 'id,name,location,start_time,description,cover',
        ],
    ];

    public function __invoke($pageId = null, $options = [])
    {
        if ($pageId === null) {
            return $this;
        }

        if (!empty($options)) {
            $this->options = ArrayUtils::merge($this->options, $options);
        }

        $record = $this->getRecord($pageId);

        return $this->getView()->partial($this->options['partial'], ['record' => $record]);
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    // @todo move the whole http client logic in service layer
    protected function getRecord($pageId)
    {
        $cacheKey = md5(serialize([
            __CLASS__,
            $pageId,
            $this->options['params']
        ]));

        $responseContent = $this->cache->getItem($cacheKey);

        if (!$responseContent) {

            $apiUri = $this->client->getUri();
            $endpoint = $apiUri.$pageId.'/events';

            $this->client->setUri($endpoint)->setParameterGet($this->options['params']);

            $response = $this->client->send();
            $responseContent = json_decode($response->getBody());

            if (!$response->isSuccess()) {
                $message = $response->getReasonPhrase();
                if (isset($responseContent->error)) {
                    $message .= ', '.$responseContent->error->message;
                }
                throw new ClientException($message);
            }

            $this->cache->setItem($cacheKey, $responseContent);
        }

        return $responseContent;
    }
}
