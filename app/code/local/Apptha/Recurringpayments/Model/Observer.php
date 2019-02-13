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
 * @version     1.0
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2014 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 */

/**
 * Event Observer
 */
class Apptha_Recurringpayments_Model_Observer {
	
	/**
	 * Set product to recurring while adding to cart
	 *
	 * @param object $observer        	
	 */
	public function addToCartEvent($observer) {
		$_helper = Mage::helper ( 'recurringpayments' );
		if (Mage::getStoreConfigFlag ( 'recurringpayments/settings/active' ) && $_helper->checkSubscriptionAndRecurringPaymentsKey ()) {
			if ($observer->getEvent ()->getControllerAction ()->getFullActionName () == 'checkout_cart_add' || $observer->getEvent ()->getControllerAction ()->getFullActionName () == 'catalog_product_view') {
				
				$productId = '';
				$options = array ();
				Mage::getSingleton ( 'checkout/session' )->setAppthaSubscriptionProductId ( $productId );
				Mage::getSingleton ( 'checkout/session' )->setAppthaSubscriptionOptions ( $options );
				
				$productId = Mage::app ()->getRequest ()->getParam ( 'product', 0 );
				$options = Mage::app ()->getRequest ()->getParam ( 'recurringoptions' );
				
				$manageSubscriptions = Mage::getModel ( 'recurringpayments/managesubscriptions' )->getCollection ()->addFieldToFilter ( 'product_id', $productId )->addFieldToFilter ( 'is_subscription_only', 1 );
				
				if (count ( $manageSubscriptions )) {
					
					$product = Mage::getModel ( 'catalog/product' )->load ( $productId );
					$_actualPrice = $product->getPrice();
					$_specialPrice = $product->getFinalPrice();
					$_finalPrice=$_actualPrice-$_specialPrice;
					Mage::getSingleton ( 'checkout/session' )->setfinalPrice ( $_finalPrice );
					if (count ( $options ) <= 0) {
						
						Mage::app ()->getFrontController ()->getResponse ()->setRedirect ( $product->getProductUrl () );
						Mage::app ()->getResponse ()->sendResponse ();
						
						$controller = $observer->getControllerAction ();
						$controller->getRequest ()->setDispatched ( true );
						$controller->setFlag ( '', Mage_Core_Controller_Front_Action::FLAG_NO_DISPATCH, true );
					}
					
					/**
					 * Checking whether product recurring or not
					 */
					if (! $product->getIsRecurring ()) {
						$product->setIsRecurring ( '1' );
						$storeId = Mage::app ()->getStore ()->getStoreId ();
						Mage::app ()->setCurrentStore ( Mage_Core_Model_App::ADMIN_STORE_ID );
						$product->save ();
						Mage::app ()->setCurrentStore ( $storeId );
					}
					
					/**
					 * Setting subscription option
					 */
					Mage::getSingleton ( 'checkout/session' )->setAppthaSubscriptionProductId ( $productId );
					Mage::getSingleton ( 'checkout/session' )->setAppthaSubscriptionOptions ( $options );
				}
			}
			return $this;
		}
	}
	/**
	 * Set product to recurring while loading a product if product have recurring feature.
	 *
	 * @param object $observer        	
	 */
	public function checkRecurring(Varien_Event_Observer $observer) {
		$isRecurringdetails = $productStatus = '';
		$product = $observer->getData ();
		foreach ( $product as $productDetails ) {
			$isRecurringdetails = $productDetails->getIsRecurring ();
			$productId = $productDetails->getEntityId ();
		}
		$managecollection = Mage::getModel ( 'recurringpayments/managesubscriptions' )->load ( $productId, 'product_id' )->getData ();
		if (! empty ( $managecollection )) {
			$productStatus = $managecollection ['product_status'];
		}
		$_helper = Mage::helper ( 'recurringpayments' );
		$productCollection = Mage::getModel ( 'catalog/product' )->load ( $productId );
		
		if (Mage::getStoreConfigFlag ( 'recurringpayments/settings/active' ) && $_helper->checkSubscriptionAndRecurringPaymentsKey ()) {
			if (! empty ( $productStatus )) {
				if ($productStatus == 1) {
					if ($isRecurringdetails == $productStatus) {
						$productCollection->setIsRecurring ( '1' );
						$storeId = Mage::app ()->getStore ()->getStoreId ();
						Mage::app ()->setCurrentStore ( Mage_Core_Model_App::ADMIN_STORE_ID );
						$productCollection->save ();
						Mage::app ()->setCurrentStore ( $storeId );
					}
				}
			} else {
				if ($isRecurringdetails == 1) {
					$productCollection->setIsRecurring ( '1' );
					$storeId = Mage::app ()->getStore ()->getStoreId ();
					Mage::app ()->setCurrentStore ( Mage_Core_Model_App::ADMIN_STORE_ID );
					$productCollection->save ();
					Mage::app ()->setCurrentStore ( $storeId );
				}
			}
		} else {
			if (! empty ( $managecollection )) {
				if ($productStatus == 1) {
					$productCollection->setIsRecurring ( '0' );
					$storeId = Mage::app ()->getStore ()->getStoreId ();
					Mage::app ()->setCurrentStore ( Mage_Core_Model_App::ADMIN_STORE_ID );
					$productCollection->save ();
					Mage::app ()->setCurrentStore ( $storeId );
				}
			} else {
				if ($isRecurringdetails == 1) {
					$productCollection->setIsRecurring ( '1' );
					$storeId = Mage::app ()->getStore ()->getStoreId ();
					Mage::app ()->setCurrentStore ( Mage_Core_Model_App::ADMIN_STORE_ID );
					$productCollection->save ();
					Mage::app ()->setCurrentStore ( $storeId );
				} else {
					$productCollection->setIsRecurring ( '0' );
					$storeId = Mage::app ()->getStore ()->getStoreId ();
					Mage::app ()->setCurrentStore ( Mage_Core_Model_App::ADMIN_STORE_ID );
					$productCollection->save ();
					Mage::app ()->setCurrentStore ( $storeId );
				}
			}
		}
	}
	
	/**
	 * Update cart price
	 *
	 * @param object $observer        	
	 */
	public function updateCartPrice($observer) {
		$_helper = Mage::helper ( 'recurringpayments' );
		if (Mage::getStoreConfigFlag ( 'recurringpayments/settings/active' ) && $_helper->checkSubscriptionAndRecurringPaymentsKey ()) {
			$productId = Mage::getSingleton ( 'checkout/session' )->getAppthaSubscriptionProductId ();
			$options = Mage::getSingleton ( 'checkout/session' )->getAppthaSubscriptionOptions ();
			
			if (! empty ( $productId ) && count ( $options ) >= 1) {
				if (isset ( $options [0] )) {
					
					$productSubscriptionId = $options [0];
					
					$productSubscription = Mage::getModel ( 'recurringpayments/productsubscriptions' )->getCollection ()->addFieldToFilter ( 'is_subscription_only', 1 )->addFieldToFilter ( 'is_delete', 0 );
					$tablePrefix = Mage::getConfig ()->getTablePrefix ();
					$productSubscription->getSelect ()->join ( $tablePrefix . 'apptha_subscriptiontypes', 'main_table.subscription_type =' . $tablePrefix . 'apptha_subscriptiontypes.id AND main_table.id = ' . $productSubscriptionId, array (
							'main_table.id as product_subscription_id',
							'main_table.subscription_type as subscription_type',
							$tablePrefix . 'apptha_subscriptiontypes.title as title',
							$tablePrefix . 'apptha_subscriptiontypes.billing_frequency as billing_frequency',
							$tablePrefix . 'apptha_subscriptiontypes.billing_cycle as billing_cycle',
							$tablePrefix . 'apptha_subscriptiontypes.billing_period_unit as billing_period_unit',
							'main_table.price_per_iteration as price_per_iteration' 
					) );
					foreach ( $productSubscription as $subscription ) {
						if ($subscription->getPricePerIteration ()) {
							$price = $subscription->getPricePerIteration ();
							$baseCurrencyCode = Mage::app ()->getStore ()->getBaseCurrencyCode ();
							$currentCurrencyCode = Mage::app ()->getStore ()->getCurrentCurrencyCode ();
							// convert price from base currency to current currency
							$currentPrice = Mage::helper ( 'directory' )->currencyConvert ( $price, $baseCurrencyCode, $currentCurrencyCode );
							$finalPrice=Mage::getSingleton ( 'checkout/session' )->getfinalPrice ();
							$event = $observer->getEvent ();
							$quoteItem = $event->getQuoteItem ();
							$quoteItem->setOriginalCustomPrice ( $currentPrice-$finalPrice );
												
							break;
						}
					}
				}
			}
		}
	}
	/**
	 * Set the nominal price after adding product to cart
	 *
	 * @param object $observer        	
	 */
	public function addToCartEventAfter($observer) {
		$_helper = Mage::helper ( 'recurringpayments' );
		if (Mage::getStoreConfigFlag ( 'recurringpayments/settings/active' ) && $_helper->checkSubscriptionAndRecurringPaymentsKey ()) {
			$productId = Mage::getSingleton ( 'checkout/session' )->getAppthaSubscriptionProductId ();
			$options = Mage::getSingleton ( 'checkout/session' )->getAppthaSubscriptionOptions ();
			
			$shippingRate = null;
			if (! empty ( $productId ) && count ( $options ) >= 1) {
				if (isset ( $options [0] )) {
					$baseCurrencyCode = Mage::app ()->getStore ()->getBaseCurrencyCode ();
					$currentCurrencyCode = Mage::app ()->getStore ()->getCurrentCurrencyCode ();
					// convert price from base currency to current currency
					$trialAmount = Mage::getSingleton ( 'checkout/session' )->getTrialPrice ();
					$trialAmount = Mage::helper ( 'directory' )->currencyConvert ( $trialAmount, $baseCurrencyCode, $currentCurrencyCode );
					$trialEnabled = Mage::getSingleton ( 'checkout/session' )->getTrialEnabled ();
					
					$initialFeeEnabled = Mage::getSingleton ( 'checkout/session' )->getInitialFeeEnabled ();
					$initialFee = Mage::getSingleton ( 'checkout/session' )->getInitialFee ();
					$initialFee = Mage::helper ( 'directory' )->currencyConvert ( $initialFee, $baseCurrencyCode, $currentCurrencyCode );
					$cart = $observer->getEvent ()->getCart ();
					$quote = $cart->getQuote ();
					foreach ( $quote->getAllItems () as $items ) {
						if ($items->getProduct ()->isRecurring ()) {
							
							$details = $items->getNominalTotalDetails ();
							foreach ( $details as $totalDetails ) {
								if ($totalDetails->getLabel () == Mage::helper ( 'recurringpayments' )->__ ( 'Shipping' )) {
									$shippingRate = $totalDetails->getAmount ();
								}
								if ($totalDetails->getLabel () == Mage::helper ( 'recurringpayments' )->__ ( 'Regular Payment' )) {
									$regularAmt = $totalDetails->getAmount ();
								}
							}
							foreach ( $details as $total ) {
								
								if ($total->getLabel () == Mage::helper ( 'recurringpayments' )->__ ( 'Initial Fee' )) {
									if ($initialFeeEnabled) {
										$total->setAmount ( $initialFee );
										if (! $trialEnabled) {
											$items->setNominalRowTotal ( $initialFee + $shippingRate + $regularAmt );
										}
									} else {
										$total->setAmount ( 0 );
									}
								} 

								else if ($total->getLabel () == Mage::helper ( 'recurringpayments' )->__ ( 'Trial Payment' )) {
									if ($trialAmount && $trialEnabled) {
										$total->setAmount ( $trialAmount );
										$items->setNominalRowTotal ( $trialAmount );
										if ($initialFeeEnabled) {
											$totalfee = $trialAmount + $initialFee;
											$items->setNominalRowTotal ( $totalfee + $shippingRate );
										}
										break;
									} else {
										$total->setAmount ( 0 );
									}
								} else {
									if (! $initialFeeEnabled && $total->getLabel () == Mage::helper ( 'recurringpayments' )->__ ( 'Regular Payment' )) {
										$totalAmount = $total->getAmount ();
										$items->setNominalRowTotal ( $totalAmount + $shippingRate );
									}
								}
							}
							$items->save ();
						}
						$quote->save ();
					}
				}
			}
			
			return $this;
		}
	}
	
	
	/**
	 * add cron job for update the paypal recurring profile
	 */
	public function eventVacationMode(){
		 
		$currentTimestamp = time();
		$statusExpired = 'Expired';			
		$this->eventActive();
		$this->eventCanceled();
		$this->eventSuspend();
		$recurringCollection = Mage::getModel ( 'sales/recurring_profile' )->getCollection ()->addFieldToFilter ( 'state', $statusExpired );		
		foreach ($recurringCollection as $recurringCollections){
			//$referenceid=$recurringCollections['reference_id'];
			$referenceid=$recurringCollections['reference_id'];
			$nvpArr = array_merge(array('PROFILEID'=>$referenceid));
			$api=Mage::getModel('paypal/api_nvp');
			$api->business= Mage::getStoreConfig('paypal/general/business_account');
			$api->api_username = Mage::getStoreConfig('paypal/wpp/api_username');
			$api->api_password =  Mage::getStoreConfig('paypal/wpp/api_password');
			$api->api_signature =  Mage::getStoreConfig('paypal/wpp/api_signature');
			$api->version ='65.0';
			$sandbox = Mage::getStoreConfig('payment/paypal_standard/sandbox_flag');
			if($sandbox == 1){
				$api->endpoint="https://api-3t.sandbox.paypal.com/nvp";
			}else{
				$api->endpoint="https://api-3t.paypal.com/nvp";
			}
			$resArr = $api->_call('GetRecurringPaymentsProfileDetails', $nvpArr);
			$status=$resArr['STATUS'];
			$maximumfailedpayments = $resArr['MAXFAILEDPAYMENTS'];
			$profilestartdate = $resArr['PROFILESTARTDATE'];
			$nextbillingdate = $resArr['NEXTBILLINGDATE'];
			$numofcyclescompleted = $resArr['NUMCYCLESCOMPLETED'];
			$nomofcyclesremaining = $resArr['NUMCYCLESREMAINING'];
			$outstandingbalane = $resArr['OUTSTANDINGBALANCE'];
			$failedpaymentcount = $resArr['FAILEDPAYMENTCOUNT'];
			$lastpaymentdate = $resArr['LASTPAYMENTDATE'];
			$lastpaymentamount = $resArr['LASTPAYMENTAMT'];
			$regularamountpaid = $resArr['REGULARAMTPAID'];
			$billingperiod = $resArr['BILLINGPERIOD'];
			$billingfrequency = $resArr['BILLINGFREQUENCY'];
			$totalbillingcycles = $resArr['TOTALBILLINGCYCLES'];
			$regularamount = $resArr['REGULARAMT'];
			$data = array('status'=>$status,'maximum_failed_payments'=>$maximumfailedpayments,'profile_start_date'=>$profilestartdate,'next_billing_date'=>$nextbillingdate,'numof_cycles_completed'=>$numofcyclescompleted,'numof_cycles_remaining'=>$nomofcyclesremaining,'outstanding_balane'=>$outstandingbalane,'failed_payment_count'=>$failedpaymentcount,'last_payment_date'=>$lastpaymentdate,'last_payment_amount'=>$lastpaymentamount,'regular_amount_paid'=>$regularamountpaid,'billing_period'=>$billingperiod,'billing_frequency'=>$billingfrequency,'total_billing_cycles'=>$totalbillingcycles,'regular_amount'=>$regularamount);
			//$data = array('status'=>$status,'maximum_failed_payments'=>$maximumfailedpayments,'profile_start_date'=>$profilestartdate,'next_billing_date'=>$nextbillingdate,'numof_cycles_completed'=>$numofcyclescompleted,'numof_cycles_remaining'=>$nomofcyclesremaining,'outstanding_balane'=>$outstandingbalane,'failed_payment_count'=>$failedpaymentcount,'last_payment_date'=>$lastpaymentdate,'last_payment_amount'=>$lastpaymentamount,'regular_amount_paid'=>$regularamountpaid,'billing_period'=>$billingperiod,'billing_frequency'=>$billingfrequency,'total_billing_cycles'=>$totalbillingcycles,'regular_amount'=>$regularamount);
			$expiredId = $recurringCollections['profile_id'];
			$model = Mage::getModel('recurringpayments/recurringprofiles')->load($expiredId)->addData($data);			
			try {
				$model->save();
				echo "Data updated successfully.";
			} catch (Exception $e){
				echo $e->getMessage();
			}
		}
	}
	
	/**
	 * add cron job for find the cancelled paypal profils.
	 */
	public function eventCanceled(){
			
		$currentTimestamp = time();		
		$statusCanceled = 'canceled';
		$recurringCollection = Mage::getModel ( 'sales/recurring_profile' )->getCollection ()->addFieldToFilter ( 'state', $statusCanceled );	
		echo "<pre>";
		print_r($recurringCollection->getData());
		foreach ($recurringCollection as $recurringCollections){
			//$referenceid=$recurringCollections['reference_id'];
			$referenceid=$recurringCollections['reference_id'];
			$nvpArr = array_merge(array('PROFILEID'=>$referenceid));
			$api=Mage::getModel('paypal/api_nvp');
			$api->business= Mage::getStoreConfig('paypal/general/business_account');
			$api->api_username = Mage::getStoreConfig('paypal/wpp/api_username');
			$api->api_password =  Mage::getStoreConfig('paypal/wpp/api_password');
			$api->api_signature =  Mage::getStoreConfig('paypal/wpp/api_signature');
			$api->version ='65.0';
			$sandbox = Mage::getStoreConfig('payment/paypal_standard/sandbox_flag');
			if($sandbox == 1){
				$api->endpoint="https://api-3t.sandbox.paypal.com/nvp";
			}else{
				$api->endpoint="https://api-3t.paypal.com/nvp";
			}
			$resArr = $api->_call('GetRecurringPaymentsProfileDetails', $nvpArr);
			$status=$resArr['STATUS'];
			$maximumfailedpayments = $resArr['MAXFAILEDPAYMENTS'];
			$profilestartdate = $resArr['PROFILESTARTDATE'];
			$nextbillingdate = $resArr['NEXTBILLINGDATE'];
			$numofcyclescompleted = $resArr['NUMCYCLESCOMPLETED'];
			$nomofcyclesremaining = $resArr['NUMCYCLESREMAINING'];
			$outstandingbalane = $resArr['OUTSTANDINGBALANCE'];
			$failedpaymentcount = $resArr['FAILEDPAYMENTCOUNT'];
			$lastpaymentdate = $resArr['LASTPAYMENTDATE'];
			$lastpaymentamount = $resArr['LASTPAYMENTAMT'];
			$regularamountpaid = $resArr['REGULARAMTPAID'];
			$billingperiod = $resArr['BILLINGPERIOD'];
			$billingfrequency = $resArr['BILLINGFREQUENCY'];
			$totalbillingcycles = $resArr['TOTALBILLINGCYCLES'];
			$regularamount = $resArr['REGULARAMT'];
			$data = array('status'=>$status,'maximum_failed_payments'=>$maximumfailedpayments,'profile_start_date'=>$profilestartdate,'next_billing_date'=>$nextbillingdate,'numof_cycles_completed'=>$numofcyclescompleted,'numof_cycles_remaining'=>$nomofcyclesremaining,'outstanding_balane'=>$outstandingbalane,'failed_payment_count'=>$failedpaymentcount,'last_payment_date'=>$lastpaymentdate,'last_payment_amount'=>$lastpaymentamount,'regular_amount_paid'=>$regularamountpaid,'billing_period'=>$billingperiod,'billing_frequency'=>$billingfrequency,'total_billing_cycles'=>$totalbillingcycles,'regular_amount'=>$regularamount);
			//$data = array('status'=>$status,'maximum_failed_payments'=>$maximumfailedpayments,'profile_start_date'=>$profilestartdate,'next_billing_date'=>$nextbillingdate,'numof_cycles_completed'=>$numofcyclescompleted,'numof_cycles_remaining'=>$nomofcyclesremaining,'outstanding_balane'=>$outstandingbalane,'failed_payment_count'=>$failedpaymentcount,'last_payment_date'=>$lastpaymentdate,'last_payment_amount'=>$lastpaymentamount,'regular_amount_paid'=>$regularamountpaid,'billing_period'=>$billingperiod,'billing_frequency'=>$billingfrequency,'total_billing_cycles'=>$totalbillingcycles,'regular_amount'=>$regularamount);
			$expiredId = $recurringCollections['profile_id'];
			$model = Mage::getModel('recurringpayments/recurringprofiles')->load($expiredId)->addData($data);
			try {
				$model->save();
				echo "Data updated successfully.";
			} catch (Exception $e){
				echo $e->getMessage();
			}
		}
	}
	
	/**
	 * add cron job for find the Active paypal profils.
	 */
	public function eventActive(){
			
		$currentTimestamp = time();
		$statusActive = 'active';
		$recurringCollection = Mage::getModel ( 'sales/recurring_profile' )->getCollection ()->addFieldToFilter ( 'state', $statusActive );
		echo "<pre>";
		print_r($recurringCollection->getData());
		foreach ($recurringCollection as $recurringCollections){
			//$referenceid=$recurringCollections['reference_id'];
			$referenceid=$recurringCollections['reference_id'];
			$nvpArr = array_merge(array('PROFILEID'=>$referenceid));
			$api=Mage::getModel('paypal/api_nvp');
			$api->business= Mage::getStoreConfig('paypal/general/business_account');
			$api->api_username = Mage::getStoreConfig('paypal/wpp/api_username');
			$api->api_password =  Mage::getStoreConfig('paypal/wpp/api_password');
			$api->api_signature =  Mage::getStoreConfig('paypal/wpp/api_signature');
			$api->version ='65.0';
			$sandbox = Mage::getStoreConfig('payment/paypal_standard/sandbox_flag');
			if($sandbox == 1){
				$api->endpoint="https://api-3t.sandbox.paypal.com/nvp";
			}else{
				$api->endpoint="https://api-3t.paypal.com/nvp";
			}
			$resArr = $api->_call('GetRecurringPaymentsProfileDetails', $nvpArr);
			$status=$resArr['STATUS'];
			$maximumfailedpayments = $resArr['MAXFAILEDPAYMENTS'];
			$profilestartdate = $resArr['PROFILESTARTDATE'];
			$nextbillingdate = $resArr['NEXTBILLINGDATE'];
			$numofcyclescompleted = $resArr['NUMCYCLESCOMPLETED'];
			$nomofcyclesremaining = $resArr['NUMCYCLESREMAINING'];
			$outstandingbalane = $resArr['OUTSTANDINGBALANCE'];
			$failedpaymentcount = $resArr['FAILEDPAYMENTCOUNT'];
			$lastpaymentdate = $resArr['LASTPAYMENTDATE'];
			$lastpaymentamount = $resArr['LASTPAYMENTAMT'];
			$regularamountpaid = $resArr['REGULARAMTPAID'];
			$billingperiod = $resArr['BILLINGPERIOD'];
			$billingfrequency = $resArr['BILLINGFREQUENCY'];
			$totalbillingcycles = $resArr['TOTALBILLINGCYCLES'];
			$regularamount = $resArr['REGULARAMT'];
			$data = array('status'=>$status,'maximum_failed_payments'=>$maximumfailedpayments,'profile_start_date'=>$profilestartdate,'next_billing_date'=>$nextbillingdate,'numof_cycles_completed'=>$numofcyclescompleted,'numof_cycles_remaining'=>$nomofcyclesremaining,'outstanding_balane'=>$outstandingbalane,'failed_payment_count'=>$failedpaymentcount,'last_payment_date'=>$lastpaymentdate,'last_payment_amount'=>$lastpaymentamount,'regular_amount_paid'=>$regularamountpaid,'billing_period'=>$billingperiod,'billing_frequency'=>$billingfrequency,'total_billing_cycles'=>$totalbillingcycles,'regular_amount'=>$regularamount);
			//$data = array('status'=>$status,'maximum_failed_payments'=>$maximumfailedpayments,'profile_start_date'=>$profilestartdate,'next_billing_date'=>$nextbillingdate,'numof_cycles_completed'=>$numofcyclescompleted,'numof_cycles_remaining'=>$nomofcyclesremaining,'outstanding_balane'=>$outstandingbalane,'failed_payment_count'=>$failedpaymentcount,'last_payment_date'=>$lastpaymentdate,'last_payment_amount'=>$lastpaymentamount,'regular_amount_paid'=>$regularamountpaid,'billing_period'=>$billingperiod,'billing_frequency'=>$billingfrequency,'total_billing_cycles'=>$totalbillingcycles,'regular_amount'=>$regularamount);
			$expiredId = $recurringCollections['profile_id'];
			$model = Mage::getModel('recurringpayments/recurringprofiles')->load($expiredId)->addData($data);
			try {
				$model->save();
				echo "Data updated successfully.";
			} catch (Exception $e){
				echo $e->getMessage();
			}
		}
	}
	
	/**
	 * add cron job for find the suspended paypal profils.
	 */
	public function eventSuspend(){			
		$currentTimestamp = time();
		$statusSuspend = 'suspended';
		$recurringCollection = Mage::getModel ( 'sales/recurring_profile' )->getCollection ()->addFieldToFilter ( 'state', $statusSuspend );		
		foreach ($recurringCollection as $recurringCollections){
			//$referenceid=$recurringCollections['reference_id'];
			$referenceid=$recurringCollections['reference_id'];
			$nvpArr = array_merge(array('PROFILEID'=>$referenceid));
			$api=Mage::getModel('paypal/api_nvp');
			$api->business= Mage::getStoreConfig('paypal/general/business_account');
			$api->api_username = Mage::getStoreConfig('paypal/wpp/api_username');
			$api->api_password =  Mage::getStoreConfig('paypal/wpp/api_password');
			$api->api_signature =  Mage::getStoreConfig('paypal/wpp/api_signature');
			$api->version ='65.0';
			$sandbox = Mage::getStoreConfig('payment/paypal_standard/sandbox_flag');
			if($sandbox == 1){
				$api->endpoint="https://api-3t.sandbox.paypal.com/nvp";
			}else{
				$api->endpoint="https://api-3t.paypal.com/nvp";
			}
			$resArr = $api->_call('GetRecurringPaymentsProfileDetails', $nvpArr);
			$status=$resArr['STATUS'];
			$maximumfailedpayments = $resArr['MAXFAILEDPAYMENTS'];
			$profilestartdate = $resArr['PROFILESTARTDATE'];
			$nextbillingdate = $resArr['NEXTBILLINGDATE'];
			$numofcyclescompleted = $resArr['NUMCYCLESCOMPLETED'];
			$nomofcyclesremaining = $resArr['NUMCYCLESREMAINING'];
			$outstandingbalane = $resArr['OUTSTANDINGBALANCE'];
			$failedpaymentcount = $resArr['FAILEDPAYMENTCOUNT'];
			$lastpaymentdate = $resArr['LASTPAYMENTDATE'];
			$lastpaymentamount = $resArr['LASTPAYMENTAMT'];
			$regularamountpaid = $resArr['REGULARAMTPAID'];
			$billingperiod = $resArr['BILLINGPERIOD'];
			$billingfrequency = $resArr['BILLINGFREQUENCY'];
			$totalbillingcycles = $resArr['TOTALBILLINGCYCLES'];
			$regularamount = $resArr['REGULARAMT'];
			$data = array('status'=>$status,'maximum_failed_payments'=>$maximumfailedpayments,'profile_start_date'=>$profilestartdate,'next_billing_date'=>$nextbillingdate,'numof_cycles_completed'=>$numofcyclescompleted,'numof_cycles_remaining'=>$nomofcyclesremaining,'outstanding_balane'=>$outstandingbalane,'failed_payment_count'=>$failedpaymentcount,'last_payment_date'=>$lastpaymentdate,'last_payment_amount'=>$lastpaymentamount,'regular_amount_paid'=>$regularamountpaid,'billing_period'=>$billingperiod,'billing_frequency'=>$billingfrequency,'total_billing_cycles'=>$totalbillingcycles,'regular_amount'=>$regularamount);			
			$expiredId = $recurringCollections['profile_id'];
			$model = Mage::getModel('recurringpayments/recurringprofiles')->load($expiredId)->addData($data);
			
			try {
				$model->save();
				echo "Data updated successfully.";
			} catch (Exception $e){
				echo $e->getMessage();
			}
		}
	}
	
	
	
}

