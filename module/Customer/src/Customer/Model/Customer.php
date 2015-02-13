<?php
// module/Customer/src/Customer/Model/Customer.php:
namespace Customer\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Customer implements InputFilterAwareInterface
{
//   id INTEGER PRIMARY KEY AUTOINCREMENT,
//   email CHAR(254) NOT NULL,
//   company CHAR(100) NOT NULL,
//   name_first CHAR(40) NOT NULL,
//   name_last CHAR(40) NOT NULL,
//   phone CHAR(40) NOT NULL,
//   address CHAR(40) NOT NULL,
//   city CHAR(40) NOT NULL,
//   state CHAR(20) NOT NULL,
//   postal CHAR(11) NOT NULL

    public $id;
    public $email;
    public $company;
    public $name_first;
    public $name_last;
    public $name_full;
    public $phone;
    public $address;
    public $city;
    public $state;
    public $postal;
    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->company  = (isset($data['company']))  ? $data['company']  : null;
        $this->name_first  = (isset($data['name_first']))  ? $data['name_first']  : null;
        $this->name_last  = (isset($data['name_last']))  ? $data['name_last']  : null;
        $this->name_full = $this->name_last.', '.$this->name_first;
        $this->phone  = (isset($data['phone']))  ? $data['phone']  : null;
        $this->address  = (isset($data['address']))  ? $data['address']  : null;
        $this->city  = (isset($data['city']))  ? $data['city']  : null;
        $this->state  = (isset($data['state']))  ? $data['state']  : null;
        $this->postal  = (isset($data['postal']))  ? $data['postal']  : null;
    }

     // Add the following method:
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

/*
            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));
*/

            $inputFilter->add($factory->createInput(array(
                'name'     => 'email',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 254,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'company',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}