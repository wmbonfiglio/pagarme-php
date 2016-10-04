<?php namespace Pagarme\Transaction;

use Pagarme\Core\PagarmeRequest;
use Pagarme\Core\PagarmeUtil;

class Subscription extends TransactionCommon {

	public function create() {
		if($this->plan) {
			$this->plan_id = $this->plan->id;
			unset($this->plan);
		}
		parent::create();
	}

	public function save() {
		if($this->plan) {
			$this->plan_id = $this->plan->id;
			unset($this->plan);
		}
		parent::save();
	}

	public function getTransactions() {
			$request = new PagarmeRequest(self::getUrl() . '/' . $this->id . '/transactions', 'GET');
			$response = $request->run();
			$this->transactions = PagarmeUtil::convertToPagarMeObject($response);
			return $this->transactions;
	}

	public function cancel() {
			$request = new PagarmeRequest(self::getUrl() . '/' . $this->id . '/cancel', 'POST');
			$response = $request->run();
			$this->refresh($response);
	}

	public function charge($amount, $installments=1) {
			$this->amount = $amount;
			$this->installments = $installments;
			$request = new PagarmeRequest(self::getUrl(). '/' . $this->id . '/transactions', 'POST');
			$request->setParameters($this->unsavedArray());
			$response = $request->run();

			$request = new PagarmeRequest(self::getUrl() . '/' . $this->id, 'GET');
			$response = $request->run();
			$this->refresh($response);
	}
}
