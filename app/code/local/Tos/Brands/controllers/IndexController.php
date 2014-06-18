<?php

class Tos_Brands_IndexController extends Mage_Core_Controller_Front_Action
{
    
    public function indexAction()
    {
        $this->loadLayout();
	$this->renderLayout();
        
    }
    
    public function nameAction($var, $name, $test)
    {
        $this->loadLayout();
	$this->renderLayout();
        
    }
    
        
    
}