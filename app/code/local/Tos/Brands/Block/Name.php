<?php

class Tos_Brands_Block_Name extends Mage_Core_Block_Template
{
    
    public function getSomeValue()
    {
        $test = $this->getRequest()->getParam('id');
        return $test;
    
    }
    
    public function getInfo()
    {
        $collection = Mage::getModel('brands/brands')->getCollection()->setOrder('brand_id','asc');
        
        return $collection;
    }

}

