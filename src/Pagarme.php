<?php

namespace Pagarme;

use Pagarme\Adapter\Adapter;
use Pagarme\Adapter\AdapterInterface;
use Pagarme\Api\Transaction;

class Pagarme extends Adapter
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct($apiKey)
    {
        $this->adapter = new Adapter($apiKey);
    }

    /**
     * @return Transaction
     */
    public function transaction()
    {
        return new Transaction($this->adapter);
    }
}
