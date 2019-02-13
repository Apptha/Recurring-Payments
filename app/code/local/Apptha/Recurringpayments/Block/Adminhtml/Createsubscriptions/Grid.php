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
 * Createsubscriptions grid block
 */
class Apptha_Recurringpayments_Block_Adminhtml_Createsubscriptions_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'productGrid' );
		$this->setDefaultSort ( 'entity_id' );
		$this->setDefaultDir ( 'DESC' );
		$this->setSaveParametersInSession ( true );
		//$this->setUseAjax ( true );
		$this->setVarNameFilter ( 'product_filter' );
	}
	protected function _getStore() {
		$storeId = ( int ) $this->getRequest ()->getParam ( 'store', 0 );
		return Mage::app ()->getStore ( $storeId );
	}
	protected function _prepareCollection() {
		$store = $this->_getStore ();
		$managesCollection = Mage::getModel ( 'recurringpayments/managesubscriptions' )->getCollection ();
		
		$collection = Mage::getModel ( 'catalog/product' )->getCollection ()->addAttributeToSelect ( 'sku' )->addAttributeToSelect ( 'name' )->addAttributeToSelect ( 'attribute_set_id' )->addAttributeToSelect ( 'type_id' )
		->addAttributeToFilter('type_id', array('in' => array('simple','virtual')));
		$idValue = null;
		foreach ( $managesCollection as $manageCollectionValue ) {
			$idValue [] = $manageCollectionValue->getProductId ();
		}
		
		$collection->addAttributeToFilter ( 'entity_id', array (
				'nin' => $idValue 
		) );
		
		if (Mage::helper ( 'catalog' )->isModuleEnabled ( 'Mage_CatalogInventory' )) {
			$collection->joinField ( 'qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left' );
		}
		if ($store->getId ()) {
			$collection->setStoreId ( $store->getId () );
			$adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
			$collection->addStoreFilter ( $store );
			$collection->joinAttribute ( 'name', 'catalog_product/name', 'entity_id', null, 'inner', $adminStore );
			$collection->joinAttribute ( 'custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId () );
			$collection->joinAttribute ( 'status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId () );
			$collection->joinAttribute ( 'visibility', 'catalog_product/visibility', 'entity_id', null, 'inner', $store->getId () );
			$collection->joinAttribute ( 'price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId () );
		} else {
			$collection->addAttributeToSelect ( 'price' );
			$collection->joinAttribute ( 'status', 'catalog_product/status', 'entity_id', null, 'inner' );
			$collection->joinAttribute ( 'visibility', 'catalog_product/visibility', 'entity_id', null, 'inner' );
		}
		
		$this->setCollection ( $collection );
		
		parent::_prepareCollection ();
		$this->getCollection ()->addWebsiteNamesToResult ();
		return $this;
	}
	protected function _addColumnFilterToCollection($column) {
		if ($this->getCollection ()) {
			if ($column->getId () == 'websites') {
				$this->getCollection ()->joinField ( 'websites', 'catalog/product_website', 'website_id', 'product_id=entity_id', null, 'left' );
			}
		}
		return parent::_addColumnFilterToCollection ( $column );
	}
	protected function _prepareColumns() {
		$this->addColumn ( 'entity_id', array (
				'header' => Mage::helper ( 'catalog' )->__ ( 'ProductId' ),
				'width' => '50px',
				'type' => 'number',
				'index' => 'entity_id' 
		) );
		$this->addColumn ( 'name', array (
				'header' => Mage::helper ( 'catalog' )->__ ( 'Name' ),
				'index' => 'name' 
		) );
		
		$store = $this->_getStore ();
		if ($store->getId ()) {
			$this->addColumn ( 'custom_name', array (
					'header' => Mage::helper ( 'catalog' )->__ ( 'Name in %s', $store->getName () ),
					'index' => 'custom_name' 
			) );
		}
		
		$this->addColumn ( 'sku', array (
				'header' => Mage::helper ( 'catalog' )->__ ( 'SKU' ),
				'width' => '80px',
				'index' => 'sku' 
		) );
		
		$store = $this->_getStore ();
		$this->addColumn ( 'price', array (
				'header' => Mage::helper ( 'catalog' )->__ ( 'Price' ),
				'type' => 'price',
				'currency_code' => $store->getBaseCurrency ()->getCode (),
				'index' => 'price' 
		) );
		
		if (Mage::helper ( 'catalog' )->isModuleEnabled ( 'Mage_CatalogInventory' )) {
			$this->addColumn ( 'qty', array (
					'header' => Mage::helper ( 'catalog' )->__ ( 'Qty' ),
					'width' => '100px',
					'type' => 'number',
					'index' => 'qty' 
			) );
		}
		
		$this->addColumn ( 'status', array (
				'header' => Mage::helper ( 'catalog' )->__ ( 'Status' ),
				'width' => '70px',
				'index' => 'status',
				'type' => 'options',
				'options' => Mage::getSingleton ( 'catalog/product_status' )->getOptionArray () 
		) );
		
		if (! Mage::app ()->isSingleStoreMode ()) {
			$this->addColumn ( 'websites', array (
					'header' => Mage::helper ( 'catalog' )->__ ( 'Websites' ),
					'width' => '100px',
					'sortable' => false,
					'index' => 'websites',
					'type' => 'options',
					'options' => Mage::getModel ( 'core/website' )->getCollection ()->toOptionHash () 
			) );
		}
		
		return parent::_prepareColumns ();
	}
	public function getRowUrl($row) {
		return $this->getUrl ( '*/*/move', array (
				'id' => $row->getId () 
		) );
	}
}
