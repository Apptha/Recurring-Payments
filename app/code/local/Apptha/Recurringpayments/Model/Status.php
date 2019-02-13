<?php
/**
 * Apptha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.apptha.com/LICENSE.txt
 *
 * ==============================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * ==============================================================
 * This package designed for Magento COMMUNITY edition
 * Apptha does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Apptha does not provide extension support in case of
 * incorrect edition usage.
 * ==============================================================
 *
 * @category    Apptha
 * @package     Apptha_Recurringpayments
 * @version     0.1.0
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2014 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 */
/**
 * Status Model Class
 */
class Apptha_Recurringpayments_Model_Status extends Varien_Object {
	const STATUS_ACTIVE = 0;
	const STATUS_SUSPENDED = 1;
	const STATUS_PENDING = 2;
	const STATUS_CANCELLED = 3;	
	static public function getOptionArray() {
		return array (
				self::STATUS_ACTIVE => Mage::helper ( 'recurringpayments' )->__ ( 'Active' ),
				self::STATUS_SUSPENDED => Mage::helper ( 'recurringpayments' )->__ ( 'Suspended' ),
				self::STATUS_PENDING => Mage::helper ( 'recurringpayments' )->__ ( 'Pending' ),
				self::STATUS_CANCELLED => Mage::helper ( 'recurringpayments' )->__ ( 'Cancelled' ) 
		);
	}
}