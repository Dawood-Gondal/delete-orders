<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_DeleteOrders
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\DeleteOrders\Controller\Adminhtml\Order;

use M2Commerce\DeleteOrders\Helper\Data as DataHelper;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction;
use Magento\Sales\Model\OrderRepository;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;
use Psr\Log\LoggerInterface;

/**
 * Class MassDelete
 */
class MassDelete extends AbstractMassAction
{
    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var DataHelper
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var OrderManagementInterface
     */
    protected $orderManagement;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param OrderRepository $orderRepository
     * @param DataHelper $dataHelper
     * @param LoggerInterface $logger
     * @param OrderManagementInterface $orderManagement
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        OrderRepository $orderRepository,
        DataHelper $dataHelper,
        LoggerInterface $logger,
        OrderManagementInterface $orderManagement
    ) {
        parent::__construct($context, $filter);
        $this->collectionFactory = $collectionFactory;
        $this->orderRepository   = $orderRepository;
        $this->helper            = $dataHelper;
        $this->logger            = $logger;
        $this->orderManagement  = $orderManagement;
    }

    /**
     * @param AbstractCollection $collection
     * @return Redirect
     */
    protected function massAction(AbstractCollection $collection)
    {
        if ($this->helper->isEnabled()) {
            $deleted = 0;
            /** @var OrderInterface $order */
            foreach ($collection->getItems() as $order) {
                try {
                    $this->orderRepository->delete($order);
                    $this->helper->deleteRecord($order->getId());
                    $deleted++;
                } catch (\Exception $e) {
                    $this->logger->critical($e);
                    $this->messageManager->addErrorMessage(__(
                        'Cannot delete order #%1. Please try again later.',
                        $order->getIncrementId()
                    ));
                }
            }

            if ($deleted) {
                $this->messageManager->addSuccessMessage(__('A total of %1 order(s) has been deleted.', $deleted));
            }
        }

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath($this->getComponentRefererUrl());
        return $resultRedirect;
    }
}
