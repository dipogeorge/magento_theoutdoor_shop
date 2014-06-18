<?php

class Tos_Brands_Block_Index extends Mage_Core_Block_Template
{
    
    public function getSomeValue()
    {
        $test = $this->getRequest()->getParam('dipo');
        return $test;
    
    }
    
    public function getInfo()
    { 
        
        $collection = Mage::getModel('brands/brands')->getCollection();
		
        $collection->addFieldToFilter('parent_id', 0);
        $brands = $collection->getData();
        return $brands;
    }

}