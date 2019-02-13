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
 * SubscriptionType Controller
 */
class Apptha_Recurringpayments_Adminhtml_SubscriptiontypeController extends Mage_Adminhtml_Controller_action {
	protected function _initAction() {
		$this->loadLayout ()->_setActiveMenu ( 'recurringpayments/subscriptiontype' )->_addBreadcrumb ( Mage::helper ( 'adminhtml' )->__ ( 'Subscription Type' ), Mage::helper ( 'adminhtml' )->__ ( 'Subscription Type' ) );
		
		return $this;
	}
	public function indexAction() {
		$this->_initAction ()->renderLayout ();
	}
	
	/**
	 * To Edit the SubscriptionType Table
	 */
	public function editAction() {
		$id = $this->getRequest ()->getParam ( 'id' );
		$model = Mage::getModel ( 'recurringpayments/subscriptiontype' )->load ( $id );
		
		if ($model->getId () || $id == 0) {
			$data = Mage::getSingleton ( 'adminhtml/session' )->getFormData ( true );
			if (! empty ( $data )) {
				$model->setData ( $data );
			}
			
			Mage::register ( 'subscriptiontype_data', $model );
			
			$this->loadLayout ();
			$this->_setActiveMenu ( 'recurringpayments/subscriptiontype' );
			
			$this->_addBreadcrumb ( Mage::helper ( 'adminhtml' )->__ ( 'Customer Manager' ), Mage::helper ( 'adminhtml' )->__ ( 'Customer Manager' ) );
			$this->_addBreadcrumb ( Mage::helper ( 'adminhtml' )->__ ( 'Item News' ), Mage::helper ( 'adminhtml' )->__ ( 'Item News' ) );
			
			$this->getLayout ()->getBlock ( 'head' )->setCanLoadExtJs ( true );
			
			$this->_addContent ( $this->getLayout ()->createBlock ( 'recurringpayments/adminhtml_subscriptiontype_edit' ) )->_addLeft ( $this->getLayout ()->createBlock ( 'recurringpayments/adminhtml_subscriptiontype_edit_tabs' ) );
			
			$this->renderLayout ();
		} else {
			Mage::getSingleton ( 'adminhtml/session' )->addError ( Mage::helper ( 'recurringpayments' )->__ ( 'customer does not exist' ) );
			$this->_redirect ( '*/*/' );
		}
	}
	public function newAction() {
		$this->_forward ( 'edit' );
	}
	
	/**
	 * To save the data into SubscriptionType Table
	 */
	public function saveAction() {
		if ($data = $this->getRequest ()->getPost ()) {
			 $data['engine_code']=0;
			if ($data ['is_infinite'] == "1") {
				$data ['billing_cycle'] = "Infinite";
			}
			
			$model = Mage::getModel ( 'recurringpayments/subscriptiontype' );
			if ($Id = $this->getRequest ()->getParam ( 'id' )) {
				$model->load ( $Id );
			}
			$model->addData ( $data );
			$collection = Mage::getModel ( 'recurringpayments/subscriptiontype' )->getCollection()->getLastItem();			
			$title = $collection->getTitle();
			$billingPeriodUnit = $collection->getBilling_period_unit();
			$billingFrequency = $collection->getBillingFrequency();
			$billingCycle = $collection->getBillingCycle();	
			$id = $collection->getId();			
			$status = $collection->getStatus();
			$is_infinite = $collection->getIs_infinite();
			$is_trial_enabled = $collection->getIs_trial_enabled();
			$is_initial_fee_enabled = $collection->getIs_initial_fee_enabled();
			$i = 0;
			try {
				if(!empty($title) && $title == $data['title'] && $billingPeriodUnit == $data['billing_period_unit'] && $billingFrequency == $data['billing_frequency'] && $billingCycle ==$data['billing_cycle']){									
					$modelCollection = Mage::getModel ( 'recurringpayments/subscriptiontype' )->load($id)->addData($data);					
					$modelCollection->setId($id)->save();					
					if(!empty($title) && $title != $data['title'] && $billingPeriodUnit != $data['billing_period_unit'] && $billingFrequency != $data['billing_frequency'] && $billingCycle !=$data['billing_cycle'] && $status == $data['status'] && $is_infinite== $data['is_infinite'] &&	$is_trial_enabled == $data['is_trial_enabled'] && $is_initial_fee_enabled == $data['is_initial_fee_enabled']){
						Mage::getSingleton ( 'adminhtml/session' )->addSuccess ( 'Saved Successfully' );
					}
				
				}
				else{
					$model->save ();
					Mage::getSingleton ( 'adminhtml/session' )->addSuccess ( 'Saved Successfully' );
				}
				
				if ($this->getRequest ()->getParam ( 'back' )) {
					$this->_redirect ( '*/*/edit', array (
							'id' => $model->getId () 
					) );
					return;
				}
				$this->_redirect ( '*/*/' );
			} catch ( Exception $e ) {
				
				Mage::getSingleton ( 'adminhtml/session' )->addError ( 'Not Saved. Error:' . $e->getMessage () );
				Mage::getSingleton ( 'adminhtml/session' )->setExampleFormData ( $data );
				$this->_redirect ( '*/*/edit', array (
						'id' => $model->getId (),
						'_current' => true 
				) );
			}
		}
	}
	
	/**
	 * To delete a record in SubscriptionType Table
	 */
	public function deleteAction() {
		if ($this->getRequest ()->getParam ( 'id' ) > 0) {
			try {
				$id = $this->getRequest ()->getParam ( 'id' );				
				$collection = Mage::getModel('recurringpayments/productsubscriptions')->getCollection();
				$subscripitonCollection = $collection ->addFieldToFilter('subscription_type',$id)->addFieldToFilter('is_delete',0)->getData();				
				/**
				 * To delete a subscription type record for particular product.
				 * @method isSubscriptionDelete
				 * @param $productModel (it is a collection)
				 * return boolean
				 */
				//$this->isSubscriptionDelete($productModel,$subscriptionCount);
				$this->subscriptionDelete($subscripitonCollection);
								
				$model = Mage::getModel ( 'recurringpayments/subscriptiontype' );
				
				$model->setId ( $this->getRequest ()->getParam ( 'id' ) )->delete ();
				
				Mage::getSingleton ( 'adminhtml/session' )->addSuccess ( Mage::helper ( 'adminhtml' )->__ ( 'Total of %d record(s) were successfully deleted', count ( 1)  ) );
				$this->_redirect ( '*/*/' );
			} catch ( Exception $e ) {
				Mage::getSingleton ( 'adminhtml/session' )->addError ( $e->getMessage () );
				$this->_redirect ( '*/*/edit', array (
						'id' => $this->getRequest ()->getParam ( 'id' ) 
				) );
			}
		}
		$this->_redirect ( '*/*/' );
	}
	
	/**
	 * To delete multiple records in SubscriptionType Table
	 */
	public function massDeleteAction() {
		
		$testIds = $this->getRequest ()->getParam ( 'subscriptiontype' );
		$subscriptionCount =array();
		$subscriptionCount = $testIds;	
		
		if (! is_array ( $testIds )) {
			Mage::getSingleton ( 'adminhtml/session' )->addError ( Mage::helper ( 'adminhtml' )->__ ( 'Please select customer(s)' ) );
		} else {			
			
			try {
				foreach ( $testIds as $testId ) {
					$collection = Mage::getModel('recurringpayments/productsubscriptions')->getCollection();
					$productModel = $collection ->addFieldToFilter('subscription_type',$testIds)
					->addFieldToFilter('is_delete',0)
					->getData();
					//$this->subscriptionProductDelete($productModel);
					$test = Mage::getModel ( 'recurringpayments/subscriptiontype' )->load ( $testId );
					$test->delete ();
				}
				Mage::getSingleton ( 'adminhtml/session' )->addSuccess ( Mage::helper ( 'adminhtml' )->__ ( 'Total of %d record(s) were successfully deleted', count ( $testIds ) ) );
			} catch ( Exception $e ) {
				Mage::getSingleton ( 'adminhtml/session' )->addError ( $e->getMessage () );
			}
		}
		$this->_redirect ( '*/*/index' );
	}
	
	/**
	 * Delete subscription product which have tge recurring as set.
	 * 
	 */
	public function subscriptionDelete($subscripitonCollection){
		$subscription = $collection = $productid = $subscriptionCollectionCount = $subscriptionIds = array ();
		$subscriptionCollectionCount = $subscripitonCollection;
		foreach ( $subscriptionCollectionCount as $value ) {
			$collection = Mage::getModel ( 'recurringpayments/productsubscriptions' )->getCollection ()->addFieldToFilter ( 'subscription_type', $value ['subscription_type'] )->addFieldToFilter ( 'is_delete', 0 )->getData ();
		}
		
		if (count ( $collection ) >= 1) {
			foreach ( $collection as $subcollection ) {
				if (! empty ( $subcollection )) {
					$productid [] = $subcollection ['product_id'];
					$subscription [] = $subcollection ['subscription_type'];
				}
			}			
			$subscriptionIds = array_values ( array_unique ( $subscription ) );
			$productCollection = $subscriptionCollection = $manageCollection = array();
			if (count ( $productid ) > 1) {	
					foreach ( $subscriptionIds as $subscriptionTypeId ) {
						foreach ( $productid as $ids ) {											
						$productCollection = Mage::getModel ( 'recurringpayments/productsubscriptions' )->getCollection ()->addFieldToFilter ( 'product_id', $ids )->addFieldToFilter ( 'is_delete', 0 )->getData ();						
						if (count ( $productCollection ) > 1) {
							$productCollection = Mage::getModel ( 'recurringpayments/productsubscriptions' )->getCollection ()->addFieldToFilter ( 'product_id', $ids )->addFieldToFilter ( 'subscription_type', $subscriptionTypeId )->addFieldToFilter ( 'is_delete', 0 );
							foreach ( $productCollection as $items ) {
								$items->delete ()->save ();
							}
						} else {
							$productCollection = Mage::getModel ( 'recurringpayments/productsubscriptions' )->getCollection ()->addFieldToFilter ( 'product_id', $ids )->addFieldToFilter ( 'subscription_type', $subscriptionTypeId )->addFieldToFilter ( 'is_delete', 0 );
							$manageCollection = Mage::getModel ( 'recurringpayments/managesubscriptions' )->getCollection ()->addFieldToFilter ( 'product_id', $ids );
							$product = array ();
							$product = Mage::getModel ( 'catalog/product' )->load ( $ids );
							if ($product->getIsRecurring ()) {
								$product->setIsRecurring ( '0' );
								$storeId = Mage::app ()->getStore ()->getStoreId ();
								Mage::app ()->setCurrentStore ( Mage_Core_Model_App::ADMIN_STORE_ID );
								$product->save ();
								Mage::app ()->setCurrentStore ( $storeId );
							}
							foreach ( $productCollection as $items ) {
								$items->delete ()->save ();
							}
							foreach ( $manageCollection as $items ) {
								$items->delete ()->save ();
							}										
						}
					}
				}
		
			} else if (count ( $productid ) == 1) {				
				foreach ( $subscriptionIds as $subscriptionTypeId ) {
				foreach ( $productid as $ids ) {					
						$productCollection = Mage::getModel ( 'recurringpayments/productsubscriptions' )->getCollection ()->addFieldToFilter ( 'product_id', $ids )->addFieldToFilter ( 'is_delete', 0 );						
						$collectionCount = count($productCollection->getData());
						if($collectionCount > 1){
							$productCollection = Mage::getModel ( 'recurringpayments/productsubscriptions' )->getCollection ()->addFieldToFilter ( 'product_id', $ids )->addFieldToFilter ( 'subscription_type', $subscriptionTypeId )->addFieldToFilter ( 'is_delete', 0 );
							$manageCollection = Mage::getModel ( 'recurringpayments/managesubscriptions' )->getCollection ()->addFieldToFilter ( 'product_id', $ids );
							foreach ( $productCollection as $items ) {
								$items->delete ()->save ();
							}							
						}else{
							$productCollection = Mage::getModel ( 'recurringpayments/productsubscriptions' )->getCollection ()->addFieldToFilter ( 'product_id', $ids )->addFieldToFilter ( 'subscription_type', $subscriptionTypeId )->addFieldToFilter ( 'is_delete', 0 );
							$manageCollection = Mage::getModel ( 'recurringpayments/managesubscriptions' )->getCollection ()->addFieldToFilter ( 'product_id', $ids );
							$product = array ();
							$product = Mage::getModel ( 'catalog/product' )->load ( $ids );
							if ($product->getIsRecurring ()) {
								$product->setIsRecurring ( '0' );
								$storeId = Mage::app ()->getStore ()->getStoreId ();
								Mage::app ()->setCurrentStore ( Mage_Core_Model_App::ADMIN_STORE_ID );
								$product->save ();
								Mage::app ()->setCurrentStore ( $storeId );
							}
							foreach ( $productCollection as $items ) {
								$items->delete ()->save ();
							}
							foreach ( $manageCollection as $items ) {
								$items->delete ()->save ();
							}
						}						
					}
				}
			} else {
				continue;
			}
		}
		
	}	
	
	
	
}			
		
	

