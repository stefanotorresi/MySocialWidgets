<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MySocialWidgets\View\Helper;

use MySocialWidgets\Exception\ClientException;

class FacebookPageEvents extends AbstractSocialHelper
{
    const DEFAULT_PARTIAL = 'my-social-widgets/facebook-page-events';

    public function __invoke($pageId, $accessToken, $partial = null)
    {
        $this->client->setParameterGet(['access_token' => $accessToken]);

        $record = $this->getRecord($pageId);

        if (!$partial) {
            $partial = static::DEFAULT_PARTIAL;
        }

        return $this->getView()->partial($partial, ['record' => $record]);
    }

    // @todo move the whole http client logic in service layer
    protected function getRecord($pageId)
    {
        $cacheKey = crc32(__CLASS__.$pageId);

        $responseContent = $this->cache->getItem($cacheKey);

        if (!$responseContent) {
            $endpoint = $pageId.'/events';
            $this->client->setUri($this->client->getUri().$endpoint);

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
