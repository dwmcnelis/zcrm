<?php
// module/Customer/src/Customer/Model/CustomerTable.php:
namespace Customer\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;

class CustomerTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     ** Fetches all customer records
     **
     ** @return RowSet results
     **/
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     ** Fetches customer records by exact email adress
     **
     ** @param String $email
     ** @return RowSet results
     **/
    public function fetchByEmail($email)
    {
        $resultSet = $this->tableGateway->select(array('email' => $email));
        return $resultSet;
    }

    /**
     ** Fetches customer records by partial name matched in first or last name
     **
     ** @param String $name
     ** @return RowSet results
     **/
    public function fetchByName($name)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->where->like('name_last', '%'.$name.'%')->or->like('name_first', '%'.$name.'%');
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }

    /**
     ** Get customer record by exact id
     **
     ** @param String $id
     ** @return Row results
     **/
    public function getCustomer($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    /**
     ** Save customer from object
     **
     ** @param Customer $customer
     ** @return String id
     **/
    public function saveCustomer(Customer $customer)
    {
        $data = array(
            'email' => $customer->email,
            'company'  => $customer->company,
            'name_first'  => $customer->name_first,
            'name_last'  => $customer->name_last,
            'phone'  => $customer->phone,
            'address'  => $customer->address,
            'city'  => $customer->city,
            'state'  => $customer->state,
            'postal'  => $customer->postal,
        );

        $id = (int)$customer->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->lastInsertValue;
       } else {
            if ($this->getCustomer($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
        return ( String )$id;
    }

    /**
     ** Delete customer by exact id
     **
     ** @param String $id
     **/
    public function deleteCustomer($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}