<?php

namespace CustomerApi\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Customer\Model\Customer;
use Customer\Form\CustomerForm;
use Zend\View\Model\JsonModel;

class CustomerApiController extends AbstractRestfulController
{

    const AUTHORIZED_USER = 9753;

    /**
     ** Authorize requests by requiring an X-AUTH-TOKEN header with a Json Web Token value.
     ** Json Web Token provide a json payload signed with strong HMAC making them difficult to forge.
     ** Here the payload contains an id for authorized user.  Since the User model is not yet implmented,
     ** just look for a special id.
     **
     ** @return Bool is authorized
     **/
    protected function authorize()
    {
        $request = $this->getRequest();
        try {
            if ($request->getHeaders()->has('X-AUTH-TOKEN')) {
                $encodedToken = $request->getHeader('X-AUTH-TOKEN')->getFieldValue();
                $key = "secret";
                $token = \JWT::decode($encodedToken, $key);
                $uid = $token->uid;
                if ($uid == self::AUTHORIZED_USER) { // TODO: Hard coded for now, but should be used to look-up user
                  return true;
                }
            }
        } catch (\Exception $e) {
        }
        return false;
    }

    /**
     ** Error response given for unauthorized access.
     **
     ** @return JsonModel not authorized error response
     **/
    protected function notAuthorized()
    {
        $response = $this->getResponse();

        $response->setStatusCode(401);
        return new JsonModel(array(
            'error' => 'Unauthorized',
            'detail' => 'Not authorized for access',
            'status' => '401',
            ));
    }

    /**
     ** Add access controll headers.
     **
     ** @return Response response with access controll headers added
     **/
    public function addHeaders()
    {
        $response = $this->getResponse();
        $response->getHeaders()
                 ->addHeaderLine('Access-Control-Allow-Origin','*')
                 ->addHeaderLine('Access-Control-Allow-Methods','GET, POST, PUT, OPTIONS, DELETE')
                 ->addHeaderLine('Access-Control-Allow-Headers','X-AUTH-TOKEN');
        return $response;
    }

    /**
     ** index => # GET /api/v1/customers
     ** search_email => # GET /api/v1/customers/email/query
     ** search_name => # GET /api/v1/customers/name/query
     **
     ** @return Response customers or error
     */
    public function getList()
    {
        if ($this->authorize()) {
            $email = $this->params()->fromRoute('email',null);
            $name = $this->params()->fromRoute('name',null);
            $results = null;
            if (!is_null($email)) {
                $results =  $this->getCustomerTable()->fetchByEmail($email);
            } elseif (!is_null($name)) {
                $results =  $this->getCustomerTable()->fetchByName($name);
            } else {
                $results = $this->getCustomerTable()->fetchAll();
            }
            $data = array();
            foreach($results as $result) {
                $data[] = $result;
            }

            $this->addHeaders();
            return new JsonModel(array(
                'customers' => $data,
            ));
        }
        else {
          return $this->notAuthorized();
        }
    }

    /**
     ** read => # GET /api/v1/customers:id
     **
     ** @param String $id customer id
     **
     ** @return Response customer or error
     **/
    public function get($id)
    {
        if ($this->authorize()) {
            $customer = $this->getCustomerTable()->getCustomer($id);

                $this->addHeaders();
                return new JsonModel(array(
                'customer' => $customer,
            ));
        }
        else {
          return $this->notAuthorized();
        }
    }

    /**
     ** create => # POST /api/v1/customers
     **
     ** @param Array form data indexed by 'customer' root key.
     **
     ** @return Response created customer or error
     **/
    public function create($data)
    {
        if ($this->authorize()) {
            $data = $data["customer"];
            $form = new CustomerForm();
            $customer = new Customer();
            $form->setInputFilter($customer->getInputFilter());
            $form->setData($data);
            $id = null;
            if ($form->isValid()) {
                $customer->exchangeArray($form->getData());
                $id = $this->getCustomerTable()->saveCustomer($customer);
            }

            $this->addHeaders();
            return $this->get($id);
        }
        else {
          return $this->notAuthorized();
        }
    }

     /**
      ** update => # PUT /api/v1/customers/:id
      **
      ** @param String $id customer id
      ** @param Array form data indexed by 'customer' root key.
      **
      ** @return Response updated customer or error
      **/
    public function update($id, $data)
    {
        if ($this->authorize()) {
            $data = $data["customer"];
            $data['id'] = $id;
            $customer = $this->getCustomerTable()->getCustomer($id);
            $form  = new CustomerForm();
            $form->bind($customer);
            $form->setInputFilter($customer->getInputFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCustomerTable()->saveCustomer($form->getData());
            }

            $this->addHeaders();
            return $this->get($id);
        }
        else {
          return $this->notAuthorized();
        }
    }

    /**
     ** delete => # DELETE /api/v1/customers/:id
     **
     ** @param String $id customer id
     **
     ** @return Response deleted or error
     **/
    public function delete($id)
    {
        if ($this->authorize()) {
            $this->getCustomerTable()->deleteCustomer($id);

            $this->addHeaders();
            return new JsonModel(array(
                'data' => 'deleted',
            ));
        }
        else {
          return $this->notAuthorized();
        }
    }

    /**
     ** options => # OPTIONS /api/v1/customers
     **
     ** @return Response empty
     **/
    public function options()
    {
        return $this->addHeaders();
    }

    /**
     ** Get customer table
     **
     ** @return CustomerTable table
     **/
    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Customer\Model\CustomerTable');
        }
        return $this->customerTable;
    }
}
