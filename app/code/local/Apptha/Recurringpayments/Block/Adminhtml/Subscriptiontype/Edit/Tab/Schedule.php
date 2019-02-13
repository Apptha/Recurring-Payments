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
 * Subscription Type Schedule Form block
 *
 */
class Apptha_Recurringpayments_Block_Adminhtml_Subscriptiontype_Edit_Tab_Schedule extends Mage_Adminhtml_Block_Widget_Form {
	
	/**
	 * Prepare form to schedule the subscription type.
	 * @return array
	 */
	protected function _prepareForm() {
		$form = new Varien_Data_Form ();
		$this->setForm ( $form );
		$fieldset = $form->addFieldset ( 'subscriptiontype_form', array (
				'legend' => Mage::helper ( 'recurringpayments' )->__ ( 'Schedule' ) 
		) );
		
		$yesNoValues = Mage::getModel ( 'adminhtml/system_config_source_yesno' )->toOptionArray ();
		
		$periodunit = Mage::getModel ( 'recurringpayments/periodunit' )->getOptionArray ();
		$fieldset->addField ( 'billing_period_unit', 'select', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'Billing Period' ),
				'required' => true,
				'name' => 'billing_period_unit',
				'note' => Mage::helper ( 'recurringpayments' )->__ ( 'Unit for billing during the subscription period..' ),
				'values' => $periodunit 
		) );
		
		$fieldset->addField ( 'billing_frequency', 'text', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'Billing Frequency' ),
				'required' => true,
				'name' => 'billing_frequency' ,
				'class' => 'required-entry validate-digits',
		) );
		$isinfinite = $fieldset->addField ( 'is_infinite', 'select', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'Is Infinite' ),
				'required' => false,
				'name' => 'is_infinite',
				'values' => $yesNoValues ,				
		) );
		
		$occurrance = $fieldset->addField ( 'billing_cycle', 'text', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'Billing Cycles' ),
				'required' => true,
				'name' => 'billing_cycle',
				'class' => 'required-entry validate-digits',
				'note' => Mage::helper ( 'recurringpayments' )->__ ( 'The number of billing cycles for payment period.' ) 
		) );
		
		$Trialfieldset = $form->addFieldset ( 'subscriptiontype_Trial', array (
				'legend' => Mage::helper ( 'recurringpayments' )->__ ( 'Trial Period' ) 
		) );
		
		$istrial = $Trialfieldset->addField ( 'is_trial_enabled', 'select', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'Is Trial Period Enabled' ),
				'required' => false,
				'name' => 'is_trial_enabled',
				'onchange' => 'hideShowSubproductOptions(this);',
				'values' => $yesNoValues 
		) );
		
		$trialoccurrance = $Trialfieldset->addField ( 'occurrences_for_trialperiod', 'text', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'No Of Occurences For Trial Enabled' ),
				'name' => 'occurrences_for_trialperiod',
				'required' => true,
				'class' => 'required-entry validate-digits',
		) );
		
		$Initialfeefieldset = $form->addFieldset ( 'subscriptiontype_Initialfee', array (
				'legend' => Mage::helper ( 'recurringpayments' )->__ ( 'Initial Fee' ) 
		) );
		
		$isinitialfee = $Initialfeefieldset->addField ( 'is_initial_fee_enabled', 'select', array (
				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'Is Initial Fee Enabled' ),
				'required' => false,
				'note' => Mage::helper ( 'recurringpayments' )->__ ( 'Initial non-recurring payment amount due immediately upon profile creation.' ),
				'name' => 'is_initial_fee_enabled',
				'values' => $yesNoValues 
		) );
		
		$this->setChild ( 'form_after', $this->getLayout ()->createBlock ( 'adminhtml/widget_form_element_dependence' )->addFieldMap ( $istrial->getHtmlId (), $istrial->getName () )->addFieldMap ( $trialoccurrance->getHtmlId (), $trialoccurrance->getName () )->addFieldMap ( $isinfinite->getHtmlId (), $isinfinite->getName () )->addFieldMap ( $occurrance->getHtmlId (), $occurrance->getName () )->addFieldDependence ( $trialoccurrance->getName (), $istrial->getName (), 1 )->addFieldDependence ( $occurrance->getName (), $isinfinite->getName (), 0 ) );
		
		if (Mage::getSingleton ( 'adminhtml/session' )->getSubscriptiontypeData ()) {
			$form->setValues ( Mage::getSingleton ( 'adminhtml/session' )->getSubscriptiontypeData () );
			Mage::getSingleton ( 'adminhtml/session' )->setSubscriptiontypeData ( null );
		} elseif (Mage::registry ( 'subscriptiontype_data' )) {
			$form->setValues ( Mage::registry ( 'subscriptiontype_data' )->getData () );
		}
		return parent::_prepareForm ();
	}
}