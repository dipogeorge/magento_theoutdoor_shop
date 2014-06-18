<?php

    ini_set('max_execution_time',0);
    ini_set('memory_limit', '-1');

    require_once ("../app/Mage.php");

    Mage::init();

    umask(0);   
    
    function duplication($name)
    {

        $resource_dup = Mage::getSingleton('core/resource');        

        $readConnection_dup = $resource_dup->getConnection('core_read');       

        $table_dup = $resource_dup->getTableName('customer_entity_varchar');                

        $query_dup = 'SELECT value FROM ' . $table_dup . ' WHERE value = "'.$name.'" AND attribute_id = 138 LIMIT 1';

        $duplicate = $readConnection_dup->fetchOne($query_dup);

        return $duplicate;

    }
    
    function cust_id($name)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('customer_entity_varchar');                

        $query = 'SELECT entity_id FROM ' . $table . ' WHERE value = "'.$name.'" AND attribute_id = 138 LIMIT 1';

        $result = $readConnection->fetchOne($query);

        return $result;

    }
    
    function cust_fname($id)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('customer_entity_varchar');                

        $query = 'SELECT value FROM ' . $table . ' WHERE entity_id = '.$id.' AND attribute_id = 5 LIMIT 1';

        $result = $readConnection->fetchOne($query);

        return $result;

    }
    
    function cust_sname($id)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('customer_entity_varchar');                

        $query = 'SELECT value FROM ' . $table . ' WHERE entity_id = '.$id.' AND attribute_id = 7 LIMIT 1';

        $result = $readConnection->fetchOne($query);

        return $result;

    }
    
    function replace($name)
    {
        $replace = str_replace("UK", "GB", $name);
        
        return $replace;
    }    
    
    function xmlFile() 
    {
        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/customer_add.xml'));
        $xml = simplexml_load_string($content);	

        return $xml;	
    }
    
    foreach(xmlFile()->Addresses->Address as $address){
        
        if(is_numeric(cust_id($address->customer))){ 
            
            $add = Mage::getModel("customer/address");
            // you need a customer object here, or simply the ID as a string.
            $add->setCustomerId(cust_id($address->customer));
            $add->setFirstname(cust_fname(cust_id($address->customer)));
            $add->setLastname(cust_sname(cust_id($address->customer)));
            $add->setCountryId(replace(replace($address->del_country))); //Country code here
            $add->setStreet($address->del_address);
            $add->setPostcode($address->del_postcode);
            $add->setCity($address->del_city);
            $add->setCounty($address->del_county);

            $add->save();
           
        }
    }
