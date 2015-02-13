<?php
// module/Customer/test/CustomerTest/Model/CustomerTest.php:
namespace CustomerTest\Model;

use Customer\Model\Customer;

use PHPUnit_Framework_TestCase;

class CustomerTest extends PHPUnit_Framework_TestCase
{
    public function testCustomerInitialState()
    {
        $customer = new Customer();

        $this->assertNull($customer->email, '"email" should initially be null');
        $this->assertNull($customer->id, '"id" should initially be null');
        $this->assertNull($customer->company, '"company" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $customer = new Customer();
        $data  = array('email' => 'some email',
                       'id'     => 123,
                       'company'  => 'some company');

        $customer->exchangeArray($data);

        $this->assertSame($data['email'], $customer->email, '"email" was not set correctly');
        $this->assertSame($data['id'], $customer->id, '"company" was not set correctly');
        $this->assertSame($data['company'], $customer->company, '"company" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $customer = new Customer();

        $customer->exchangeArray(array('email' => 'some email',
                                    'id'     => 123,
                                    'company'  => 'some company'));
        $customer->exchangeArray(array());

        $this->assertNull($customer->email, '"email" should have defaulted to null');
        $this->assertNull($customer->id, '"company" should have defaulted to null');
        $this->assertNull($customer->company, '"company" should have defaulted to null');
    }
}