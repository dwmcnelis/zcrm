<?php
namespace Customer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Customer\Model\Customer;
use Customer\Form\CustomerForm;

class CustomerController extends AbstractActionController
{
    protected $customerTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'customers' => $this->getCustomerTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new CustomerForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $customer = new Customer();
            $form->setInputFilter($customer->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $customer->exchangeArray($form->getData());
                $this->getCustomerTable()->saveCustomer($customer);

                // Redirect to list of customers
                return $this->redirect()->toRoute('customer');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('customer', array(
                'action' => 'add'
            ));
        }
        $customer = $this->getCustomerTable()->getCustomer($id);

        $form  = new CustomerForm();
        $form->bind($customer);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($customer->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getCustomerTable()->saveCustomer($form->getData());

                // Redirect to list of customers
                return $this->redirect()->toRoute('customer');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('customer');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getCustomerTable()->deleteCustomer($id);
            }

            // Redirect to list of customers
            return $this->redirect()->toRoute('customer');
        }

        return array(
            'id'    => $id,
            'customer' => $this->getCustomerTable()->getCustomer($id)
        );
    }

    public function getCustomerTable()
    {
        if (!$this->customerTable) {
            $sm = $this->getServiceLocator();
            $this->customerTable = $sm->get('Customer\Model\CustomerTable');
        }
        return $this->customerTable;
    }
}