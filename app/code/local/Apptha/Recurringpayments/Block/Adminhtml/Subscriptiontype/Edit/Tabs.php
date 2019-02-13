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
 * @package     Apptha_Extendedmassaction
 * @version     0.1.0
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2014 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 */

/**
 * Subscription Type Tabs block
 */
class Apptha_Recurringpayments_Block_Adminhtml_Subscriptiontype_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'subscriptiontype_tabs' );
		$this->setDestElementId ( 'edit_form' );
		$this->setTitle ( Mage::helper ( 'recurringpayments' )->__ ( 'The Subscription Type ' ) );
	}
	/**
	 * Function to add the required tabs
	 * @return array
	 */
	protected function _beforeToHtml() {
		$this->addTab ( 'form_section', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'General' ),
				'title' => Mage::helper ( 'recurringpayments' )->__ ( 'General' ),
				'content' => $this->getLayout ()->createBlock ( 'recurringpayments/adminhtml_subscriptiontype_edit_tab_form' )->toHtml () 
		) );
		
		$this->addTabAfter ( 'schedule_section', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'Schedule' ),
				'title' => Mage::helper ( 'recurringpayments' )->__ ( 'Schedule' ),
				'content' => $this->getLayout ()->createBlock ( 'recurringpayments/adminhtml_subscriptiontype_edit_tab_schedule' )->toHtml () 
		), 'form_section' );
		
		
		return parent::_beforeToHtml ();
	}
}