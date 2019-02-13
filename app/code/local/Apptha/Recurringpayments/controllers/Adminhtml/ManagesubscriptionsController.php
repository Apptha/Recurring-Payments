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
 * Managesubscriptions Controller
 */
class Apptha_Recurringpayments_Adminhtml_ManagesubscriptionsController extends Mage_Adminhtml_Controller_Action {
	
	/**
	 * Load phtml file layout
	 *
	 * @return void
	 */
	public function indexAction() {		
		$this->loadLayout ();
		$this->_setActiveMenu ( 'managesubscriptions/items' );
		$this->renderLayout ();
	}
	/**
	 * Edit product subscription data
	 *
	 * @return void
	 */
	public function editAction() {
		$id = $this->getRequest ()->getParam ( 'id' );
		$model = Mage::getModel ( 'recurringpayments/managesubscriptions' )->load ( $id );
		
		$product_id = $model->getData ( 'product_id' );
		$productModel = Mage::getModel ( 'catalog/product' )->load ( $product_id );
		
		if ($model->getId () || $id == 0) {
			$data = Mage::getSingleton ( 'adminhtml/session' )->getFormData ( true );
			if (! empty ( $data )) {
					$model->setData ( $data );
			}
			Mage::register ( 'managesubscriptionData', $productModel );
			Mage::register ( 'managesubscriptions_id', $product_id );
			Mage::register ( 'managesubscriptions_data', $model );
			$this->loadLayout ();
			$this->_setActiveMenu ( 'managesubscriptions/items' );
			
			$this->_addBreadcrumb ( Mage::helper ( 'adminhtml' )->__ ( 'Item Manager' ), Mage::helper ( 'adminhtml' )->__ ( 'Item Manager' ) );
			$this->_addBreadcrumb ( Mage::helper ( 'adminhtml' )->__ ( 'Item News' ), Mage::helper ( 'adminhtml' )->__ ( 'Item News' ) );
			
			$this->getLayout ()->getBlock ( 'head' )->setCanLoadExtJs ( true );
			
			$this->_addContent ( $this->getLayout ()->createBlock ( 'recurringpayments/adminhtml_managesubscriptions_edit' ) )->_addLeft ( $this->getLayout ()->createBlock ( 'recurringpayments/adminhtml_managesubscriptions_edit_tabs' ) );
			
			$this->renderLayout ();
		} else {
			Mage::getSingleton ( 'adminhtml/session' )->addError ( Mage::helper ( 'managesubscriptions' )->__ ( 'Item does not exist' ) );
			$this->_redirect ( '*/*/' );
		}
	}
	/**
	 * This loads a new grid, name as CreateSubscription in new Action.
	 *
	 * @return void
	 */
	public function newAction() {
		$this->loadLayout ();
		
		$this->_addContent ( $this->getLayout ()->createBlock ( 'managesubscriptions/adminhtml_createsubscriptions' ) );
		
		$this->renderLayout ();
	}
	/**
	 * Its For create subscription grid in addnew action.
	 * it works same as edit action but name has changed as move action in create subscription gird.
	 *
	 * Create subscription type to new product.
	 *
	 * @return void
	 */
	public function moveAction() {
		$productId = $this->getRequest ()->getParam ( 'id' );
		$productModel = Mage::getModel ( 'catalog/product' )->load ( $productId );
		if ($productModel->getId () || $productId == 0) {
			$data = Mage::getSingleton ( 'adminhtml/session' )->getFormData ( true );
			
			if (! empty ( $data )) {
				$productModel->setData ( $data );
			}
			
			Mage::register ( 'createsubscriptions_data', $productModel );
			
			$this->loadLayout ();
			$this->_setActiveMenu ( 'managesubscriptions/items' );
			
			$this->_addBreadcrumb ( Mage::helper ( 'adminhtml' )->__ ( 'Item Manager' ), Mage::helper ( 'adminhtml' )->__ ( 'Item Manager' ) );
			$this->_addBreadcrumb ( Mage::helper ( 'adminhtml' )->__ ( 'Item News' ), Mage::helper ( 'adminhtml' )->__ ( 'Item News' ) );
			
			$this->getLayout ()->getBlock ( 'head' )->setCanLoadExtJs ( true );
			
			$this->_addContent ( $this->getLayout ()->createBlock ( 'recurringpayments/adminhtml_createsubscriptions_edit' ) )->_addLeft ( $this->getLayout ()->createBlock ( 'recurringpayments/adminhtml_createsubscriptions_edit_tabs' ) );
			
			$this->renderLayout ();
		} else {
			Mage::getSingleton ( 'adminhtml/session' )->addError ( Mage::helper ( 'recurringpayments' )->__ ( 'Item does not exist' ) );
			$this->_redirect ( '*/*/' );
		}
	}
	
	/**
	 * This saves the product with its assigned subscription type .
	 *
	 *
	 *
	 * Save subscriptions data and change the status
	 *
	 * @return void
	 */
	public function saveAction() {
		if ($data = $this->getRequest ()->getPost ()) {
			
			$productId = $data ['product_id'];
			$product = Mage::getModel ( 'catalog/product' )->load ( $productId );
			$allProduct=Mage::getModel ( 'recurringpayments/managesubscriptions' )->getCollection ()
						->addFieldToFilter('product_id',$productId)
						->getdata();			
		  $_productName= $product->getName(); 
			if(!empty($allProduct)){				
				foreach($allProduct as $manageProduct){							
					if($manageProduct['product_id'] != $productId){					
						if( $product->getIsRecurring()){							
							$productStatus=1;			
						}
						else{							
							$productStatus=0;				
						}
					}
					else{						
						$productStatus=$manageProduct['product_status'];						
						break;
					} 			
				}
			}else{
				$productStatus = 1;
			}
			
			if (! $product->getIsRecurring ()) {
				$product->setIsRecurring ( '1' );
				$recurringProfile ['period_frequency'] = 1;
				$recurringProfile ['period_unit'] = "day";
				$recurringProfile ['trial_billing_amount'] = 1;
				$recurringProfile ['init_amount'] = 1;
				$product->setRecurringProfile ( $recurringProfile );
				$storeId = Mage::app ()->getStore ()->getStoreId ();
				Mage::app ()->setCurrentStore ( Mage_Core_Model_App::ADMIN_STORE_ID );
				$product->save ();
				Mage::app ()->setCurrentStore ( $storeId );
			}
			
			 $isSubscriptionOnly = $startDate = null;
			
			$trialPeriodPrice = $initialFee = $subscriptionType = $pricePerIteration = array();
			$isSubscriptionOnly = $data ['is_subscription_only'];
			$startDate = $data ['start_date'];			
			if (! empty ( $data ['trial_period_price'] )) {
				$trialPeriodPrice = $data ['trial_period_price'];
			}
			if (! empty ( $data ['initial_fee'] )) {
				$initialFee = $data ['initial_fee'];
			}
			$subscriptionType = $data ['subscription_type'];
			$maximumValue = sizeof ( $subscriptionType );
			$pricePerIteration = $data ['price_per_iteration'];
			
			$productcollection = $collection = array();
			$productcollection = Mage::getModel ( 'recurringpayments/productsubscriptions' )->getCollection ()->addFieldToFilter ( 'is_delete', array (
					'eq' => '0' 
			) );
			$collection = $productcollection->getData ();
			$productIdValue = $id = $isDelete = array();
			for($i = 0; $i < count ( $collection ); $i ++) {
				$id = $collection [$i] ['id'];
				$isDelete = $collection [$i] ['is_delete'];
				$productIdValue = $collection [$i] ['product_id'];
				if (($this->getRequest ()->getPost ( 'product_id' ) == $productIdValue) && ($isDelete == 0)) {
					
					$productcollections = Mage::getModel ( 'recurringpayments/productsubscriptions' )->load ( $id );
					$productcollections->setIsDelete ( '1' );
					$productcollections->save ();
				}
			}
			$initialFeePrice =  $trialPrice =  $trialPriceValue = $inititalFeeValue = $pricePerIterationValue = $pricePerIterationValue = '';
			$productModel = array();
			for($i = 1; $i < $maximumValue; $i ++) {
				$productModel = Mage::getModel ( 'recurringpayments/productsubscriptions' );
				
				$subscriptionTypeList = $subscriptionType [$i];
				
				$pricePerIterationValue = $pricePerIteration [$i];
				
				if (! empty ( $data ['initial_fee'] )) {
					
					$initialFeePrice = $initialFee [$i];
				}
				if (! empty ( $data ['trial_period_price'] )) {
					$trialPrice = $trialPeriodPrice [$i];
				}			
				$productModel->setData ( 'product_id', $productId );
				$productModel->setData ( 'subscription_type', $subscriptionTypeList );
				$productModel->setData ( 'is_subscription_only', $isSubscriptionOnly );
				
				if (! empty ( $data ['trial_period_price'] )) {
					$productModel->setData ( 'trial_period_price', $trialPrice );
				}
				$productModel->setData ( 'price_per_iteration', $pricePerIterationValue );
				if (! empty ( $data ['initial_fee'] )) {
					$productModel->setData ( 'initial_fee', $initialFeePrice );
				}
				
				$productModel->save ();
				$values = array (
						'$subscriptionTypeList' => $subscriptionType [$i],
						'$price' => $pricePerIteration [$i]
				);				
				unset ( $values );
				if (! empty ( $data ['initial_fee'] )) {
						
					$initialFeePrice = $initialFee [$i];
					
					$initialFeePrice = null;
				}
				if (! empty ( $data ['trial_period_price'] )) {
					$trialPrice = $trialPeriodPrice [$i];
					
					$trialPrice = null;
				}
				
				/* if (! empty ( $data ['trial_period_price'] )) {
					$trialPriceValue = array (
							'$trial_period_price ' => $trailPrice 
					);
					
					$trialPriceValue = null;
				}
				if (! empty ( $data ['initial_fee'] )) {
					$inititalFeeValue = array (
							'$initialFeePrice' => $initialFeePrice 
					);
					$inititalFeeValue = null;
				} */
			}	
			
			try {
				$productCollection = Mage::getModel ( "catalog/product" )->load ( $productId );
				$manageModel = $manageSubscriptionsModel = array();
				$manageProductId = $manageSubscriptionsModel = '';
				$manageSubscriptionsModel = Mage::getModel ( 'recurringpayments/managesubscriptions' )->getCollection ();
				$manageModel = $manageSubscriptionsModel->getData ();							
				
				if (! empty ( $manageModel )) {
					$urlProductId = $this->getRequest ()->getPost ( 'product_id' );					
					$manageModel = Mage::getModel ( 'recurringpayments/managesubscriptions' )->getCollection ()
									->addFieldToFilter ( 'product_id', array ('eq' => $urlProductId) )->getData ();						
					foreach ( $manageModel as $manageModelData ) {
						$manageProductId = $manageModelData ['product_id'];
					}					
					if ($manageProductId == $urlProductId) {
						$model = Mage::getModel ( 'recurringpayments/managesubscriptions' )->load ( $urlProductId, 'product_id' );						
						$model->setData ( 'product_id', $productId );
						$model->setData('product_name',$_productName);
						$model->setData ( 'is_subscription_only', $isSubscriptionOnly );
						$model->setData ( 'start_date', $startDate );	
						$model->setData ( 'product_status', $productStatus );
						//print_r($model);
						$model->save ();
					} else {
						$model = Mage::getModel ( 'recurringpayments/managesubscriptions' );
						$model->setData ( 'product_id', $productId );
						$model->setData('product_name',$_productName);
						$model->setData ( 'is_subscription_only', $isSubscriptionOnly );
						$model->setData ( 'start_date', $startDate );
						$model->setData ( 'product_status', $productStatus );
						//print_r($model);
						$model->save ();
					}
				} else {
					$model = Mage::getModel ( 'recurringpayments/managesubscriptions' );						
					$model->setData ( 'product_id', $productId );	
					$model->setData('product_name',$_productName);
					$model->setData ( 'is_subscription_only', $isSubscriptionOnly );
					$model->setData ( 'start_date', $startDate );
					$model->setData ( 'product_status', $productStatus );
					print_r($model);
					$model->save ();
				}
				
				if ($i == ($maximumValue - 1)) {
					
					Mage::getSingleton ( 'adminhtml/session' )->addSuccess ( Mage::helper ( 'recurringpayments' )->__ ( 'Item was successfully saved' ) );
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
		} else {
			
			Mage::getSingleton ( 'adminhtml/session' )->addError ( Mage::helper ( 'recurringpayments' )->__ ( 'Unable to find item to save' ) );
			$this->_redirect ( '*/*/' );
		}
	}
	
	/**
	 * Delete a product subscription data.
	 *
	 * @return void
	 */
	public function deleteAction() {
		if ($this->getRequest ()->getParam ( 'id' ) > 0) {
			try {
				$productId = $id = null;
				$id = $this->getRequest ()->getParam ( 'id' );
				$manageCollection = Mage::getModel ( 'recurringpayments/managesubscriptions' )->load ( $id );
				$productId = $manageCollection->getProductId ();
				$product = Mage::getModel ( 'catalog/product' )->load ( $productId );
				if ($product->getIsRecurring ()) {
					$product->setIsRecurring ( '0' );
					$storeId = Mage::app ()->getStore ()->getStoreId ();
					Mage::app ()->setCurrentStore ( Mage_Core_Model_App::ADMIN_STORE_ID );
					$product->save ();
					Mage::app ()->setCurrentStore ( $storeId );
				}
				$model = Mage::getModel ( 'recurringpayments/managesubscriptions' );
				
				$model->setId ( $this->getRequest ()->getParam ( 'id' ) )->delete ();
				
				Mage::getSingleton ( 'adminhtml/session' )->addSuccess ( Mage::helper ( 'adminhtml' )->__ ( 'Item was successfully deleted' ) );
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
}