<?php

namespace Pagarme\Adapter;

use Pagarme\Exception\HttpException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;

class Adapter implements AdapterInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Params
     */
    protected $apiKey;

    /**
     * Adapter constructor.
     * @param $apiKey
     * @param ClientInterface|null $client
     */
    public function __construct($apiKey, ClientInterface $client = null)
    {
        $this->client = $client ?: new Client();
        $this->apiKey = $apiKey;
    }

    /**
     * @param $url
     * @return \GuzzleHttp\Psr7\Stream|\Psr\Http\Message\StreamInterface
     */
    public function get($url)
    {
        try {
            $this->response = $this->client->get($url, array(
                'query' => array(
                    'api_key' => $this->apiKey
                )
            ));
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }
        return $this->response->getBody();
    }

    /**
     * @param $url
     * @return \GuzzleHttp\Psr7\Stream|\Psr\Http\Message\StreamInterface
     */
    public function delete($url)
    {
        try {
            $this->response = $this->client->delete($url);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }
        return $this->response->getBody();
    }

    /**
     * @param $url
     * @param string $content
     * @return \GuzzleHttp\Psr7\Stream|\Psr\Http\Message\StreamInterface
     */
    public function put($url, $content = '')
    {
        $options = [];
        $options[is_array($content) ? 'json' : 'body'] = $content;
        try {
            $this->response = $this->client->put($url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }
        return $this->response->getBody();
    }

    /**
     * @param $url
     * @param string $content
     * @return \GuzzleHttp\Psr7\Stream|\Psr\Http\Message\StreamInterface
     */
    public function post($url, $content = '')
    {
        $options = [];
        $options[is_array($content) ? 'json' : 'body'] = $content;
        try {
            $this->response = $this->client->post($url, $options);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }
        return $this->response->getBody();
    }

    /**
     * @return array|void
     */
    public function getLatestResponseHeaders()
    {
        if (null === $this->response) {
            return;
        }
        return [
            'reset' => (int) (string) $this->response->getHeader('RateLimit-Reset'),
            'remaining' => (int) (string) $this->response->getHeader('RateLimit-Remaining'),
            'limit' => (int) (string) $this->response->getHeader('RateLimit-Limit'),
        ];
    }

    /**
     * @throws HttpException
     */
    protected function handleError()
    {
        $body = (string) $this->response->getBody();
        $code = (int) $this->response->getStatusCode();
        $content = json_decode($body);
        //throw new HttpException(isset($content->message) ? $content->message : 'Request not processed.', $code);
    }
}
