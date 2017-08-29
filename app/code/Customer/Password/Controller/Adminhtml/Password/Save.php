<?php
namespace Customer\Password\Controller\Adminhtml\Password;


use Magento\Backend\App\Action;

class Save extends \Magento\Backend\App\Action
{


    protected $resultPageFactory;

    protected $_encryptor;

    protected $_CustomerRepositoryInterface;

    protected $_customerRegistry;

    protected $messageManager;

    /**
     * @param Action\Context $context
     */
    public function __construct(
               Action\Context $context,
              \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
              \Magento\Customer\Model\CustomerRegistry $customerRegistry,
              \Magento\Framework\Encryption\Encryptor $encryptor,
              \Magento\Framework\Message\ManagerInterface $messageManager   
    )
    {
        $this->_customerRegistry   = $customerRegistry;
        $this->_CustomerRepositoryInterface = $customerRepository;
        $this->_encryptor          = $encryptor;
        $this->messageManager = $messageManager;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
        $object_manager = \Magento\Framework\App\ObjectManager::getInstance();
        $customer_factory = $object_manager->get('\Magento\Customer\Model\CustomerFactory');
        $storeManager = $object_manager->get('\Magento\Store\Model\StoreManagerInterface');
        $website_id = $storeManager->getWebsite()->getWebsiteId();
        $customer_data = $customer_factory->create();
        $customer_data->setWebsiteId($website_id);
        $customer_data->loadByEmail($data['customer_email']);
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        if($customer_data->getId()){
        try {
                
                $customer = $this->_CustomerRepositoryInterface->getById($customer_data->getId());
                $customerSecure = $this->_customerRegistry->retrieveSecureData($customer->getId());
                $customerSecure->setRpToken(null);
                $customerSecure->setRpTokenCreatedAt(null);
                $customerSecure->setPasswordHash($this->_encryptor->getHash($data['password'], true));
                $this->_CustomerRepositoryInterface->save($customer);

                //$customer = $this->_customerRepository->getById($customer_data->getId());
                //$this->_customerRepository->save($customer, $this->_encryptor->getHash($data['password'], true));
                $this->messageManager->addSuccess(__('Password has been changed.'));
        
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while changing password.'));
            }
          }else{
                $this->messageManager->addError('Customer email does not exist');
          }
       }
        return $resultRedirect->setPath('changepassword/password/index');
    }
}
