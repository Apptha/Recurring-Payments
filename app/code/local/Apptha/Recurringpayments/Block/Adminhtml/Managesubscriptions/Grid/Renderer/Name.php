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
 * @version     1.0.0
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2014 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 */
class Apptha_Recurringpayments_Block_Adminhtml_Managesubscriptions_Grid_Renderer_Name extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
	
	/**
	 * Function to call render for name column in subscription gird.
	 * (non-PHPdoc)
	 *
	 * @see Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract::render()
	 * @param $row as
	 *        	varien object
	 *        	
	 * @return String.
	 *
	 */
	public function render(Varien_Object $row) {
		$collection = $row->getData ();
		
		$productId = $collection ['product_id'];
		
		$productModel = Mage::getModel ( 'catalog/product' )->load ( $productId );
		
		$name = $productModel->getName ();
		return $name;
	}
}
