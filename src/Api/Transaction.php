<?php

namespace Pagarme\Api;

class Transaction extends AbstractApi
{
    /**
     * @return mixed
     */
    public function getAll()
    {
        $transactions = $this->adapter->get(sprintf('%s/transactions', $this->endpoint, 200));
        $transactions = json_decode($transactions);

        var_dump($transactions); die;


//        $this->extractMeta($droplets);
//        return array_map(function ($droplet) {
//            return new DropletEntity($droplet);
//        }, $droplets->droplets);
    }
}