<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\View\Helper;

use MySocialWidgets\Client\ClientException;
use Zend\Cache\Storage\Adapter\AbstractAdapter as CacheAdapter;
use Zend\Http\Client;
use Zend\Stdlib\ArrayUtils;

class FacebookPageEvents extends AbstractHelper
{
    /**
     * @var string
     */
    protected $cacheKey;

    /**
     * @param Client       $client
     * @param CacheAdapter $cacheAdapter
     */
    public function __construct(Client $client, CacheAdapter $cacheAdapter = null)
    {
        $this->setDefaultOptions([
            'partial' => 'my-social-widgets/facebook-page-events',
            'client_params' => [
                'access_token' => null, // @todo make access_token retrievable from the module config
                'fields' => 'id,name,location,start_time,description,cover',
            ],
        ]);

        parent::__construct($client, $cacheAdapter);
    }

    /**
     * @param  string $pageId
     * @param  array  $options
     * @return $this
     */
    public function __invoke($pageId = null, $options = [])
    {
        if ($pageId === null) {
            return $this;
        }

        if (! empty($options)) {
            $newOptions = ArrayUtils::merge($this->getDefaultOptions()->toArray(), $options);
            $this->setOptions($newOptions);
        } else {
            $this->setOptions($this->getDefaultOptions());
        }

        $record = $this->fetch($pageId);
        $markup = $this->getView()->partial($this->getOptions()->getPartial(), ['record' => $record]);

        return $markup;
    }

    /**
     * @todo move the fetch process in a separate layer
     * @param $pageId
     * @return mixed
     * @throws ClientException
     */
    protected function fetch($pageId)
    {
        if ($responseContent = $this->getResponseFromCache($pageId)) {
            return $responseContent;
        }

        $client = $this->getClient();

        $apiUri = $client->getUri();
        $endpoint = $apiUri.$pageId.'/events';

        $client->setUri($endpoint);
        $client->setParameterGet($this->getOptions()->getClientParams());

        $response = $client->send();
        $responseContent = json_decode($response->getBody());

        if (! $response->isSuccess()) {
            $message = $response->getReasonPhrase();
            if (isset($responseContent->error)) {
                $message .= ', '.$responseContent->error->message;
            }
            throw new ClientException($message);
        }

        if (! $responseContent) {
            $responseContent = new \stdClass();
            $responseContent->data = [];
        }

        $this->updateCache($pageId, $responseContent);

        return $responseContent;
    }
}
