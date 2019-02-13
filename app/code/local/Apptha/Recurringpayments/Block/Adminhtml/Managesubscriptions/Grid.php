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
 * Display Managesubscriptions grid 
 * 
 */
class Apptha_Recurringpayments_Block_Adminhtml_Managesubscriptions_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	
	/**
	 * Construct the inital display of grid information
	 * Set the default sort for collection
	 * Set the sort order as "DESC"
	 *
	 * Return array of  managesubscriptions data 
	 * @return array
	 */
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'id' );
		$this->setDefaultSort ( 'id' );
		$this->setDefaultDir ( 'ASC' );
		$this->setSaveParametersInSession ( true );
	}
	
	/**
	 * Get collection from apptha_managesubscriptions table
	 *
	 * Return array of managesubscriptions data
	 * @return array
	 */
	protected function _prepareCollection() {
		
		/* $productSubscription = Mage::getModel ( 'recurringpayments/productsubscriptions' )->getCollection ()->addFieldToFilter ( 'is_delete', 0 );
		 $tablePrefix = Mage::getConfig ()->getTablePrefix ();
		
		$productSubscription->getSelect ()->group('main_table.product_id')->join( $tablePrefix . 'apptha_managesubscriptions', 'main_table.product_id =' . $tablePrefix . 'apptha_managesubscriptions.product_id', array (
				'main_table.product_id as product_id',
				'main_table.subscription_type as subscription_type',
				$tablePrefix . 'apptha_managesubscriptions.id as manage_id',
				$tablePrefix . 'apptha_managesubscriptions.product_name as product_name',
				$tablePrefix . 'apptha_managesubscriptions.is_subscription_only as is_subscription_only'
		
		) ); */
		$isDelete = 0;
		$productSubscription = Mage::getModel ( 'recurringpayments/productsubscriptions' )->getCollection ()->addFieldToFilter ( 'is_delete', 0 );
		$manageSubscription = Mage::getModel ( 'recurringpayments/managesubscriptions' )->getCollection ();
		$tablePrefix = Mage::getConfig ()->getTablePrefix ();		
		$manageSubscription->getSelect ()->group('main_table.product_id')->joinLeft( $tablePrefix . 'apptha_productsubscriptions', 'main_table.product_id =' . $tablePrefix . 'apptha_productsubscriptions.product_id AND '.$tablePrefix.'apptha_productsubscriptions.is_delete = 0', array (
				'main_table.id as id',
				'main_table.product_name as product_name',
				 $tablePrefix.'apptha_productsubscriptions.subscription_type as subscription_type'		
		) );
		
		//$collection = Mage::getModel ( 'recurringpayments/managesubscriptions' )->getCollection ();
		$collection=$manageSubscription;
		$this->setCollection ( $collection);
		return parent::_prepareCollection ();
	}
	
	/**
	 * Display the subscription  in grid
	 *
	 * Display information about product subscriptions
	 * @return void
	 */
	protected function _prepareColumns() {
				
		$this->addColumn ( 'id', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'ID' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'id' ,
				'filter' => false,
		) );
				
		$this->addColumn ( 'product_name', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Subscription Product Name' ),
				'align' => 'left',				
				'index'=>'product_name',
				
				)); 
		 
		$subscriptionTitle = Mage::getModel ( 'recurringpayments/subscriptiontype' )->getCollection ();
		
		$data = $subscriptionTitle->getData ();
		
		$substitle = null;
		foreach ( $data as $value ) {
			$title=$value ['title'];
			$i=$value['id'];
			$substitle [$i] = $value ['title'];			
		}
		
		$this->addColumn ( 'subscription_type', array (
				'header' => Mage::helper ( 'recurringpayments' )->__ ( 'Subscription Type' ),
				'align' => 'left',
				'width' => '80px',
				'index' => 'subscription_type',
				 'type' => 'options',
				'options' => $substitle, 
				'renderer' => 'Apptha_Recurringpayments_Block_Adminhtml_Managesubscriptions_Grid_Renderer_Type',
				
				
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
				'index' => 'stores',
				'is_system' => true 
		) );
		
		return parent::_prepareColumns ();
	}
	/**
	 * It gets the current row url
	 * 
	 *  @method getRowUrl()
	 *  @param $row @return array
	 */
	public function getRowUrl($row) {
		return $this->getUrl ( '*/*/edit', array (
				'id' => $row->getId () 
		) );
	}
	
	
	
}