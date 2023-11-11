<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_DeleteOrders
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\DeleteOrders\Plugin\Order;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Sales\Block\Adminhtml\Order\View;
use M2Commerce\DeleteOrders\Helper\Data;

/**
 * Class AddDeleteButton
 */
class AddDeleteButton
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var AuthorizationInterface
     */
    protected $authorization;

    /**
     * @param Data $helper
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        Data $helper,
        AuthorizationInterface $authorization
    ) {
        $this->helper         = $helper;
        $this->authorization = $authorization;
    }

    /**
     * @param View $object
     * @param LayoutInterface $layout
     *
     * @return array
     */
    public function beforeSetLayout(View $object, LayoutInterface $layout)
    {
        if ($this->helper->isEnabled() && $this->authorization->isAllowed('M2Commerce_DeleteOrders::delete')) {
            $message = __('Are you sure you want to delete this order?');
            $deleteUrl = $object->getUrl('m2c-od/order/deleteSingle');
            $object->addButton(
                'order_delete',
                [
                    'label'   => __('Delete'),
                    'class'   => 'delete',
                    'id'      => 'order-view-delete-button',
                    'onclick' => "confirmSetLocation('{$message}', '{$deleteUrl}')"
                ]
            );
        }

        return [$layout];
    }
}
