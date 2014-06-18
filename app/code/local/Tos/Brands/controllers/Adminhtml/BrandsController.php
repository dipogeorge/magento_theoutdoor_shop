<?php

class Tos_Brands_Adminhtml_BrandsController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        
        $this->loadLayout()
        ->_setActiveMenu('recyclelcd/recyclelcd')
        ->_addBreadcrumb(Mage::helper('adminhtml')->__('Recycle Manager'), Mage::helper('adminhtml')->__('Recycle Manager'));
        return $this;
    
    }

    public function indexAction()
    {
        
        $this->_initAction();
        $this->_addContent($this->getLayout()->createBlock('recyclelcd/adminhtml_recyclelcd'));
        $this->renderLayout();
    
    }

    public function editAction()
    {   
        
        $recyclelcdId = $this->getRequest()->getParam('id');
        $recyclelcdModel = Mage::getModel('recyclelcd/recyclelcd')->load($recyclelcdId);

        if ($recyclelcdModel->getId() || $recyclelcdId == 0) {
            
            Mage::register('recyclelcd_data', $recyclelcdModel);
            $this->loadLayout();
            $this->_setActiveMenu('recyclelcd/items');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('recyclelcd/adminhtml_recyclelcd_edit'))
            ->_addLeft($this->getLayout()->createBlock('recyclelcd/adminhtml_recyclelcd_edit_tabs'));
            $this->renderLayout();
        
        }
        else {
            
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('recyclelcd')->__('Item does not exist'));
            $this->_redirect('*/*/');
        
        }
        
    }

    public function newAction()
    {
        
        $this->_forward('edit');
    
    }

    public function saveAction()
    {
        
        $postData = $this->getRequest()->getPost();
        $recycle_id = $postData['recyclelcd_id'];
        
//        echo '<pre>';
//        echo $postData['recyclelcd_id'];
//        print_r($postData);
//        exit;
        
        if ($this->getRequest()->getPost()){
            
           if(isset($postData['update_order'])){
               
               foreach($this->getRequest()->getPost() as $productID => $value){
               
                if(is_numeric($productID)){
                    
                    $product_info = Mage::getModel('recyclelcd/lcdinfo')->load($productID);
                    $product = $product_info->getData();
                    
                    $write = Mage::getSingleton('core/resource')->getConnection('core_write'); 

                    $result = $write->query("UPDATE mg_recyclelcddetail SET recycle_qty = '".$value."', recycle_total = '".$product['trade_price']*$value."' WHERE  product_id = '".$productID."' AND recyclelcd_id = '".$recycle_id."'");
                   
                }
                
               }
               
                $read = Mage::getSingleton('core/resource')->getConnection('core_read'); 

                $result_prod = $read->fetchAll("select * from mg_recyclelcddetail where recyclelcd_id = '".$recycle_id."'");

                $sum = 0;

                foreach ($result_prod as $subarray)
                {
                 $sum += $subarray['recycle_total'];
                }

                $data = array(
                         'order_total' => $sum
                         );
                $model = Mage::getModel('recyclelcd/recyclelcd')->load($recycle_id)->addData($data);

                $model->setRecyclelcd_id($recycle_id)->save();
               
               echo '<script>alert("Stock Quantity Updated Successfully"); history.back();</script>';
               exit;
               
           }else{
            
            try {
                
                $postData = $this->getRequest()->getPost();
                $recyclelcdModel = Mage::getModel('recyclelcd/recyclelcd');
                $recyclelcdModel->setId($this->getRequest()->getParam('id'))                               
                ->setStatus($postData['status'])
                ->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setRecyclelcdData(false);
                $this->_redirect('*/*/');
                return;
            
            }
            catch (Exception $e) {
                
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setRecyclelcdData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            
            }
           }
            
        }
        $this->_redirect('*/*/');
    
    }
    
    public function deleteAction()
    {
        
        if( $this->getRequest()->getParam('id') > 0 ) {
            
            try {
                
                $recyclelcdModel = Mage::getModel('recyclelcd/recyclelcd');
                $recyclelcdModel->setId($this->getRequest()->getParam('id'))
                ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            
            } 
            catch (Exception $e) {
                
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            
            }
        
        }
        $this->_redirect('*/*/');
    
    }
    /**
    * * Product grid for AJAX request.
    * * Sort and filter result for example.
    * */
    public function gridAction()
    {
        
        $this->loadLayout();
        $this->getResponse()->setBody(
        $this->getLayout()->createBlock('recyclelcd/adminhtml_recyclelcd_grid')->toHtml());
    
    }
}