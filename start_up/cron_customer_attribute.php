<?php

    ini_set('max_execution_time',0);

    require_once ("../app/Mage.php");

    Mage::init();

    umask(0); 

	Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
	
//	$installer = new Mage_Sales_Model_Mysql4_Setup;
//	
//	$attribute = array(
//							'group'             => 'Default',
//							'type'              => 'varchar',
//							'input'             => 'text',
//							'label'             => 'Elucid Code',
//							//'source'            => 'tax/class_source_customer',
//							'required'          => true,
//							'visible'           => true,
//							'user_defined'      => true
//							);
//
//	$installer->addAttribute( 'customer', 'elucid_code', $attribute);
//
//	Mage::getSingleton('eav/config')
//					->getAttribute('customer', 'elucid_code')
//					->setData('used_in_forms', array('adminhtml_customer'))
//					->save();
//
//	$installer->endSetup();
        
        $installer = new Mage_Sales_Model_Mysql4_Setup;
	
	$attribute = array(
							'group'             => 'Default',
							'type'              => 'varchar',
							'input'             => 'text',
							'label'             => 'Customer Type',
							//'source'            => 'tax/class_source_customer',
							'required'          => true,
							'visible'           => true,
							'user_defined'      => true
							);

	$installer->addAttribute( 'customer', 'cust_type', $attribute);

	Mage::getSingleton('eav/config')
					->getAttribute('customer', 'cust_type')
					->setData('used_in_forms', array('adminhtml_customer'))
					->save();

	$installer->endSetup();

?>