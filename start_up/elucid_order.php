<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('max_execution_time',0);
ini_set('memory_limit', '-1');

require_once ("../app/Mage.php");

Mage::init();

umask(0); 

function duplication($name)
{

    $resource = Mage::getSingleton('core/resource');        

    $readConnection = $resource->getConnection('core_read');       

    $table = $resource->getTableName('sales_order_processed');                

    $query = 'SELECT sales_order_ref FROM ' . $table . ' WHERE sales_order_ref = "'.$name.'" LIMIT 1';

    $duplicate = $readConnection->fetchOne($query);

    return $duplicate;

}

//$cust = Mage::getModel('customer/customer')->load(2)->getData();
//$customerData = Mage::getModel('customer/customer')->load(2)->getAddresses();
//$cust_add = Mage::getModel('customer/customer')->load(2);
//$defaultBilling = $cust_add->getDefaultBillingAddress();
//$defaultShipping = $cust_add->getDefaultShippingAddress();
//echo '<pre>';
//print_r($defaultBilling);
//echo '<pre>';
//print_r($customerData);
//echo $cust['elucid_code'];
//echo $customerData->getDefaultBilling();
//exit;


// Set an Admin Session
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
Mage::getSingleton('core/session', array('name' => 'adminhtml'));
$userModel = Mage::getModel('admin/user');
$userModel->setUserId(1);
$session = Mage::getSingleton('admin/session');
$session->setUser($userModel);
$session->setAcl(Mage::getResourceModel('admin/acl')->loadAcl());

$connection = Mage::getSingleton('core/resource')->getConnection('core_write');

/* Get orders collection of pending orders, run a query */
        $collection = Mage::getModel('sales/order')
                        ->getCollection()
                //      ->addFieldToFilter('state',Array('eq'=>Mage_Sales_Model_Order::STATE_NEW))
                        ->addAttributeToSelect('*');

$xml_data = '<?xml version="1.0"?>' . "\r\n" . 
		'<DTD_ORDER>' . "\r\n";     
foreach($collection as $order)
{
    //var_dump(duplication($order->getIncrementId())).'<br/>';
    //echo duplication($order->getIncrementId()).'<br/>';
    //exit;
    
    
    if(duplication($order->getIncrementId()) != $order->getIncrementId()){
    
    echo '<pre>';
    //print_r($order->getShippingDescription());
    //print_r($order->getBase_shipping_amount());
    //print_r($order);
    print_r($order->getAllItems());
    //echo $order->shipping_description;
    exit;
    
    $cust = Mage::getModel('customer/customer')->load($order->customer_id)->getData();
    $address = Mage::getModel('customer/customer')->load($order->customer_id)->getAddresses();
     if ($billingAddress = $order->getBillingAddress()){
        $billingStreet = $billingAddress->getStreet();
    }
    if ($shippingAddress = $order->getShippingAddress()){
        $shippingStreet = $shippingAddress->getStreet();
    }
    //echo  $order->base_subtotal_invoiced;
//    echo '<pre>';
//    print_r($billingAddress);
//    exit();

//        echo '<pre>';
//        print_r($order);
//        //echo $order->state;
//        exit;
    //$xml_data .= "<dat:dataPackItem  version=\"2.0\">\n";
    //$xml_data .= "<dat:dataPackItemversion=\"1.0\">\n";
            $xml_data.= '<OrderHead>' . "\r\n" .
            
            
                '<Method>WEB</Method>' . "\r\n" .
		'<OrderReferences>'.$order->getIncrementId().'</OrderReferences>' . "\r\n" .
		'<Buyer>UNKNOWN</Buyer>' . "\r\n" .
		'<ExternalCustomerCode>'.$cust['elucid_code'].'</ExternalCustomerCode>' . "\r\n" .
		'<OrderDate>'.date('d/m/Y',strtotime($order->getCreatedAt())).'</OrderDate>' . "\r\n" .
		'<InvoiceTitle>'.$billingAddress->getPrefix().'</InvoiceTitle>' . "\r\n" .
		'<InvoiceInitials>'.$billingAddress->getFirstname().'</InvoiceInitials>' . "\r\n" .
		'<InvoiceSurname>'.$billingAddress->getLastname().'</InvoiceSurname>' . "\r\n" .
		'<InvoicePosition></InvoicePosition>' . "\r\n" .
		'<InvoiceCompany>'.$billingAddress->getCompany().'</InvoiceCompany>' . "\r\n" .
		'<InvoicePostCode>'.$billingAddress->getPostcode().'</InvoicePostCode>' . "\r\n" .
		'<InvoiceAddress>'.$billingStreet[0].'</InvoiceAddress>' . "\r\n" .
		'<InvoiceCity>'.$billingAddress->getCity().'</InvoiceCity>' . "\r\n" .
		'<InvoiceCounty>'.$billingAddress->getRegion().'</InvoiceCounty>' . "\r\n" .
		'<InvoiceCountry>'.$billingAddress->getCountry().'</InvoiceCountry>' . "\r\n" .
		'<InvoicePhone>'.$billingAddress->getTelephone().'</InvoicePhone>' . "\r\n" .
		'<InvoiceFax></InvoiceFax>' . "\r\n" .
		'<InvoiceEmail>'.$billingAddress->getEmail().'</InvoiceEmail>' . "\r\n" .
		'<CustomerRef></CustomerRef>' . "\r\n" .
		'<OrdDelChrgGross>'.number_format($order->base_shipping_amount, 2, '.', ',').'</OrdDelChrgGross>' . "\r\n" .
		'<OrdDelChrgNet></OrdDelChrgNet>' . "\r\n" .
		'<OrdDelChrgTax></OrdDelChrgTax>' . "\r\n" .
		'<OrdTotalGross>'.number_format($order->grand_total, 2, '.', ',').'</OrdTotalGross>' . "\r\n" .
		'<OrdTotalNet>'.number_format($order->subtotal, 2, '.', ',').'</OrdTotalNet>' . "\r\n" .
		'<OrdTotalTax>'.number_format($order->tax_amount, 2, '.', ',').'</OrdTotalTax>' . "\r\n" .
		'<OrdDiscPercent></OrdDiscPercent>' . "\r\n" .
		'<OrdDiscValue>0</OrdDiscValue>' . "\r\n" .
		'<OrderSource>WEB</OrderSource>' . "\r\n" .
		'<Currency>GBP</Currency>' . "\r\n" .
		'<Allow_Mailshot>F</Allow_Mailshot>' . "\r\n" .
		'<Allow_Recipient_Mail>F</Allow_Recipient_Mail>' . "\r\n" .
		'<Allow_Mail>F</Allow_Mail>' . "\r\n" .

                    /*
                    $xml_data.= "<ord:orderType>receivedOrder</ord:orderType>\n";
                    $xml_data.= "<ord:numberOrder>".$order->getIncrementId()."</ord:numberOrder>\n";
                    $xml_data.= "<ord:date>".date('Y-m-d',strtotime($order->getCreatedAt()))."</ord:date>\n";
                    $xml_data.= "<ord:dateFrom>".date('Y-m-d',strtotime($order->getCreatedAt()))."</ord:dateFrom>\n";
                    $xml_data.= "<ord:dateTo>".date('Y-m-d',strtotime($order->getCreatedAt()))."</ord:dateTo>\n";
                    $xml_data.= "<ord:text>Objednávka z internetového obchodu</ord:text>\n";
                    $xml_data.= "<ord:partnerIdentity>\n";
                    $xml_data.= "<typ:address>\n";
                    $xml_data.= "<typ:company>{$billingAddress->getCompany()}</typ:company>\n";
                    $xml_data.= "<typ:division></typ:division>\n";
                    $xml_data.= "<typ:name>{$billingAddress->getName()}</typ:name>\n";
                    $xml_data.= "<typ:city>{$billingAddress->getCity()}</typ:city>\n";
                    $xml_data.= "<typ:street>{$billingStreet[0]}</typ:street>\n";
                    $xml_data.= "<typ:zip>{$billingAddress->getPostcode()}</typ:zip>\n";
                    $xml_data.= "</typ:address> \n";
                    $xml_data.="<typ:shipToAddress>\n";
                    $xml_data.= "<typ:company>{$shippingAddress->getCompany()}</typ:company>\n";
                    $xml_data.= "<typ:division></typ:division>\n";
                    $xml_data.= "<typ:name>{$shippingAddress->getName()}</typ:name>\n";
                    $xml_data.= "<typ:city>{$shippingAddress->getCity()}</typ:city>\n";
                    $xml_data.= "<typ:street>{$shippingStreet[0]}</typ:street>\n";
                    $xml_data.= "<typ:zip>{$shippingAddress->getPostcode()}</typ:zip>\n";
                    $xml_data.= "</typ:shipToAddress>\n";
                    $xml_data.= "</ord:partnerIdentity>\n";
                    $xml_data.= "<ord:paymentType> \n";
                    $xml_data.= "<typ:ids>{$order->getShippingDescription()}</typ:ids>\n";
                    $xml_data.= "</ord:paymentType>\n";
                    $xml_data.= "<ord:priceLevel>\n";
                    $xml_data.= "<typ:ids></typ:ids>\n";
                    $xml_data.= "</ord:priceLevel>\n";
                    $xml_data.= "</ord:orderHeader>\n";
                    $xml_data.= "<ord:orderDetail> \n";
                    */
                    
                '<OrderRecipient>' . "\r\n" .
                        '<RecipientDelivery>1</RecipientDelivery>' . "\r\n" .
			'<RecipientOrderType />' . "\r\n" .
			'<RecipientTitle>'.$shippingAddress->getPrefix().'</RecipientTitle>' . "\r\n" .
			'<RecipientInitials>'.$shippingAddress->getFirstname().'</RecipientInitials>' . "\r\n" .
			'<RecipientSurname>'.$shippingAddress->getLastname().'</RecipientSurname>' . "\r\n" .
			'<RecipientPosition />' . "\r\n" .
			'<RecipientAddressRef>MAIN</RecipientAddressRef>' . "\r\n" .
			'<RecipientCompany />' . "\r\n" .
			'<RecipientAddress>'.$shippingAddress[0].'</RecipientAddress>' . "\r\n" .
			'<RecipientCity>'.$shippingAddress->getCity().'</RecipientCity>' . "\r\n" .
			'<RecipientCounty></RecipientCounty>' . "\r\n" .
			'<RecipientPhone>'.$shippingAddress->getTelephone().'</RecipientPhone>' . "\r\n" .
			'<RecipientPostCode>'.$shippingAddress->getPostcode().'</RecipientPostCode>' . "\r\n" .
			'<RecipientCountry>'.$shippingAddress->getCountry_id().'</RecipientCountry>' . "\r\n" .
			'<RecipientMessage></RecipientMessage>' . "\r\n" .
			'<RecipientOpenMessage></RecipientOpenMessage>' . "\r\n" .
			'<RecipientDelMessage></RecipientDelMessage>' . "\r\n" .
			'<RecipientDelChrgGross>'.$order->base_shipping_amount.'</RecipientDelChrgGross>' . "\r\n" .
			'<RecipientDelChrgNet></RecipientDelChrgNet>' . "\r\n" .
			'<RecipientDelChrgTax></RecipientDelChrgTax>' . "\r\n" .
			'<RecipientCarrier>PF#</RecipientCarrier>' . "\r\n" .
			'<RecipientCarrierService>PF48#</RecipientCarrierService>' . "\r\n" .
			'<RecipientArrivalDate>'.date('d/m/Y').'</RecipientArrivalDate>' . "\r\n" .
			'<RecipientDespatchDate />' . "\r\n" .
			'<RecipientCarrierFixed>FIXED</RecipientCarrierFixed>' . "\r\n";
                foreach ($order->getAllItems() as $itemId => $item){
                    
                    //$itemname =  $item->getName();
                    //$itemname =  str_replace('&', "and", $itemname);
                    if($item->getTax_percent() < 0){
                        $tax_amount = $item->getTax_percent()/100; 
                    }else{
                        $tax_amount = 0.2;
                    }
                    
                    $line_number = $itemId + 1;
                    
                    $total_price = $item->getRow_total();
                    $total_vat = $item->getRow_total()*$tax_amount;
                    
                    $xml_data.= 
                            '<OrderLine>' . "\r\n" .
                                '<LineNumber>'.$line_number.'</LineNumber>' . "\r\n" .
                                '<Product>'.$item->getSku().'</Product>' . "\r\n" .
                                '<CustomerPart></CustomerPart>' . "\r\n" .
                                '<Quantity>'.$item->getQtyOrdered().'</Quantity>' . "\r\n" .
                                '<Price>'.number_format($item->getPrice(), 2, '.', ',').'</Price>' . "\r\n" .
                                '<DiscValue></DiscValue>' . "\r\n" .
                                '<LineTotalGross>'.number_format($total_price, 2, '.', ',').'</LineTotalGross>' . "\r\n" .
                                '<LineTotalNet>'.number_format($total_price-$total_vat, 2, '.', ',').'</LineTotalNet>' . "\r\n" .
                                '<LineTotalTax>'.number_format($total_vat, 2, '.', ',').'</LineTotalTax>' . "\r\n" .
                                '<Personalisation></Personalisation>' . "\r\n" .
                            '</OrderLine>' . "\r\n";
                    
                    
                    
                    
                    // textova polozka
                   /*
                   $xml_data.= "<ord:orderItem> \n";
                        $itemname =  $item->getName();
                        $itemname =  str_replace('&', " ", $itemname);
                        $xml_data.= "<ord:text>{$itemname}</ord:text> \n";
                        $xml_data.= "<ord:quantity>{$item->getQtyOrdered()}</ord:quantity>\n";
                        //$xml_data.= "<ord:delivered></ord:delivered>";
                        $xml_data.= "<ord:rateVAT>high</ord:rateVAT> \n";
                        $xml_data.= "<ord:homeCurrency> \n";
                        $xml_data.= "<typ:unitPrice>{$item->getPrice()}</typ:unitPrice>\n";
                        $xml_data.= "</ord:homeCurrency>\n";
                        $xml_data.= "<ord:stockItem>\n";
                        $xml_data.= "<typ:stockItem>\n";
                        $xml_data.= "<typ:ids>{$item->getSku()}</typ:ids>\n";
                        $xml_data.= "</typ:stockItem>\n";
                        $xml_data.= "</ord:stockItem>\n";
                    $xml_data.= "</ord:orderItem>\n";
                    */
                }
                $xml_data.= 
                        '</OrderRecipient>' . "\r\n" .                        
                        '<OrderPayment>' . "\r\n" .
                            '<CardType></CardType>' . "\r\n" .
                            '<CardNumber></CardNumber>' . "\r\n" .
                            '<IssueNumber></IssueNumber>' . "\r\n" .
                            '<ExpiryDate></ExpiryDate>' . "\r\n" .
                            '<IssueDate></IssueDate>' . "\r\n" .
                            '<Amount></Amount>' . "\r\n" .
                            '<AuthCode></AuthCode>' . "\r\n" .
                            '<AuthMessage></AuthMessage>' . "\r\n" .
                            '<AuthStatus></AuthStatus>' . "\r\n" .
                        '</OrderPayment>' . "\r\n" .
                        
                /*
                $xml_data.= "<ord:orderSummary>\n";
                $xml_data.= "<ord:roundingDocument>math2one</ord:roundingDocument>\n";
                $xml_data.= "</ord:orderSummary>\n";
                */
            '</OrderHead>' . "\r\n";
        //$xml_data.= "</dat:dataPackItem>\n";
    }            
            
};

$xml_data.=     '</DTD_ORDER>' . "\r\n";

$fp = fopen("../files/xml/elucid_order.xml","wb");	

fwrite($fp,$xml_data);

fclose($fp);

?>