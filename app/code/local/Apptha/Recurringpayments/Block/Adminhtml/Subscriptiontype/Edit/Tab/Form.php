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
 * Subscription Type Edit Form block.
 */
class Apptha_Recurringpayments_Block_Adminhtml_Subscriptiontype_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {
	
	/**
	 * Prepare Form For SubscriptionType
	 * @see Mage_Adminhtml_Block_Widget_Form::_prepareForm()
	 * @return array
	 */
	protected function _prepareForm() {
		$form = new Varien_Data_Form ();
		$this->setForm ( $form );
		$fieldset = $form->addFieldset ( 'subscriptiontype_form', array (
				'legend' => Mage::helper ( 'recurringpayments' )->__ ( 'General' ) 
		) );
				
		$fieldset->addField ( 'engine_code', 'hidden', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'Engine' ),
				'required' => false,
				'name' => 'engine_code',
				'values' => array (
						'Paypal',
						
				) 
		) );
		
		$fieldset->addField ( 'title', 'text', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'Title' ),
				'required' => true,
				'name' => 'title' ,				
		) );
		
		$fieldset->addField ( 'status', 'select', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'Status' ),
				'name' => 'status',
				'values' => array (
						'Invisible',
						'Visible' 
				) 
		) );
		
		if (Mage::getSingleton ( 'adminhtml/session' )->getSubscriptiontypeData ()) {
			$form->setValues ( Mage::getSingleton ( 'adminhtml/session' )->getSubscriptiontypeData () );
			Mage::getSingleton ( 'adminhtml/session' )->setSubscriptiontypeData ( null );
		} elseif (Mage::registry ( 'subscriptiontype_data' )) {
			$form->setValues ( Mage::registry ( 'subscriptiontype_data' )->getData () );
		}
		return parent::_prepareForm ();
	}
}