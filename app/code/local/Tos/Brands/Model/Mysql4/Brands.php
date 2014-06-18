<?php

class Tos_Brands_Model_Mysql4_Brands extends Mage_Core_Model_Mysql4_Abstract{
    
    public function _construct()
    {
        $this->_init('brands/brands', 'entity_id');
    }   
}
