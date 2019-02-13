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
 * Subscription Type Edit block
 */
class Apptha_Recurringpayments_Block_Adminhtml_Subscriptiontype_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {
	public function __construct() {
		parent::__construct ();
		
		$this->_objectId = 'id';
		$this->_blockGroup = 'subscriptiontype';
		$this->_controller = 'adminhtml_subscriptiontype';
		
		$this->_updateButton ( 'save', 'label', Mage::helper ( 'recurringpayments' )->__ ( 'Save Type' ) );
		$this->_updateButton ( 'delete', 'label', Mage::helper ( 'recurringpayments' )->__ ( 'Delete Type' ) );
		
		$this->_addButton ( 'saveandcontinue', array (
				'label' => Mage::helper ( 'adminhtml' )->__ ( 'Save And Continue Edit' ),
				'onclick' => 'saveAndContinueEdit()',
				'class' => 'save' 
		), - 100 );
		
		$this->_formScripts [] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('subscriptiontype_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'subscriptiontype_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'subscriptiontype_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
	}
	
	/**
	 * Set Header Text
	 * 
	 * @see Mage_Adminhtml_Block_Widget_Container::getHeaderText()
	 */
	public function getHeaderText() {
		if (Mage::registry ( 'subscriptiontype_data' ) && Mage::registry ( 'subscriptiontype_data' )->getId ()) {
			return Mage::helper ( 'recurringpayments' )->__ ( "Edit Type  " . '%s', '"' . $this->htmlEscape ( Mage::registry ( 'subscriptiontype_data' )->getTitle () ) . '"' );
		} else {
			return Mage::helper ( 'recurringpayments' )->__ ( 'Add Type' );
		}
	}
}