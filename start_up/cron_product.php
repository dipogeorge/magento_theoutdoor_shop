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
    
//    echo $srp;
//    echo '<br/>';
//    echo $bmc;
//    echo '<br/>';
//    echo $clear;
//    echo '<br/>';
//    echo $contract;

    //exit;
    
//    $productModel = Mage::getModel('catalog/product');
//    $attr = $productModel->getResource()->getAttribute("color");
//    if ($attr->usesSource()) {
//        echo $color_id = $attr->getSource()->getOptionId("Red");
//    }
//    
//    exit;
  
    
//    $test = Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE;
//    echo $test;
//    exit;
//    
//    $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct(1);
//    $stockItemId = $stockItem->getId();
//    
//    echo '<pre>';
//    print_r($stockItemId);
//    exit;
    
    function product_duplication($product_code)
    {        
       
        $resource = Mage::getSingleton('core/resource');
        
        $readConnection = $resource->getConnection('core_read');
       
        $table = $resource->getTableName('catalog_product_entity');
                
        $query = 'SELECT sku FROM ' . $table . ' WHERE sku = "'.$product_code . '" LIMIT 1';

        $sku_duplicate = $readConnection->fetchOne($query);

        return $sku_duplicate;
    }

//    function seo_friendly($name)
//    {
//
//        $name = str_replace(" ", "-", $name);
//        $name = str_replace("&", "and", $name);
//        $name = strtolower($name);
//
//        return $name;
//
//    }
//
//    function get_name($cat_code)
//    {
//
//        $con = mysqli_connect("localhost","root","People1205","csv_db");
//
//        $result = mysqli_query($con, "SELECT cat_name FROM category WHERE cat_code = '".$cat_code."'");
//
//        while($row = mysqli_fetch_array($result)) {
//
//            $cat_name = $row['cat_name'];
//
//        }
//
//        return $cat_name;
//    }
//
    function cat_id($name)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('catalog_category_entity_varchar');                

        $query = 'SELECT entity_id FROM ' . $table . ' WHERE value = "'.$name.'" AND attribute_id = 41';

        $result = $readConnection->fetchAll($query);
        
        foreach($result as $cat){

            $cat_id .=  $cat['entity_id'].',';
        }

        return rtrim($cat_id, ',');

    }
    
    //echo cat_id('Footwear');
    //exit;
    
    function product_id($name)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('catalog_product_entity');                

        $query = 'SELECT entity_id FROM ' . $table . ' WHERE sku = "'.$name.'"';

        $result = $readConnection->fetchOne($query);

        return $result;

    }
    
    function seo_friendly($name)
    {

        $name = str_replace(" ", "-", $name);
        $name = str_replace("(", "", $name);
        $name = str_replace(")", "", $name);
        $name = strtolower($name);

        return $name;

    }
    
    //echo seo_friendly('Extra Small');
    //exit;
    
    function getID($attribute, $option)
    {
        
//        $productModel = Mage::getModel('catalog/product');
//        $attr = $productModel->getResource()->getAttribute($attribute);
//        
//        if ($attr->usesSource()) {
//             $color_id = $attr->getSource()->getOptionId($option);
//        }
//        
//        return $color_id;
        //get the attribute
        $attribute = Mage::getResourceModel('catalog/product')->getAttribute($attribute);

        //set the store id on the attribute
        $attribute->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID);

        //get the source
        $source = $attribute->getSource();

        //get the id
        $id = $source->getOptionId(seo_friendly($option));
        
        return $id;
        
    }
    
    //echo getID('size', 'Extra Small');
    
    //exit;
    
    //echo $sizeId = getOptionId('size', 'Small');
    //echo '<br/>';
    //echo $colourId = getOptionId('color', 'Red');
	
    //echo getOptionId('size', 'Small');
    //echo get_name(15000);
    //echo '<br/>';
    //echo cat_id('Futura Pro 38 3');
    //echo product_id('PN125rew789');
    //exit;
    

    function xmlFile() 
    {
        
        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/products.xml'));
        $xml = simplexml_load_string($content);	

        return $xml;	
    }	

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


    foreach(xmlFile()->Products->Product as $products)
    {
        //echo getID('size', $products->Size).'<br/>';
        if(product_duplication($products->Part_Number) != $products->Part_Number){
            

            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
            
            /*-- INITIAL PRODUCT UPLOAD START --*/
            $product = Mage::getModel('catalog/product'); 
            $product->setSku($products->Part_Number);
            $product->setName($products->Description);
            $product->setDescription($products->Full_Description);
            $product->setShortDescription($products->Description);
            $product->setPrice(floatval($products->Our_Price));
            //$product->setTypeId(product_type($products->Part_type, $products->Master_Part));
            //$product->setTypeId('simple');
            $product->setCategoryIds(array(cat_id($products->Family)));
            
            if($products->Master_Part == '' && $products->Part_type == 1){
                
               $product->setAttributeSetId(4);
               $product->setTypeId('simple');
               
            }
            elseif($products->Master_Part == '' && $products->Part_type == 5){
                
               $product->setAttributeSetId(9);
               $product->setTypeId('configurable');
               
            }
            else{
                
                $product->setAttributeSetId(9);
                $product->setTypeId('simple');
                
            }
            $product->setWeight($products->Weight);
            $product->setTaxClassId(2);
            $product->setVisibility(4);
            $product->setStatus(1);					
            $product->setWebsiteIds(array(1));
            
            if($products->Part_type == 5){
                
                $data = array(
                            '0' => array(
                                        'id' => NULL,
                                        'label' => 'Color',
                                        'position'=> NULL,
                                        'values' => NULL,
                                        'attribute_id' => 92,
                                        'attribute_code' => 'color',
                                        'frontend_label' => 'Colour',
                                        'html_id' => 'config_super_product__attribute_0'
                                        ),
                            '1' => array(
                                        'id' => NULL,
                                        'label' => 'Size',
                                        'position' => NULL,
                                        'values' => NULL,
                                        'attribute_id' => 139,
                                        'attribute_code' => 'size',
                                        'frontend_label' => 'Size',
                                        'html_id' => 'config_super_product__attribute_1'
                                        )
                );
                $product->setConfigurableAttributesData($data);
                $product->setCanSaveConfigurableAttributes(1);
                
            }
            
            $product->save();            
            
            $product_info = Mage::getModel('catalog/product')->loadByAttribute('sku', $products->Part_Number);
            //if (!($stockItem = $product->getStockItem())) {
            $stockItem = Mage::getModel('cataloginventory/stock_item');
            $stockItem->assignProduct($product_info)
                      ->setData('stock_id', 1)
                      ->setData('store_id', 1);
            //}
            $stockItem->setData('qty', $products->Free_Stock)
                      ->setData('is_in_stock', $products->Free_Stock > 0 ? 1 : 0)
                      ->setData('manage_stock', 1)
                      ->setData('use_config_manage_stock', 0)
                      ->save();
            
            product_price($products->Part_Number, $products->SRP, $products->Clearance, $products->BMC, $products->Contract, $products->Tax_Code);
            
            if($products->Size != ''){
               
                product_size($products->Part_Number, $products->Size);                
                
            }
            if($products->Colour != ''){
                
                product_colour($products->Part_Number, $products->Colour);
            }
            
            if($products->Master_Part != '' && $products->Part_type == 1){
                
                 product_map($products->Part_Number, $products->Master_Part);
                 product_hide($products->Part_Number);
            }
            
            if($products->Part_type == 5){
             
                product_inventory($products->Part_Number);
            }
            
            product_tax($products->Part_Number, $products->Tax_Code);
         }
    }
    
    
    function product_price($code, $srp, $clear, $bmc, $contract, $tax_group)
    {
        $eavAttribute = new Mage_Eav_Model_Mysql4_Entity_Attribute();
        $srpID = $eavAttribute->getIdByCode('catalog_product', 'srp_price');
        $bmcID = $eavAttribute->getIdByCode('catalog_product', 'bmc_price');
        $clearID = $eavAttribute->getIdByCode('catalog_product', 'clear_price');
        $contractID = $eavAttribute->getIdByCode('catalog_product', 'contract_price');
        $taxgroupID = $eavAttribute->getIdByCode('catalog_product', 'tax_group');
       
            $insert = Mage::getSingleton('core/resource')->getConnection('core_write');

            $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, '".$srpID."', 0, '".product_id($code)."', '".number_format((integer)$srp, 2, '.', ',')."')");

            $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, '".$clearID."', 0, '".product_id($code)."', '".number_format((integer)$clear, 2, '.', ',')."')");

            $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, '".$bmcID."', 0, '".product_id($code)."', '".number_format((integer)$bmc, 2, '.', ',')."')");

            $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, '".$contractID."', 0, '".product_id($code)."', '".number_format((integer)$contract, 2, '.', ',')."')");
            
            $insert->query("INSERT INTO os_catalog_product_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (4, '".$taxgroupID."', 0, '".product_id($code)."', '".$tax_group."')");


    }
    
    function product_size($code, $size)
    {       
        $_product = Mage::getModel('catalog/product')->load(product_id($code));
        
        $_product->setData('size', getID('size', $size)); 
        $_product->save();
        
    }
    
    function product_colour($code, $colour)
    {
        $_product = Mage::getModel('catalog/product')->load(product_id($code));
        
        $_product->setData('color',  getID('color', $colour)); 
        $_product->save();
    }
    
    function product_inventory($master)
    {
        $insert = Mage::getSingleton('core/resource')->getConnection('core_write');
        
        $insert->query("UPDATE os_cataloginventory_stock_item SET is_in_stock = 1 WHERE product_id = '".product_id($master)."'");
        
    }
    
    function product_map($code, $master)
    {

	$simpleSku = $code;
	$configurableSku = $master;
	$simpleProduct = Mage::getModel('catalog/product')->loadByAttribute('sku',$simpleSku);       
	$configurableProduct = Mage::getModel('catalog/product')->loadByAttribute('sku',$configurableSku);
	$simpleId = $simpleProduct->getId();
	$ids = $configurableProduct->getTypeInstance()->getUsedProductIds();

	$newids = array();
	foreach ( $ids as $id ) {
            
		$newids[$id] = 1;
                
	}
        
	$newids[$simpleId] = 1;
	
	Mage::getResourceModel('catalog/product_type_configurable')->saveProducts($configurableProduct, array_keys($newids));
    }
    
    function product_hide($master)
    {
        $insert = Mage::getSingleton('core/resource')->getConnection('core_write');
        
        $insert->query("UPDATE os_catalog_product_entity_int SET value = 3 WHERE entity_id = '".product_id($master)."' AND attribute_id = 102");
        
    }
    
    function product_tax($code, $tax_group)
    {
        $insert = Mage::getSingleton('core/resource')->getConnection('core_write');
        
        if($tax_group == 'T1'){
            
            $insert->query("UPDATE os_catalog_product_entity_int SET value = 5 WHERE entity_id = '".product_id($code)."' AND attribute_id = 121");
            
        }
        elseif($tax_group == 'T2'){
            
            $insert->query("UPDATE os_catalog_product_entity_int SET value = 6 WHERE entity_id = '".product_id($code)."' AND attribute_id = 121");
            
        }
        else{
            
            $insert->query("UPDATE os_catalog_product_entity_int SET value = 7 WHERE entity_id = '".product_id($code)."' AND attribute_id = 121");
            
        }
        
        
        
    }

