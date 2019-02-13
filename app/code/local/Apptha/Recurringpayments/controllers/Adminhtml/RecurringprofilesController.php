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
 * RecurringProfiles Controller
 */
class Apptha_Recurringpayments_Adminhtml_RecurringprofilesController extends Mage_Adminhtml_Controller_action {
	protected function _initAction() {
		$this->loadLayout ()->_setActiveMenu ( 'recurringpayments/recurringprofiles' )->_addBreadcrumb ( Mage::helper ( 'adminhtml' )->__ ( 'Customer Manager' ), Mage::helper ( 'adminhtml' )->__ ( 'Customer Manager' ) );
		
		return $this;
	}
	public function indexAction() {		
		Mage::register ( 'ProfileStatus', 3 );
		$this->_initAction ()->renderLayout ();
	}
	public function activeAction() {
		$active = Mage::helper('recurringpayments')->__('Active');
		Mage::register ( 'ProfileStatus', 0 );
		Mage::register ( 'State', $active );
		$this->_initAction ()->renderLayout ();
	}
	public function suspendedAction() {
		$suspended = Mage::helper('recurringpayments')->__('Suspended');
		Mage::register ( 'ProfileStatus', 1 );
		Mage::register ( 'State', $suspended );
		$this->_initAction ()->renderLayout ();
	}	
	public function pendingAction() {
		$pending = Mage::helper('recurringpayments')->__('Pending');
		Mage::register ( 'ProfileStatus', 4 );
		Mage::register ( 'State', $pending );
		$this->_initAction ();
		$this->renderLayout ();
	}
	public function canceledAction() {
		$canceled = Mage::helper('recurringpayments')->__('Canceled'); 
		Mage::register ( 'ProfileStatus', 2 );
		Mage::register ( 'State', $canceled );
		$this->_initAction ();		
		$this->renderLayout ();
	}
	
	
	/**
	 * To view the recurringprofile
	 */
	public function editAction() {
		$Id = $this->getRequest ()->getParam ( 'id' );
		$model = Mage::getModel ( 'recurringpayments/subscriptiontype' )->load ( $Id );
		$modelProfile = Mage::getModel ( 'recurringpayments/recurringprofiles' )->load ( $Id );
		
		if ($model->getId () || $Id == 0) {
			$data = Mage::getSingleton ( 'adminhtml/session' )->getFormData ( true );
			if (! empty ( $data )) {
				$model->setData ( $data );
				$modelProfile->setData ( $data );
			}
			
			Mage::register ( 'subscriptiontype_data', $model );
			Mage::register ( 'recurringprofiles_data', $modelProfile );
			
			$this->loadLayout ();
			$this->_setActiveMenu ( 'recurringpayments/recurring' );
			
			$this->_addBreadcrumb ( Mage::helper ( 'adminhtml' )->__ ( 'Customer Manager' ), Mage::helper ( 'adminhtml' )->__ ( 'Customer Manager' ) );
			$this->_addBreadcrumb ( Mage::helper ( 'adminhtml' )->__ ( 'Item News' ), Mage::helper ( 'adminhtml' )->__ ( 'Item News' ) );
			
			$this->getLayout ()->getBlock ( 'head' )->setCanLoadExtJs ( true );
			
			$this->_addContent ( $this->getLayout ()->createBlock ( 'recurringpayments/adminhtml_recurringprofiles_edit' ) )->_addLeft ( $this->getLayout ()->createBlock ( 'recurringpayments/adminhtml_recurringprofiles_edit_tabs' ) );
			
			$this->renderLayout ();
		} else {
			Mage::getSingleton ( 'adminhtml/session' )->addError ( Mage::helper ( 'recurringpayments' )->__ ( 'customer does not exist' ) );
			$this->_redirect ( '*/*/' );
		}
	}
}