<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_DeleteOrders
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\DeleteOrders\Controller\Adminhtml\Order;

use M2Commerce\DeleteOrders\Helper\Data as DataHelper;
use Magento\Backend\App\Action;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\Translate\InlineInterface;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Controller\Adminhtml\Order;
use M2Commerce\DeleteOrders\Helper\Data;
use Psr\Log\LoggerInterface;

/**
 * Class Delete
 */
class DeleteSingle extends Order
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'M2Commerce_DeleteOrders::delete';

    /**
     * @var DataHelper
     */
    protected $helper;

    /**
     * @param Action\Context $context
     * @param Registry $coreRegistry
     * @param FileFactory $fileFactory
     * @param InlineInterface $translateInline
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param LayoutFactory $resultLayoutFactory
     * @param RawFactory $resultRawFactory
     * @param OrderManagementInterface $orderManagement
     * @param OrderRepositoryInterface $orderRepository
     * @param LoggerInterface $logger
     * @param Data $helper
     */
    public function __construct(
        Action\Context $context,
        Registry $coreRegistry,
        FileFactory $fileFactory,
        InlineInterface $translateInline,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        LayoutFactory $resultLayoutFactory,
        RawFactory $resultRawFactory,
        OrderManagementInterface $orderManagement,
        OrderRepositoryInterface $orderRepository,
        LoggerInterface $logger,
        DataHelper $helper
    ) {
        $this->helper = $helper;
        parent::__construct(
            $context,
            $coreRegistry,
            $fileFactory,
            $translateInline,
            $resultPageFactory,
            $resultJsonFactory,
            $resultLayoutFactory,
            $resultRawFactory,
            $orderManagement,
            $orderRepository,
            $logger
        );
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $resultRedirect  = $this->resultRedirectFactory->create();
        if (!$this->helper->isEnabled()) {
            $this->messageManager->addErrorMessage(__('Cannot delete the order.'));

            return $resultRedirect->setPath('sales/order/view', [
                'order_id' => $this->getRequest()->getParam('order_id')
            ]);
        }

        $order = $this->_initOrder();
        if ($order) {
            try {
                $this->orderRepository->delete($order);
                $this->helper->deleteRecord($order->getId());
            } catch (\Exception $e) {
                $this->logger->critical($e);
                $this->messageManager->addErrorMessage(__(
                    'Cannot delete order #%1. Please try again later.',
                    $order->getIncrementId()
                ));
            }
        }

        return $resultRedirect->setPath('sales/*/');
    }
}
