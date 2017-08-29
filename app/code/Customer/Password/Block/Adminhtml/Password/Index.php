<?php 
namespace Customer\Password\Block\Adminhtml\Password;
class Index extends  \Magento\Backend\Block\Template
{
     protected $_customers;

    public function __construct(
            \Magento\Backend\Block\Template\Context $context,
            \Magento\Customer\Model\Customer $customers,
            \Magento\Framework\Data\Form\FormKey $formkey
 
    ) { 
    	$this->_customers = $customers;
        $this->formKey=$formkey;
        parent::__construct($context);
    }

    public function getCollection(){
    	 return $this->_customers->getCollection();

    }

    public function getCustomer($customerId)
    {
        //Get customer by customerID
        return $this->_customers->load($customerId);
    }

    public function updateCancelTime(){

        return $this->formKey->getFormKey();
    }
}

?>