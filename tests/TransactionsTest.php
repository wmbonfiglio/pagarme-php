<?php

namespace Pagarme\Tests\Transactions;

use Pagarme\Pagarme;
use Pagarme\Adapter\Adapter;

class TransactionsTest extends \PHPUnit_Framework_TestCase
{
    protected $adapter;
    protected $pagarme;

    /**
     * TransactionsTest constructor.
     */
    public function __construct()
    {
        $this->pagarme = new Pagarme('ak_test_hFnW2y4Eg6ddTZQ0Mpa95TU5uWQXDr');
    }
    /**
     * Unit Tests - Create Transaction
     *
     * @group adapter
     */
    public function testCreateTransaction()
    {
        $transaction = $this->pagarme->transaction();
    }
}