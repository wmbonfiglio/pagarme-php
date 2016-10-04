<?php

class PagarMe_PlanTest extends PagarMeTestCase {
	public function testCreate() {
		$plan = self::createTestPlan();
		$plan->create();
		$this->assertTrue($plan->getId());
	}

	public function testUpdate() {
		$plan = self::createTestPlan();
		$plan->create();
		$this->assertEqual($plan->getName(), "Plano Silver");
		$plan->setName("Plano gold!");
		$plan->save();
		$plan2 = \Pagarme\Models\Plan::findById($plan->getId());
		$this->assertEqual($plan->getName(), $plan2->getName());

		$this->expectException(new IsAExpectation('\Pagarme\Core\PagarmeException'));
		$plan2->setDays('60');
		$plan2->save();
	}

	public function testUpdatePaymentMethods() {
		$plan = self::createTestPlan();
		$plan->create();
		$this->assertTrue(in_array('credit_card', $plan->getPaymentMethods()));
		$this->assertTrue(in_array('boleto', $plan->getPaymentMethods()));

		$plan2 = self::createTestPlan();
		$plan2->setPaymentMethods(array('credit_card'));

		$this->assertTrue(in_array('credit_card', $plan2->getPaymentMethods()));
		$this->assertFalse(in_array('boleto', $plan2->getPaymentMethods()));
	}

	public function testValidate() {
		$plan = new \Pagarme\Models\Plan();

		$this->expectException(new IsAExpectation('\Pagarme\Core\PagarmeException'));
		$plan->setAmount('0');
		$plan->create();
		$plan->setAmount('10000');

		$this->expectException(new IsAExpectation('\Pagarme\Core\PagarmeException'));
		$plan->days('0');
		$plan->create();
		$plan->setDays('30');

		$this->expectException(new IsAExpectation('\Pagarme\Core\PagarmeException'));
		$plan->setTrialDays('-1');
		$plan->create();
		$plan->setTrialDays("10");

		$this->expectException(new IsAExpectation('\Pagarme\Core\PagarmeException'));
		$plan->setName('');
		$plan->create();
		$plan->setName('Plan');

		$plan->create();

		$plan->assertTrue($plan->getId());
	}
}
