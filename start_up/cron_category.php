<?php

/*

 * Developer: Dipo George

 * Function: Update Category database

 * @category    The Outdoor Shop

 * @package     Category Update

 * @copyright   Copyright (c) 2014 The Outdoor Shop. (http://www.theoutdoorshop.com)

 */
    ini_set('max_execution_time',0);

    require_once ("../app/Mage.php");

    Mage::init();

    umask(0);
    
//CREATE TABLE `magento`.`os_catalog_category_match` (
//  `category_match_id` INT NOT NULL AUTO_INCREMENT,
//  `entity_id` INT NULL,
//  `cat_id` VARCHAR(45) NULL,
//  `cat_name` VARCHAR(45) NULL,
//  PRIMARY KEY (`category_match_id`));
	
    function seo_friendly($name)
    {
        $name = str_replace(" ", "-", $name);
        $name = str_replace("&", "and", $name);
        $name = strtolower($name);

        return $name;
    }
	
        
    function category_duplication($name)
    {

        $resource_dup = Mage::getSingleton('core/resource');        

        $readConnection_dup = $resource_dup->getConnection('core_read');       

        $table_dup = $resource_dup->getTableName('catalog_category_entity_varchar');                

        $query_dup = 'SELECT value FROM ' . $table_dup . ' WHERE value = "'.$name.'" AND attribute_id = 41 LIMIT 1';

        $duplicate = $readConnection_dup->fetchOne($query_dup);

        return $duplicate;

    }
	
    function cat_id($name)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('catalog_category_entity_varchar');                

        $query = 'SELECT entity_id FROM ' . $table . ' WHERE value = "'.$name.'" AND attribute_id = 41 LIMIT 1';

        $result = $readConnection->fetchOne($query);

        return $result;

    } 
    
    function get_name($id)
    {

        $con = mysqli_connect("localhost","root","People1205","csv_db");

        $result = mysqli_query($con, "SELECT cat_name FROM category WHERE id = '".$id."'");

        while($row = mysqli_fetch_array($result)) {

                $cat_name = $row['cat_name'];

        }

        return $cat_name;
    }

    
    
//    echo get_name(1);
//    echo '<br/>';
//    echo cat_id('Clearance');   
//    //echo category_duplication('Clearances');
//    exit;
   

    function xmlFile() 
    {

        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/category.xml'));
        $xml = simplexml_load_string($content);	

        return $xml;	
    }

    /*
    echo '<pre>';
    print_r(xmlFile());
    exit;
    */

    foreach(xmlFile()->Items->Item as $cat){
        
        if(category_duplication($cat->CATNAME) != $cat->CATNAME){
            
            $cat->PARENTID = str_replace(" ", "", $cat->PARENTID);
            $parent_id = explode(',', $cat->PARENTID);		

            $count = 0;

            while($count < count($parent_id)){	
                
                    if($parent_id[$count] == 0){

                            $parentId = '2';

                            $category = new Mage_Catalog_Model_Category();
                            $category->setName($cat->CATNAME);
                            $category->setUrlKey(seo_friendly($cat->CATNAME));
                            $category->setIsActive(1);
                            $category->setDisplayMode('PRODUCTS');
                            $category->setIsAnchor(0);

                            $parentCategory = Mage::getModel('catalog/category')->load($parentId);
                            $category->setPath($parentCategory->getPath());               

                            $category->save();
                            
                            $insert = Mage::getSingleton('core/resource')->getConnection('core_write');

                            $insert->query("INSERT INTO os_catalog_category_match (entity_id, cat_id, cat_name) VALUES ('".$category->getId()."', '".$cat->CATID."', '".$cat->CATNAME."')");        
                                                        
                            unset($category);
//
                    }
                    else{
                        
                        $parentId = cat_id(get_name($parent_id[$count]));

                        $category = new Mage_Catalog_Model_Category();
                        $category->setName($cat->CATNAME);
                        $category->setUrlKey(seo_friendly($cat->CATNAME));
                        $category->setIsActive(1);
                        $category->setDisplayMode('PRODUCTS');
                        $category->setIsAnchor(0);

                        $parentCategory = Mage::getModel('catalog/category')->load($parentId);
                        $category->setPath($parentCategory->getPath());               

                        $category->save();
                        
                        $insert = Mage::getSingleton('core/resource')->getConnection('core_write');

                        $insert->query("INSERT INTO os_catalog_category_match (entity_id, cat_id, cat_name) VALUES ('".$category->getId()."', '".$cat->CATID."', '".$cat->CATNAME."')");
                                                
                        unset($category);
                    }

            $count++;
            }	            
            
       }
        
    }	
    
//    foreach(xmlFile()->Items->Item as $cat){
//        
//        $insert = Mage::getSingleton('core/resource')->getConnection('core_write');
//
//        $insert->query("INSERT INTO os_catalog_category_entity_varchar (entity_type_id, attribute_id, store_id, entity_id, value) VALUES (3, 133, 0, '".cat_id($cat->CATNAME)."', '".$cat->CATCODE."')");        
//        
//    }
