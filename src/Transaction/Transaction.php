<?php namespace Pagarme\Transaction;

use Pagarme\Core\PagarmeRequest;

class Transaction extends TransactionCommon {

	public function charge()
	{
		$this->create();
	}

	public function capture($data = false)
	{
			$request = new PagarmeRequest(self::getUrl().'/'.$this->id . '/capture', 'POST');

			if(gettype($data) == 'array') {
				$request->setParameters($data);
			} else {
				if($data) {
					$request->setParameters(array('amount' => $data));
				}
			}

			$response = $request->run();
			$this->refresh($response);
	}

	public function refund($params = array())
	{
			$request = new PagarmeRequest(self::getUrl().'/'.$this->id . '/refund', 'POST');
			$request->setParameters($params);
			$response = $request->run();
			$this->refresh($response);
	}
}
