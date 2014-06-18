<?php

/*

 * Developer: Dipo George

 * Function: Update Product database

 * @category    The Outdoor Shop

 * @package     Product Update

 * @copyright   Copyright (c) 2014 The Outdoor Shop. (http://www.theoutdoorshop.com)

 */

    ini_set('max_execution_time',0);

    require_once ("../app/Mage.php");

    Mage::init();

    umask(0);
    
    $test = Mage::getModel('matrixrate_adminhtml/system_config_backend_shipping_matrixrate');
    var_dump(get_class($test)); 
    exit;
    
//    $stockQty = 3;
//    
$product = Mage::getModel('catalog/product')->load(1);
//    //if (!($stockItem = $product->getStockItem())) {
//    $stockItem = Mage::getModel('cataloginventory/stock_item');
//    $stockItem->assignProduct($product)
//              ->setData('stock_id', 1)
//              ->setData('store_id', 1);
////}
//    $stockItem->setData('qty', $stockQty)
//              ->setData('is_in_stock', $stockQty > 0 ? 1 : 0)
//              ->setData('manage_stock', 1)
//              ->setData('use_config_manage_stock', 0)
//              ->save();
    
    //$product = Mage::getModel('catalog/product')->loadByAttribute('sku', 'PN4506');
    //$stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product->entity_id);
    //$stockItem = Mage::getModel('cataloginventory/stock_item');
    
    
//    $stockStatus = Mage::getModel('cataloginventory/stock_status');
//    $stockStatus->setData('product_id', 1);
//    $stockStatus->setData('website_id', 1);
//    $stockStatus->setData('stock_id', 1);
//    $stockStatus->setData('qty', 12);
//    $stockStatus->setData('stock_status', 1);
//    $stockStatus->save();
    
    echo '<pre>';
    print_r($product);
    exit;
    
    function product_id($name)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('catalog_product_entity');                

        $query = 'SELECT entity_id FROM ' . $table . ' WHERE sku = "'.$name.'"';

        $result = $readConnection->fetchOne($query);

        return $result;

    }
    
    function cat_id($cat_id, $cat_name)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('catalog_category_match');                

        $query = 'SELECT entity_id FROM ' . $table . ' WHERE cat_id = "'.$cat_id.'" AND cat_name = "'.$cat_name.'" LIMIT 1';

        $result = $readConnection->fetchOne($query);

        return $result;

    }
    
    //echo get_name(15000);
    //echo '<br/>';
    //echo cat_id('Futura Pro 38 3');
    //echo product_id('PN125rew789');
    //exit;
    

    function xmlFile_product() 
    {
        
        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/product.xml'));
        $xml = simplexml_load_string($content);	

        return $xml;	
    }
    
//    function xmlFile_category() 
//    {
//        
//        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/category.xml'));
//        $xml = simplexml_load_string($content);	
//
//        return $xml;	
//    }
    
//    foreach(xmlFile_category()->Items->Item as $cat){
//        
//        //echo cat_id($cat->CATID, $cat->CATNAME).'<br/>';
//        //exit();
//        
//    }
//    
//    exit;

    /*
    unset($products_array);
    foreach(xmlFile()->mydata as $products){

            unset($products_array);

            $products->CATCODE = str_replace(" ", "", $products->CATCODE);
            $products = explode(',', $products->CATCODE);

            //$products[] = $products;

            //echo '<pre>';
            //echo $products->CATCODE.'<br/>';
            //$category_array = cat_id(get_name(15000));
            //print_r($products);
            $count = 0;
            while($count < count($products)){

                    $products_array .= cat_id(get_name($products[$count])).',';

            $count++;
            }

            $products_array = rtrim($products_array, ',');

    }

    //echo print_r($products);

    //echo $category_array;
    //exit;
    */
    //ITEM QTY SCRIPT:
    foreach(xmlFile_product()->Items->Item as $products){
        
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $products->CODE);
        //if (!($stockItem = $product->getStockItem())) {
        $stockItem = Mage::getModel('cataloginventory/stock_item');
        $stockItem->assignProduct($product)
                  ->setData('stock_id', 1)
                  ->setData('store_id', 1);
    //}
        $stockItem->setData('qty', $products->QTY)
                  ->setData('is_in_stock', $products->QTY > 0 ? 1 : 0)
                  ->setData('manage_stock', 1)
                  ->setData('use_config_manage_stock', 0)
                  ->save();
        
    }


//    foreach(xmlFile_product()->Items->Item as $products){
//        
//        $insert = Mage::getSingleton('core/resource')->getConnection('core_write');
//        
//        $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, 134, 0, '".product_id($products->CODE)."', '".$products->SRP."')");
//
//        $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, 135, 0, '".product_id($products->CODE)."', '".$products->CLEAR."')");
//
//        $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, 136, 0, '".product_id($products->CODE)."', '".$products->BMC."')");
//
//        $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, 137, 0, '".product_id($products->CODE)."', '".$products->CONTRACT."')");
//
//    }

//    foreach(xmlFile_category()->Items->Item as $cat){
//        
//        if(is_numeric(cat_id($cat->CATID, $cat->CATNAME))){
//        
//            //echo cat_id($cat->CATNAME).'<br/>';
//
//            $insert = Mage::getSingleton('core/resource')->getConnection('core_write');
//
//            $insert->query("INSERT INTO os_catalog_category_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (3, 133, 0, '".cat_id($cat->CATID, $cat->CATNAME)."', '".$cat->CATCODE."')");        
//        
//        }
//    }
//    foreach(xmlFile_product()->Items->Item as $products){
//        
//        //if(product_duplication($products->CODE) != $products->CODE){
//        
//            $insert = Mage::getSingleton('core/resource')->getConnection('core_write');
//
//            $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, 134, 0, '".product_id($products->CODE)."', '".$products->SRP."')");
//
//            $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, 135, 0, '".product_id($products->CODE)."', '".$products->CLEAR."')");
//
//            $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, 136, 0, '".product_id($products->CODE)."', '".$products->BMC."')");
//
//            $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, 137, 0, '".product_id($products->CODE)."', '".$products->CONTRACT."')");
//        //}
//    }
//    $installer = $this;
//    $installer->startSetup();
//
//    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
//
//    $entityTypeId     = $setup->getEntityTypeId('customer');
//    $attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
//    $attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);
//
//    $setup->addAttribute(
//                        'customer', 'your_attribute_code_here', array(
//                                                                    'input'         => 'text',
//                                                                    'type'          => 'int',
//                                                                    'label'         => 'Some textual description',
//                                                                    'visible'       => 1,
//                                                                    'required'      => 0,
//                                                                    'user_defined' => 1,
//                                                                    )
//                        );
//
//    $setup->addAttributeToGroup(
//                                $entityTypeId,
//                                $attributeSetId,
//                                $attributeGroupId,
//                                'your_attribute_code_here',
//                                '999'  //sort_order
//                               );
//
//    $oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'your_attribute_code_here');
//    $oAttribute->setData('used_in_forms', array('adminhtml_customer'));
//    $oAttribute->save();
//
//    $setup->endSetup();
