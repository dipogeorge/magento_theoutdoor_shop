<?php

    ini_set('max_execution_time',0);

    require_once ("../app/Mage.php");

    Mage::init();

    umask(0); 
    
    function duplication($name)
    {

        $resource_dup = Mage::getSingleton('core/resource');        

        $readConnection_dup = $resource_dup->getConnection('core_read');       

        $table_dup = $resource_dup->getTableName('customer_group');                

        $query_dup = 'SELECT customer_group_code FROM ' . $table_dup . ' WHERE customer_group_code = "'.$name.'"';

        $duplicate = $readConnection_dup->fetchOne($query_dup);

        return $duplicate;

    }
    
    function xmlFile() 
    {

        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/customer_group.xml'));
        $xml = simplexml_load_string($content);	

        return $xml;	
    }
    
    foreach(xmlFile()->Groups->Group as $group){
        
        if(duplication($group->source) != $group->source){

            Mage::getSingleton('customer/group')->setData(array(
                                                                'customer_group_code' => $group->source,
                                                                'tax_class_id' => 3
                                                                )
                                                )
            ->save(); 
        }
    }
	
?>