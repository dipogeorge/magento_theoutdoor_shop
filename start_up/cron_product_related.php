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
    
    
    function duplication($product, $product_link)
    {

        $resource_dup = Mage::getSingleton('core/resource');        

        $readConnection_dup = $resource_dup->getConnection('core_read');       

        $table_dup = $resource_dup->getTableName('catalog_product_link');                

        $query_dup = 'SELECT link_id FROM ' . $table_dup . ' WHERE product_id = "'.$product.'" AND linked_product_id = "'.$product_link.'" LIMIT 1';

        $duplicate = $readConnection_dup->fetchOne($query_dup);

        return $duplicate;

    }    

    function prod_id($name)
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

        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/products_rel.xml'));
        $xml = simplexml_load_string($content);	

        return $xml;	
    }
    
    $resource = Mage::getSingleton('core/resource');
        
    $insert = $resource->getConnection('core_write');

    $read = $resource->getConnection('core_read');

    $pro_link = $resource->getTablename('catalog/product_link');				

    $pro_link_attr = $resource->getTablename('catalog/product_link_attribute_int');
    
    foreach(xmlFile()->Products->Product as $pro_rel){        
                
       //if($this->product_duplication($Item->CODE) != $Item->CODE){
         if(duplication(prod_id($pro_rel->Part), prod_id($pro_rel->Related)) < 1){
							
            $insert->query("INSERT into ".$pro_link." SET product_id='".prod_id($pro_rel->Part)."', linked_product_id='".prod_id($pro_rel->Related)."', link_type_id='4'");

            $result = $read->query("SELECT * FROM ".$pro_link." WHERE product_id='".prod_id($pro_rel->Part)."' and linked_product_id='" .prod_id($pro_rel->Related). "' and link_type_id='4'");

            $link = $result->fetch(PDO::FETCH_ASSOC);

            //$write->query("INSERT INTO ".$pro_link_attr." SET product_link_attribute_id = 1, link_id  = ".$link['link_id'].", value = 1");

            $insert->query("INSERT into ".$pro_link_attr." SET product_link_attribute_id = 4, link_id  = ".$link['link_id'].", value = 0");

        }
        else{


            $insert->query("INSERT into ".$pro_link." SET product_id='".prod_id($pro_rel->Part)."', linked_product_id='".prod_id($pro_rel->Related)."', link_type_id='4'");

            $result = $read->query("SELECT * FROM ".$pro_link." WHERE product_id='".prod_id($pro_rel->Part)."' and linked_product_id='" .prod_id($pro_rel->Related). "' and link_type_id='4'");

            $link = $result->fetch(PDO::FETCH_ASSOC);

            //$write->query("INSERT INTO ".$pro_link_attr." SET product_link_attribute_id = 1, link_id  = ".$link['link_id'].", value = 1");

            $insert->query("INSERT into ".$pro_link_attr." SET product_link_attribute_id = 4, link_id  = ".$link['link_id'].", value = 0");

        }
        
    }
