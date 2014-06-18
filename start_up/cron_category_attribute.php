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
                            'type'          => 'varchar',
                            'label'         => 'Navigation Group',
                            'input'         => 'text',
                            'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                            'visible'       => true,
                            'required'      => true,
                            'user_defined'  => true,
                            'default'       => "",
                            'group'         => "General Information"
                           );
	
	$installer->addAttribute('catalog_category', 'cat_code', $attribute);
	
	$installer->endSetup();