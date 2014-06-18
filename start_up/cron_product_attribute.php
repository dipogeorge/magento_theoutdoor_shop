<?php

/*

 * Developer: Dipo George

 * Function: Update Category Attributes

 * @category    The Outdoor Shop

 * @package     Category Attributes

 * @copyright   Copyright (c) 2014 The Outdoor Shop. (http://www.theoutdoorshop.com)

 */
    ini_set('max_execution_time',0);

    require_once ("../app/Mage.php");

    Mage::init();

    umask(0);
	
    Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
	
	$installer = new Mage_Sales_Model_Mysql4_Setup;
	
	$attribute  = array(
                            'attribute_set' => 'Default',
                            'group'         => 'Prices',
                            'label'         => 'SRP Price',
                            'visible'       => true,
                            'type'          => 'varchar', // multiselect uses comma-sep storage
                            'input'         => 'text',
                            'system'        => true,
                            'required'      => false,
                            'user_defined'  => 1, //defaults to false; if true, define a group
                            );
	
	$installer->addAttribute('catalog_product', 'srp_price', $attribute);
        
        $attribute  = array(
                            'attribute_set' => 'Default',
                            'group'         => 'Prices',
                            'label'         => 'Clearance Price',
                            'visible'       => true,
                            'type'          => 'varchar', // multiselect uses comma-sep storage
                            'input'         => 'text',
                            'system'        => true,
                            'required'      => false,
                            'user_defined'  => 1, //defaults to false; if true, define a group
                            );
	
	$installer->addAttribute('catalog_product', 'clear_price', $attribute);
        
        $attribute  = array(
                            'attribute_set' => 'Default',
                            'group'         => 'Prices',
                            'label'         => 'BMC Price',
                            'visible'       => true,
                            'type'          => 'varchar', // multiselect uses comma-sep storage
                            'input'         => 'text',
                            'system'        => true,
                            'required'      => false,
                            'user_defined'  => 1, //defaults to false; if true, define a group
                            );
	
	$installer->addAttribute('catalog_product', 'bmc_price', $attribute);
        
        $attribute  = array(
                            'attribute_set' => 'Default',
                            'group'         => 'Prices',
                            'label'         => 'Contract Price',
                            'visible'       => true,
                            'type'          => 'varchar', // multiselect uses comma-sep storage
                            'input'         => 'text',
                            'system'        => true,
                            'required'      => false,
                            'user_defined'  => 1, //defaults to false; if true, define a group
                            );
	
	$installer->addAttribute('catalog_product', 'contract_price', $attribute);
        
        $attribute  = array(
                            'attribute_set' => 'Default',
                            'group'         => 'Prices',
                            'label'         => 'Tax Group',
                            'visible'       => true,
                            'type'          => 'varchar', // multiselect uses comma-sep storage
                            'input'         => 'text',
                            'system'        => true,
                            'required'      => false,
                            'user_defined'  => 1, //defaults to false; if true, define a group
                            );
	
	$installer->addAttribute('catalog_product', 'tax_group', $attribute);
	
	$installer->endSetup();