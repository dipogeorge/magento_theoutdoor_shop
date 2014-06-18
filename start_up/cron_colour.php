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
    
    $attribute_model        = Mage::getModel('eav/entity_attribute');
    $attribute_code         = $attribute_model->getIdByCode('catalog_product', 'color');
    $attribute              = $attribute_model->load($attribute_code);
    
    //echo '<pre>';
    //print_r($attribute);
    //exit;
    
    function attributeValueExists($arg_attribute, $arg_value)
    {
        $attribute_model        = Mage::getModel('eav/entity_attribute');
        $attribute_options_model= Mage::getModel('eav/entity_attribute_source_table') ;

        $attribute_code         = $attribute_model->getIdByCode('catalog_product', $arg_attribute);
        $attribute              = $attribute_model->load($attribute_code);

        $attribute_table        = $attribute_options_model->setAttribute($attribute);
        $options                = $attribute_options_model->getAllOptions(false);

        foreach($options as $option)
        {
            if ($option['label'] == $arg_value){
                return $option['value'];
            }
        }
        return false;
    }
    
    function seo_friendly($name)
    {

        $name = str_replace(" ", "-", $name);
        $name = str_replace("(", "", $name);
        $name = str_replace(")", "", $name);
        $name = strtolower($name);

        return $name;

    }
    
    function xmlFile() 
    {
        
        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/colour.xml'));
        $xml = simplexml_load_string($content);	

        return $xml;	
    }
    
    $count = 0;
    
    foreach(xmlFile()->Items->Item as $colour){
                
        if(!attributeValueExists('color', $colour->COLOUR)){
        
        
            $value['option'] = array(seo_friendly($colour->COLOUR), $colour->COLOUR);
            $order['option'] = $count;
            $result = array(
                            'value' => $value,
                            'order' => $order
                           );
            $attribute->setData('option',$result);
            $attribute->save();
    
        }
        $count++;
    }
    
 