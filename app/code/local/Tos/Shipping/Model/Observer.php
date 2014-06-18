<?php
class Tos_Shipping_Model_Observer {
	
    public function handleCollect($observer) 
    {
        /** @var Mage_Sales_Model_Quote $quote */
        $quote = $observer->getQuote();
        $someConditions = true; //this can be any condition based on your requirements
        $newHandlingFee = 15;
        $store    = Mage::app()->getStore($quote->getStoreId());
        $carriers = Mage::getStoreConfig('carriers', $store);
        foreach ($carriers as $carrierCode => $carrierConfig) {
            if($carrierCode == 'fedex'){
                if($someConditions){
                    Mage::log('Handling Fee(Before):' . $store->getConfig("carriers/{$carrierCode}/handling_fee"), null, 'shipping-price.log');
                    $store->setConfig("carriers/{$carrierCode}/handling_type", 'F'); #F - Fixed, P - Percentage                  
                    $store->setConfig("carriers/{$carrierCode}/handling_fee", $newHandlingFee);
     
                    ###If you want to set the price instead of handling fee you can simply use as:
                    #$store->setConfig("carriers/{$carrierCode}/price", $newPrice);
     
                    Mage::log('Handling Fee(After):' . $store->getConfig("carriers/{$carrierCode}/handling_fee"), null, 'shipping-price.log');
                }
            }
        }

    } 
}