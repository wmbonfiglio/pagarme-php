<?php

namespace Pagarme\Api;
use Pagarme\Adapter\AdapterInterface;

abstract class AbstractApi
{
    /**
     * @var string
     */
    const ENDPOINT = 'https://api.pagar.me/1';

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var Meta
     */
    protected $meta;

    /**
     * @param AdapterInterface $adapter
     * @param string|null      $endpoint
     */
    public function __construct(AdapterInterface $adapter, $endpoint = null)
    {
        $this->adapter = $adapter;
        $this->endpoint = $endpoint ?: static::ENDPOINT;
    }
}