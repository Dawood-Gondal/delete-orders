<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_DeleteOrders
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\DeleteOrders\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\ResourceModel\Order;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\ResourceModel\OrderFactory;
use M2Commerce\DeleteOrders\Model\Config\Source\Country;

/**
 * Class Data
 */
class Data extends AbstractHelper
{
    private const PATH_CONFIG_IS_ENABLE = "delete_orders/general/enabled";

    /**
     * @var OrderFactory
     */
    private $orderResourceFactory;

    /**
     * @var CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @param Context $context
     * @param CollectionFactory $orderCollectionFactory
     * @param OrderFactory $orderResourceFactory
     */
    public function __construct(
        Context $context,
        CollectionFactory $orderCollectionFactory,
        OrderFactory $orderResourceFactory
    ) {
        $this->orderResourceFactory   = $orderResourceFactory;
        $this->orderCollectionFactory = $orderCollectionFactory;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->scopeConfig->getValue(self::PATH_CONFIG_IS_ENABLE);
    }

    /**
     * @param $orderId
     */
    public function deleteRecord($orderId)
    {
        /** @var Order $resource */
        $resource   = $this->orderResourceFactory->create();
        $connection = $resource->getConnection();

        // delete invoice grid record via resource model
        $connection->delete(
            $resource->getTable('sales_invoice_grid'),
            $connection->quoteInto('order_id = ?', $orderId)
        );

        // delete shipment grid record via resource model
        $connection->delete(
            $resource->getTable('sales_shipment_grid'),
            $connection->quoteInto('order_id = ?', $orderId)
        );

        // delete creditmemo grid record via resource model
        $connection->delete(
            $resource->getTable('sales_creditmemo_grid'),
            $connection->quoteInto('order_id = ?', $orderId)
        );
    }
}
