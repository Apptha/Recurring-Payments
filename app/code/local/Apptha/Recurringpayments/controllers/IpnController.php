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
require_once 'Mage/Paypal/controllers/IpnController.php';
class Apptha_Recurringpayments_IpnController extends Mage_Paypal_IpnController 
{
	/**
	 * Instantiate IPN model and pass IPN request to it
	 */
	public function indexAction()
	{		
		if (!$this->getRequest()->isPost()) {
			return;
		}
		
		try {
			$data = $this->getRequest()->getPost();	
			Mage::getModel('paypal/ipn')->processIpnRequest($data, new Varien_Http_Adapter_Curl());
			Mage::dispatchEvent('paypal_ipn_index',array('ipndetails' => $data,));
			
		} catch (Exception $e) {
			Mage::logException($e);
			$this->getResponse()->setHttpResponseCode(500);
		}
	}
}
