<?php
namespace Customer\Password\Controller\Adminhtml\Password;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    protected $_encryptor;

    protected $_customerRepository;

    protected $messageManager;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\ResourceModel\CustomerRepository $customerRepository,
        \Magento\Framework\Encryption\Encryptor $encryptor,
        \Magento\Framework\Message\ManagerInterface $messageManager

    ) {
        parent::__construct($context);
        $this->_customerRepository = $customerRepository;
        $this->resultPageFactory = $resultPageFactory;
        $this->_encryptor          = $encryptor;
        $this->messageManager = $messageManager;

    }

    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Customer_Password::resetpassword');
        $resultPage->addBreadcrumb(__('CMS'), __('CMS'));
        $resultPage->addBreadcrumb(__('Manage Password'), __('Reset Password'));
        $resultPage->getConfig()->getTitle()->prepend(__('Reset Password'));
        $data = $this->getRequest()->getPostValue();    
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        /*if ($data) {
            try {
                $customer = $this->_customerRepository->getById($data['customer_email']);
                $this->_customerRepository->save($customer, $this->_encryptor->getHash($data['password'], true));
                $this->messageManager->addSuccess(__('Password has been changed.'));
                
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while changing password.'));
            }
        }*/
        
        return $resultPage;

    }
}
