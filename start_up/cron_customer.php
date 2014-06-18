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
    
    function replace($name)
    {
        $replace = str_replace("UK", "GB", $name);
        
        return $replace;
    }
    
    function group_id($name)
    {

        $resource = Mage::getSingleton('core/resource');        

        $readConnection = $resource->getConnection('core_read');       

        $table = $resource->getTableName('customer_group');                

        $query = 'SELECT customer_group_id FROM ' . $table . ' WHERE customer_group_code = "'.$name.'" LIMIT 1';

        $result = $readConnection->fetchOne($query);

        return $result;

    }
    
    function xmlFile() 
    {
        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/customer.xml'));
        $xml = simplexml_load_string($content);	

        return $xml;	
    }
    
    foreach(xmlFile()->Customers->Customer as $customer){
        
        //echo $customer->initials.'<br/>';
        
        if(duplication($customer->customer) != $customer->customer){
//
            $cust = Mage::getModel("customer/customer");
            $cust->setWebsiteId(Mage::app()->getWebsite()->getId());
            $cust->setStore(Mage::app()->getStore());

            $cust->setFirstname($customer->initials);
            $cust->setLastname($customer->full_name);
            $cust->setEmail($customer->email_address);
            $cust->setPasswordHash(md5("chocolate"));
            $cust->setData('group_id', group_id($customer->cust_source));
            $cust->setData('elucid_code', $customer->customer);
            $cust->setData('cust_type', $customer->cust_type);
            $cust->save();

            $address = Mage::getModel("customer/address");
            // you need a customer object here, or simply the ID as a string.
            $address->setCustomerId($cust->getId());
            $address->setFirstname($cust->getFirstname());
            $address->setLastname($cust->getLastname());
            $address->setCountryId(replace($customer->main_country)); //Country code here
            $address->setStreet($customer->main_address);
            $address->setPostcode($customer->main_postcode);
            $address->setCity($customer->main_city);
            $address->setTelephone($customer->phone_day);
            $address->setIsDefaultBilling(true);

            $address->save();
//            
        }
    }
