<?php
// module/Customer/test/CustomerTest/Model/CustomerTableTest.php:
namespace Customer\Model;

use Zend\Db\ResultSet\ResultSet;
use PHPUnit_Framework_TestCase;

class CustomerTableTest extends PHPUnit_Framework_TestCase
{
    public function testFetchAllReturnsAllCustomers()
    {
        $resultSet        = new ResultSet();
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $customerTable = new CustomerTable($mockTableGateway);

        $this->assertSame($resultSet, $customerTable->fetchAll());
    }
    public function testCanRetrieveAnCustomerByItsId()
    {
        $customer = new Customer();
        $customer->exchangeArray(array('id'     => 123,
                                    'email' => 'mikel@franeckilang.name',
                                    'company'  => 'Acme',
                                    'name_last' => 'Heidenreich'));

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Customer());
        $resultSet->initialize(array($customer));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $customerTable = new CustomerTable($mockTableGateway);

        $this->assertSame($customer, $customerTable->getCustomer(123));
    }

    public function testCanDeleteAnCustomerByItsId()
    {
        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('delete'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('delete')
                         ->with(array('id' => 123));

        $customerTable = new CustomerTable($mockTableGateway);
        $customerTable->deleteCustomer(123);
    }

    public function testSaveCustomerWillInsertNewCustomersIfTheyDontAlreadyHaveAnId()
    {
        $customerData = array('email' => 'mikel@franeckilang.name', 'company' => 'Acme', 'name_last' => 'Heidenreich', 'name_first' => null, 'phone' => null, 'address' => null, 'city' => null, 'state' => null, 'postal' => null);
        $customer     = new Customer();
        $customer->exchangeArray($customerData);

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('insert'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('insert')
                         ->with($customerData);

        $customerTable = new CustomerTable($mockTableGateway);
        $customerTable->saveCustomer($customer);
    }

    public function testSaveCustomerWillUpdateExistingCustomersIfTheyAlreadyHaveAnId()
    {
        $customerData = array('id' => 123, 'email' => 'mikel@franeckilang.name', 'company' => 'Acme', 'name_last' => 'Heidenreich', 'name_first' => null, 'phone' => null, 'address' => null, 'city' => null, 'state' => null, 'postal' => null);
        $customer     = new Customer();
        $customer->exchangeArray($customerData);

        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Customer());
        $resultSet->initialize(array($customer));

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway',
                                           array('select', 'update'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));
        $mockTableGateway->expects($this->once())
                         ->method('update')
                         ->with(array('email' => 'mikel@franeckilang.name', 'company' => 'Acme', 'name_last' => 'Heidenreich', 'name_first' => null, 'phone' => null, 'address' => null, 'city' => null, 'state' => null, 'postal' => null),
                                array('id' => 123));

        $customerTable = new CustomerTable($mockTableGateway);
        $customerTable->saveCustomer($customer);
    }

    public function testExceptionIsThrownWhenGettingNonexistentCustomer()
    {
        $resultSet = new ResultSet();
        $resultSet->setArrayObjectPrototype(new Customer());
        $resultSet->initialize(array());

        $mockTableGateway = $this->getMock('Zend\Db\TableGateway\TableGateway', array('select'), array(), '', false);
        $mockTableGateway->expects($this->once())
                         ->method('select')
                         ->with(array('id' => 123))
                         ->will($this->returnValue($resultSet));

        $customerTable = new CustomerTable($mockTableGateway);

        try
        {
            $customerTable->getCustomer(123);
        }
        catch (\Exception $e)
        {
            $this->assertSame('Could not find row 123', $e->getMessage());
            return;
        }

        $this->fail('Expected exception was not thrown');
    }
}