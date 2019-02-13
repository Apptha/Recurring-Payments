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
 * Subscription Type Grid block
 */
class Apptha_Recurringpayments_Block_Adminhtml_Subscriptiontype_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'subscriptiontypeGrid' );
		$this->setDefaultSort ( 'subscriptiontype_id' );
		$this->setDefaultDir ( 'ASC' );
		$this->setSaveParametersInSession ( true );
	}
	
	/**
	 * Function to prepare columns to display grid
	 *
	 * @return array
	 *
	 */
	protected function _prepareCollection() {
		$collection = Mage::getModel ( 'recurringpayments/subscriptiontype' )->getCollection ();
		$this->setCollection ( $collection );
		return parent::_prepareCollection ();
	}
	
	/**
	 * Function to prepare collection
	 *
	 * @return array
	 *
	 */
	protected function _prepareColumns() {
		$this->addColumn ( 'id', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Id' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'id' 
		) );
		
		$this->addColumn ( 'engine_code', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Engine' ),
				'align' => 'left',
				'index' => 'engine_code',
				'type' => 'options',
				'options' => array (
						'Paypal' 
				)
				 
		) );
		
		$this->addColumn ( 'title', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Title' ),
				'align' => 'left',
				'index' => 'title' 
		) );
		
		$this->addColumn ( 'status', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Status' ),
				'align' => 'left',
				'index' => 'status',
				'type' => 'options',
				'options' => array (
						'Invisible',
						'Visible' 
				) 
		) );
		$periodunit = Mage::getModel ( 'recurringpayments/periodunit' )->getOptionArray ();
		$this->addColumn ( 'billing_period_unit', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Billing Period Unit' ),
				'align' => 'left',
				'index' => 'billing_period_unit',
				'type' => 'options',
				'options' => $periodunit 
		) );
		
		$this->addColumn ( 'billing_frequency', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Billing Frequency' ),
				'align' => 'left',
				'index' => 'billing_frequency' 
		) );
		
		$this->addColumn ( 'billing_cycle', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Billing Cycles' ),
				'align' => 'left',
				'index' => 'billing_cycle' 
		) );
		$this->addColumn ( 'is_infinite', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Is Infinite' ),
				'align' => 'left',
				'index' => 'is_infinite',
				'type' => 'options',
				'options' => array (
						'No',
						'Yes' 
				) 
		) );
		$this->addColumn ( 'is_trial_enabled', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Is Trial Enabled' ),
				'align' => 'left',
				'index' => 'is_trial_enabled',
				'type' => 'options',
				'options' => array (
						'No',
						'Yes' 
				) 
		) );
		
		$this->addColumn ( 'is_initial_fee_enabled', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Is Initial Fee Enabled' ),
				'align' => 'left',
				'index' => 'is_initial_fee_enabled',
				'type' => 'options',
				'options' => array (
						'No',
						'Yes' 
				) 
		) );
		
		$this->addColumn ( 'action', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Action' ),
				'width' => '100',
				'type' => 'action',
				'getter' => 'getId',
				'actions' => array (
						array (
								'caption' => Mage::helper ( 'recurringpayments' )->__ ( 'Edit' ),
								'url' => array (
										'base' => '*/*/edit' 
								),
								'field' => 'id' 
						) 
				),
				'filter' => false,
				'sortable' => false,
				'index' => 'subscription',
				'is_system' => true 
		) );
		
		return parent::_prepareColumns ();
	}
	/**
	 * Function to delete multiple records
	 */
// 	protected function _prepareMassaction() {
// 		$this->setMassactionIdField ( 'subscriptiontype_id' );
// 		$this->getMassactionBlock ()->setFormFieldName ( 'subscriptiontype' );
		
// 		$this->getMassactionBlock ()->addItem ( 'delete', array (
// 				'label' => Mage::helper ( 'recurringpayments' )->__ ( 'Delete' ),
// 				'url' => $this->getUrl ( '*/*/massDelete' ),
// 				'confirm' => Mage::helper ( 'recurringpayments' )->__ ( 'Are you sure?' ) ,
				
// 		) );
		
// 		return $this;
// 	}
	
	/**
	 * It gets the current row url
	 *
	 * @method getRowUrl()
	 * @param $row @return
	 *        	array
	 */
	public function getRowUrl($row) {
		return $this->getUrl ( '*/*/edit', array (
				'id' => $row->getId () 
		) );
	}
}