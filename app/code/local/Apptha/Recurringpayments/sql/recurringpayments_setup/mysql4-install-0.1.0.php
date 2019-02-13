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
$installer = $this;

$installer->startSetup ();

$installer->run ( "
		
			DROP TABLE IF EXISTS {$this->getTable('apptha_subscriptiontypes')};
			CREATE TABLE {$this->getTable('apptha_subscriptiontypes')} (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`engine_code` varchar(255) NOT NULL DEFAULT '',
				`title` varchar(255) NOT NULL ,
				`status` varchar(255) NOT NULL DEFAULT '',
				`billing_frequency` INT NOT NULL ,
				`billing_period_unit` varchar(255) NOT NULL ,
				`billing_cycle` varchar(255) NOT NULL DEFAULT 'Infinite' ,
				`is_infinite` varchar(255) NOT NULL ,
				`is_trial_enabled` varchar(255) NOT NULL ,
				`occurrences_for_trialperiod` INT NOT NULL ,
				`is_initial_fee_enabled` varchar(255) NOT NULL ,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;				
			
			DROP TABLE IF EXISTS {$this->getTable('apptha_managesubscriptions')};
				CREATE TABLE {$this->getTable('apptha_managesubscriptions')} (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`is_subscription_only` int(11) NOT NULL ,
				`product_id` int(11) NOT NULL,
				`product_name` varchar(255) NOT NULL,
				`start_date` int(11) NOT NULL,					
				`sort_order` int(11) NOT NULL,	
				`product_status` int(11) NOT NULL,						
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

			DROP TABLE IF EXISTS {$this->getTable('apptha_productsubscriptions')};
				CREATE TABLE {$this->getTable('apptha_productsubscriptions')} (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`product_id` int(11) NOT NULL,
				`subscription_type` int(11) NOT NULL ,
				`is_subscription_only` int(11) NOT NULL ,
				`price_per_iteration` int(11)  NOT NULL ,				
				`initial_fee` varchar(11) NOT NULL ,
				`trial_period_price` int(11)  NOT NULL ,				
				`is_delete` int(11) NOT NUll,
				`created_date_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`)				
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
			
			 DROP TABLE IF EXISTS {$this->getTable('apptha_recurringprofiles')};
			  CREATE TABLE {$this->getTable('apptha_recurringprofiles')} (
              `recurring_id` int(11) unsigned NOT NULL auto_increment,
              `reference_id` int(20) NOT NULL,
              `customer_details` varchar(255) NOT NULL default '',
              `status` varchar(255) NOT NULL default '',
              `amount_of_regular_payment` int(100) NOT NULL ,
              `date_of_profile_creation` datetime NULL,
              `last_order_id` int(100) NOT NULL,
              `last_order_creation_date` datetime NULL,
              `maximum_failed_payments` int(100) NOT NULL,
              `profile_start_date` datetime NULL,
              `next_billing_date` datetime NULL,
              `numof_cycles_completed` int(100) NOT NULL,
              `outstanding_balane` int(100) NOT NULL,
              `failed_payment_count` int(100) NOT NULL,
              `regular_amount_paid` int(100) NOT NULL, 
              `billing_period` int(100) NOT NULL,
              `billing_frequency` int(100) NOT NULL,
              `total_billing_cycles` int(100) NOT NULL,
              `regular_amount` int(100) NOT NULL,                   
              PRIMARY KEY (`recurring_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			
		" );

$installer->endSetup ();


