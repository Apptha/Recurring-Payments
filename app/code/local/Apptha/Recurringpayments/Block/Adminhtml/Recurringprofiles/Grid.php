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
 * RecurringProfiles Grid block
 */
class Apptha_Recurringpayments_Block_Adminhtml_Recurringprofiles_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'recurringprofilesGrid' );
		$this->setDefaultSort ( 'recurringprofiles_id' );
		$this->setDefaultDir ( 'ASC' );
		$this->setSaveParametersInSession ( true );
	}
	
	/**
	 * Prepare Collection.
	 * Set the recurring profile collection based on the status received from Controller.
	 * 
	 * @return array
	 */
	protected function _prepareCollection() {
		$status = Mage::registry ( 'ProfileStatus' );
		$state = Mage::registry ( 'State' );		
		if ($status == 3) {
			$collection = Mage::getModel ( 'sales/recurring_profile' )->getCollection ();
			
			$this->setCollection ( $collection );
		} else {
			$collection = Mage::getModel ( 'sales/recurring_profile' )->getCollection ()->addFieldToFilter ( 'state', $state );
			
			$this->setCollection ( $collection );
		}
		return parent::_prepareCollection ();
	}
	
	/**
	 * Prepare Columns.
	 * Add block for adding columns in the grid.
	 *
	 * @return array
	 */
	protected function _prepareColumns() {
		$this->addColumn ( 'profile_id', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Id' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'profile_id' 
		) );
		$this->addColumn ( 'reference_id', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Payment Refrence Id' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'reference_id' 
		)
		 );
		
		$this->addColumn ( 'method_code', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Payment Method' ),
				'align' => 'left',
				'index' => 'method_code' 
		)
		 );
		
		$this->addColumn ( 'state', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Profile State' ),
				'align' => 'left',
				'index' => 'state',
				'filter' => false 
		)
		 );
		
		$this->addColumn ( 'billing_amount', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Billing Amount' ),
				'align' => 'left',
				'index' => 'billing_amount', 
				'type'  => 'price',
               'currency_code' => Mage::app()->getStore(0)->getBaseCurrency()->getCode(),//default currency
		) );
		
		$this->addColumn ( 'created_at', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Created At' ),
				'align' => 'left',
				'index' => 'created_at' 
		) );
		$this->addColumn ( 'start_datetime', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Start Date' ),
				'align' => 'left',
				'index' => 'start_datetime' 
		) );
		$this->addColumn('schedule_description',
				array (
						'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Schedule Descripiton' ),
						'align' => 'left',
						'index' => 'schedule_description'
						)
		);
		$this->addColumn('period_unit',
				array (
						'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Billing Period Unit' ),
						'align' => 'left',
						'index' => 'period_unit'
				)
		);
		$this->addColumn('period_frequency',
				array (
						'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Billing Period Frequency' ),
						'align' => 'left',
						'index' => 'period_frequency'
				)
		);
		$this->addColumn('period_max_cycles',
				array (
						'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Billing Cycles' ),
						'align' => 'left',
						'index' => 'period_max_cycles'
				)
		);
		$this->addColumn('trial_period_unit',
				array (
						'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Trial Period Unit' ),
						'align' => 'left',
						'index' => 'trial_period_unit'
				)
		);
		$this->addColumn('trial_period_max_cycles',
				array (
						'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Trial Period Cycles' ),
						'align' => 'left',
						'index' => 'trial_period_max_cycles'
				)
		);
		
		return parent::_prepareColumns ();
	}
}