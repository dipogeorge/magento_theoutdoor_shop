<?php

class Tos_Customerprice_Model_Observer
{
    public function priceChange($observer)
    {
        if(Mage::getSingleton('core/session')->getGroupId() == 0 || Mage::getSingleton('core/session')->getGroupId() == 1){
            
            if(Mage::getSingleton('core/session')->getClearPrice() > 0){
                
                $new_price = Mage::getSingleton('core/session')->getClearPrice();
              
            }
            else{
            
                $new_price = Mage::getSingleton('core/session')->getOurPrice();
            
            }
        }
        elseif(Mage::getSingleton('core/session')->getGroupId() == 4){
            
            if(Mage::getSingleton('core/session')->getClearPrice() > 0){
                
                $new_price = Mage::getSingleton('core/session')->getClearPrice();
              
            }
            else{
            
                $new_price = Mage::getSingleton('core/session')->getBMCPrice(); 
            
            }
        }
        elseif(Mage::getSingleton('core/session')->getGroupId() == 5){
            
            if(Mage::getSingleton('core/session')->getClearPrice() > 0){
                
                $new_price = Mage::getSingleton('core/session')->getClearPrice();
              
            }
            else{
            
                $new_price = Mage::getSingleton('core/session')->getContractPrice(); 
                
            }
        }        

        $p = $observer->getQuoteItem();
        $p->setCustomPrice($new_price)->setOriginalCustomPrice($new_price); 
        
    }
}

