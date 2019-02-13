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
 * @package     Apptha_RecurringPayments
 * @version     0.1.0
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2014 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 */
/**
 * Managesubscriptions_Edit_Tab Subscriptiontype block
 */
class Apptha_Recurringpayments_Block_Adminhtml_Managesubscriptions_Edit_Tab_Edit extends Mage_Adminhtml_Block_Widget_Form {
	
	/**
	 * Set template
	 */
	public function __construct() {
		parent::__construct ();
		$this->setTemplate ( 'recurringpayments/managesubscriptions/edit.phtml' )->toHtml ();		
	}
	/**
	 * Get Collection values
	 * @method 
	 */
	public function getSubscripitonCollection(){
		$subscripitonId = $subscriptionTypes = array();
		$subscriptionCollection = Mage::getModel ( 'recurringpayments/subscriptiontype' )->getCollection();
		$subscriptionTypes = $subscriptionCollection->getData();
		foreach ($subscriptionTypes as $subscriptions){
			$subscriptionId [] = $subscriptions['id'];
		}
		
		return $subscriptionId;
	}
}
	
