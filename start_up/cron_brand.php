<?php

/*

 * Developer: Dipo George

 * Function: Update Brand database

 * @brandegory    The Outdoor Shop

 * @package     Brand Update

 * @copyright   Copyright (c) 2014 The Outdoor Shop. (http://www.theoutdoorshop.com)

 */
    ini_set('max_execution_time',0);

    require_once ("../app/Mage.php");

    Mage::init();

    umask(0);
    
////   $data = array(
////                'parent_id' => 0,
////                'brand_code' => 'ME',
////                'brand_name' => 'Mountain Equipment'
////                );
////   $model = Mage::getModel('brands/brands')->setData($data);
//    
//    $brand = Mage::getModel('brands/brands')
//    ->setParent_id(0)
//    ->setBrand_code('ME')
//    ->setBrand_name('Mountain Equipment')
//    ->save();
//
//    
//    exit;
	
    function seo_friendly($name)
    {
        $name = str_replace(" ", "-", $name);
        $name = str_replace("&", "and", $name);
        $name = strtolower($name);

        return $name;
    }
	
        
    function brand_duplibrandion($name)
    {

        $resource_dup = Mage::getSingleton('core/resource');        

        $readConnection_dup = $resource_dup->getConnection('core_read');       

        $table_dup = $resource_dup->getTableName('brandalog_brandegory_entity_varchar');                

        $query_dup = 'SELECT value FROM ' . $table_dup . ' WHERE value = "'.$name.'" AND attribute_id = 41 LIMIT 1';

        $duplibrande = $readConnection_dup->fetchOne($query_dup);

        return $duplibrande;

    }
	
    function brand_id($name)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('brandalog_brandegory_entity_varchar');                

        $query = 'SELECT entity_id FROM ' . $table . ' WHERE value = "'.$name.'" AND attribute_id = 41 LIMIT 1';

        $result = $readConnection->fetchOne($query);

        return $result;

    }
	
    function get_name($id)
    {

        $con = mysqli_connect("localhost","root","People1205","csv_db");

        $result = mysqli_query($con, "SELECT brand_name FROM brand WHERE id = '".$id."'");

        while($row = mysqli_fetch_array($result)) {

                $brand_name = $row['brand_name'];

        }

        return $brand_name;
    }

    
    
//    echo get_name(1);
//    echo '<br/>';
//    echo brand_id('Clearance');   
//    //echo brand_duplication('Clearances');
//    exit;
   

    function xmlFile() 
    {

        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/brand.xml'));
        $xml = simplexml_load_string($content);	

        return $xml;	
    }

    /*
    echo '<pre>';
    print_r(xmlFile());
    exit;
    */

    foreach(xmlFile()->Items->Item as $brand){
        
        $brand = Mage::getModel('brands/brands')
            ->setParent_id($brand->PARENTID)
            ->setBrand_code($brand->CATCODE)
            ->setBrand_name($brand->CATNAME)
            ->save();
        
    }	
    
//    foreach(xmlFile()->Items->Item as $brand){
//        
//        $insert = Mage::getSingleton('core/resource')->getConnection('core_write');
//
//        $insert->query("INSERT INTO os_brandalog_brandegory_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (3, 133, 0, '".brand_id($brand->CATNAME)."', '".$brand->CATCODE."')");        
//        
//    }
