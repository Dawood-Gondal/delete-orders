<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_DeleteOrders
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\DeleteOrders\Plugin\Order;

use Magento\Framework\AuthorizationInterface;
use Magento\Ui\Component\MassAction;
use M2Commerce\DeleteOrders\Helper\Data;

/**
 * Class AddDeleteAction
 */
class AddDeleteAction
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var AuthorizationInterface
     */
    protected $_authorization;

    /**
     * AddDeleteAction constructor.
     *
     * @param Data $helper
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        Data $helper,
        AuthorizationInterface $authorization
    ) {
        $this->helper         = $helper;
        $this->_authorization = $authorization;
    }

    /**
     * @param MassAction $object
     * @param $result
     *
     * @return mixed
     */
    public function afterGetChildComponents(MassAction $object, $result)
    {
        if (!isset($result['m2c_delete'])) {
            return $result;
        }

        if (!$this->helper->isEnabled() || !$this->_authorization->isAllowed('M2Commerce_DeleteOrders::delete')) {
            unset($result['m2c_delete']);
        }

        return $result;
    }
}
