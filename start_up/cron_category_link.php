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
    
    function seo_friendly($name)
    {
        $name = str_replace(" ", "-", $name);
        $name = str_replace("&", "and", $name);
        $name = strtolower($name);

        return $name;
    }
	
        
    function duplication($cat, $product)
    {

        $resource_dup = Mage::getSingleton('core/resource');        

        $readConnection_dup = $resource_dup->getConnection('core_read');       

        $table_dup = $resource_dup->getTableName('catalog_category_product');                

        $query_dup = 'SELECT position FROM ' . $table_dup . ' WHERE category_id = "'.$cat.'" AND product_id = "'.$product.'" LIMIT 1';

        $duplicate = $readConnection_dup->fetchOne($query_dup);

        return $duplicate;

    }
    
    function cat_id($name)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('catalog_category_entity_varchar');                

        $query = 'SELECT entity_id FROM ' . $table . ' WHERE value = "'.$name.'" AND attribute_id = 133';

        $result = $readConnection->fetchAll($query);

        return $result;

    } 
    
    function product_id($name)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('catalog_product_entity');                

        $query = 'SELECT entity_id FROM ' . $table . ' WHERE sku = "'.$name.'" LIMIT 1';

        $result = $readConnection->fetchOne($query);

        return $result;

    }
    
    function xmlFile() 
    {

        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/categorys.xml'));
        $xml = simplexml_load_string($content);	

        return $xml;	
    }
    
    foreach(xmlFile()->Categorys->Category as $cat_link){
        
        $insert = Mage::getSingleton('core/resource')->getConnection('core_write');
        
        $cat_id = cat_id($cat_link->Nav_Group);
    
        foreach($cat_id as $cat){   
            
            if(duplication($cat['entity_id'], product_id($cat_link->Part)) != 1){
                
                $insert->query("INSERT INTO os_catalog_category_product (category_id, product_id, position) VALUES ('".$cat['entity_id']."', '".product_id($cat_link->Part)."', 1)");        
        
            }
            
        }
        
    }
