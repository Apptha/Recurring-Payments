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
 * Recurringpayments_Helper Data Block
 *
 * @category Apptha
 * @package Apptha_Recurringpayments
 * @author Apptha Team <developers@contus.in>
 */
class Apptha_Recurringpayments_Helper_Data extends Mage_Core_Helper_Abstract {
	public function checkSubscriptionAndRecurringPaymentsKey() {
		$apikey = Mage::getStoreConfig ( 'recurringpayments/settings/apply_apptha_licensekey' );
		$subscriptionAndRecurringPaymentsApiKey = $this->subscriptionAndRecurringPaymentsApiKey ();
		if ($apikey != $subscriptionAndRecurringPaymentsApiKey) {
			return false;
		}
		
		return true;
	}
	public function getLicenseText() {
		return base64_decode ( 'PGEgc3R5bGU9ImNvbG9yOnJlZDsiIGhyZWY9Imh0dHA6Ly93d3cuYXBwdGhhLmNvbS9jaGVja291dC9jYXJ0L2FkZC9wcm9kdWN0LzE2NyIgdGFyZ2V0PSJfYmxhbmsiPiAtIEJ1eSBub3c8L2E+' );
	}
	
	/**
	 * Function to get the license key
	 *
	 * Return generated license key
	 * 
	 * @return string
	 */
	public function subscriptionAndRecurringPaymentsApiKey() {
		$code = $this->genenrateOscdomain ();
		$domainKey = substr ( $code, 0, 25 ) . "CONTUS";
		return $domainKey;
	}
	/**
	 * Function to get the domain key
	 *
	 * Return domain key
	 * 
	 * @return string
	 */
	public function domainKey($tkey) {
		$message = "EM-RECPAYMP0EFIL9XEV8YZAL7KCIUQ6NI5OREH4TSEB3TSRIF2SI1ROTAIDALG-JW";
		$stringLength = strlen ( $tkey );
		for($i = 0; $i < $stringLength; $i ++) {
			$keyArray [] = $tkey [$i];
		}
		$encMessage = "";
		$kPos = 0;
		$charsStr = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
		$strLen = strlen ( $charsStr );
		for($i = 0; $i < $strLen; $i ++) {
			$charsArray [] = $charsStr [$i];
		}
		$lenMessage = strlen ( $message );
		$count = count ( $keyArray );
		for($i = 0; $i < $lenMessage; $i ++) {
			$char = substr ( $message, $i, 1 );
			$offset = $this->getOffset ( $keyArray [$kPos], $char );
			$encMessage .= $charsArray [$offset];
			$kPos ++;
			
			if ($kPos >= $count) {
				$kPos = 0;
			}
		}
		return $encMessage;
	}
	/**
	 * Function to get the offset for license key
	 *
	 * Return offset key
	 * 
	 * @return string
	 */
	public function getOffset($start, $end) {
		$charsStr = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
		$strLen = strlen ( $charsStr );
		for($i = 0; $i < $strLen; $i ++) {
			$charsArray [] = $charsStr [$i];
		}
		for($i = count ( $charsArray ) - 1; $i >= 0; $i --) {
			$lookupObj [ord ( $charsArray [$i] )] = $i;
		}
		$sNum = $lookupObj [ord ( $start )];
		$eNum = $lookupObj [ord ( $end )];
		$offset = $eNum - $sNum;
		if ($offset < 0) {
			$offset = count ( $charsArray ) + ($offset);
		}
		return $offset;
	}
	/**
	 * Function to generate license key
	 *
	 * Return license key
	 * 
	 * @return string
	 */
	public function genenrateOscdomain() {
		$subfolder = $matches = '';
		$strDomainName = Mage::app ()->getFrontController ()->getRequest ()->getHttpHost ();
		preg_match ( "/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder );
		preg_match ( "/^(https:\/\/)?([^\/]+)/i", $strDomainName, $subfolder );
		preg_match ( "/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder [2], $matches );
		if (isset ( $matches ['domain'] )) {
			$customerurl = $matches ['domain'];
		} else {
			$customerurl = "";
		}
		$customerurl = str_replace ( "www.", "", $customerurl );
		$customerurl = str_replace ( ".", "D", $customerurl );
		$customerurl = strtoupper ( $customerurl );
		if (isset ( $matches ['domain'] )) {
			$response = $this->domainKey ( $customerurl );
		} else {
			$response = "";
		}
		return $response;
	}
}