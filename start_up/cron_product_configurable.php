<?php

/*

 * Developer: Dipo George

 * Function: Update Configurable Product

 * @category    The Outdoor Shop

 * @package     Configurable Product Update

 * @copyright   Copyright (c) 2014 The Outdoor Shop. (http://www.theoutdoorshop.com)

 */

    ini_set('max_execution_time',0);

    require_once ("../app/Mage.php");

    Mage::init();

    umask(0);
    
   $data = array(
                '0' =>array(
                            'id'=>NULL,
                            'label' => 'Media Format',
                            'position'=> NULL,
                            'values'=> array(
                                            '0' => array(
                                                        'value_index' => 5,
                                                        'label'=> 'vhs',
                                                        'is_percent' => 0,
                                                        'pricing_value' => '0',
                                                        'attribute_id' => '491'
                                                        ),
                                            '1' => array(
                                                        'value_index' => 6,
                                                        'label' => 'dvd',
                                                        'is_percent' => 0,
                                                        'pricing_value'=> '0',
                                                        'attribute_id' => '491'
                                                        )
                                ),
                            'attribute_id' => 491,
                            'attribute_code' => 'media_format',
                            'frontend_label' => 'Media Format',
                            'html_id'=>'config_super_product__attribute_0')
                );

        $product->setConfigurableAttributesData($data);
    exit;
    
    $sp[0]['size'] = 'Small';
    $sp[0]['color'] = 'green';
    $sp[0]['name'] = 'Some tshirt with size Small';  
    $sp[0]['sku'] = 'tshirt_small';  
    $sp[0]['weight'] = 1;   
    $sp[0]['attribute_set_id'] = 10;   
    $sp[0]['description'] = 'descritpion about the product'; 
    $sp[0]['short_description'] = 'one line description about the product';    
    $sp[0]['type_id'] = Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE;       
    $sp[0]['status'] = Mage_Catalog_Model_Product_Status::STATUS_ENABLED;
    $sp[0]['visibility'] = Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE;         
    $sp[0]['tax_class_id'] = 0;      
    $sp[0]['price'] = 5.99; 
    $sp[0]['stock_data'] = array(   
                             'manage_stock' => 1,
                             'is_in_stock' => 1,
                             'qty' => 10,
                             'use_config_manage_stock' => 0
                 );
    $sp[0]['category_ids'] = array(2,3,5,7);
    
    
    $sp[0]['size'] = 'Extra Small';
    $sp[0]['color'] = 'green';
    $sp[1]['name'] = 'Some tshirt with size Extra Small';
    $sp[1]['sku'] = 'tshirt_xsmall';
    $sp[1]['weight'] = 1;
    $sp[1]['attribute_set_id'] = 10;
    $sp[1]['description'] = 'descritpion about the product';
    $sp[1]['short_description'] = 'one line description about the product';
    $sp[1]['type_id'] = Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE;
    $sp[1]['status'] = Mage_Catalog_Model_Product_Status::STATUS_ENABLED;
    $sp[1]['visibility'] = Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE;
    $sp[1]['tax_class_id'] = 0;
    $sp[1]['price'] = 4.99;
    $sp[1]['stock_data'] = array(
                            'manage_stock' => 1,
                            'is_in_stock' => 1,
                            'qty' => 11,
                            'use_config_manage_stock' => 0
                );
    $sp[1]['category_ids'] = array(2,3,5,7);
    
    //echo '<pre>';
    //print_r($sp);
    
    /**    returns the option id for any attribute code by passing the label
     * $attribute_code e.g. 'size','color','article'
     * $label e.g. 'M','Red','art_21312'
     **/  
    
    
    
    //echo getOptionId('size', 'Extra Small');
    //exit;
    
    $simpleProducts = array();     
    
        foreach($sp as $product){
            
            $sProduct = Mage::getModel('catalog/product');
            $sProduct->setName($product['name']);
            $sProduct->setSku($product['sku']);
            $sProduct->setWeight($product['weight']);
            $sProduct->setAttributeSetId($product['attribute_set_id']);
            $sProduct->setDescription($product['description']);
            $sProduct->setShortDescription($product['short_description']);
            $sProduct->setTypeId($product['type_id'])
                     ->setWebsiteIds(array(1))
                     ->setStatus($product['status'])
                     ->setVisibility($product['visibility'])
                     ->setTaxClassId($product['tax_class_id']);
            $sProduct->setPrice($product['price']);
            $sProduct->setStockData($product['stock_data']);
            $sProduct->setCategoryIds($product['category_ids']);
            $sizeId = getOptionId('size',$product['size']);
            $colorId = getOptionId('color',$product['color']);
            $sProduct->setData('size',$sizeId);
             $sProduct->setData('color',$colorId);
            $sProduct->save();
 
        //we are creating an array with some information which will be used to bind the simple products with the configurable
        
        array_push(
                    $simpleProducts, array( 
                                            "id" => $sProduct->getId(), 
                                            "price" => $sProduct->getPrice(), 
                                            "attr_code" => $configurable_attribute, 
                                            "attr_id" => $attr_id, 
                                            "value" => getOptionId($configurable_attribute, $attr_value), 
                                            "label" => $attr_value
                                            )
				);
        
    }
    //exit;
    
    $cProduct = Mage::getModel('catalog/product');          
    $productData = array(
                        'name' => 'Main configurable Tshirt',
                        'sku' > 'tshirt_sku',
                        'description' => 'Clear description about your Tshirt that explains its features',
                        'short_description' => 'One liner',
                        'weight' => 1,
                        'status' => 1,
                        'visibility' => 4,
                        'attribute_set_id' => 4,
                        'type_id' => 'configurable',
                        'price' => 5.99,
                        'tax_class_id' => 0,
                        'page_layout' => 'one_column',
                         );
    foreach($productData as $key => $value)
    {
    	$cProduct->setData($key,$value);
    }
    $cProduct->setWebsiteIds(array(1));
    $cProduct->setStockData(array(
                                'manage_stock' => 1,
                                'is_in_stock' => 1,
                                'qty' => 0,
                                'use_config_manage_stock' => 0
                                )
                            );
    
    $cProduct->setCategoryIds(array(2,3,5,7));
    
    $cProduct->setCanSaveConfigurableAttributes(true);
    $cProduct->setCanSaveCustomOptions(true);
 
    $cProductTypeInstance = $cProduct->getTypeInstance();
    $attribute_ids = array(139, 92);
    $cProductTypeInstance->setUsedProductAttributeIds($attribute_ids);
    $attributes_array = $cProductTypeInstance->getConfigurableAttributesAsArray();
    foreach($attributes_array as $key => $attribute_array) 
    {
        $attributes_array[$key]['use_default'] = 1;
        $attributes_array[$key]['position'] = 0;

        if (isset($attribute_array['frontend_label']))
        {
            $attributes_array[$key]['label'] = $attribute_array['frontend_label'];
        }
        else {
            $attributes_array[$key]['label'] = $attribute_array['attribute_code'];
        }
    }
    // Add it back to the configurable product..
    $cProduct->setConfigurableAttributesData($attributes_array);

    $dataArray = array();
    foreach ($simpleProducts as $simpleArray)
    {
        $dataArray[$simpleArray['id']] = array();
        foreach ($attributes_array as $key => $attrArray)
        {
            array_push(
                $dataArray[$simpleArray['id']],
                array(
                    "attribute_id" => $simpleArray['attr_id'][$key],
                    "label" => $simpleArray['label'][$key],
                    "is_percent" => 0,
                    "pricing_value" => $simpleArray['pricing_value'][$key]
                )
            );
        }
    }
    $cProduct->setConfigurableProductsData($dataArray);



