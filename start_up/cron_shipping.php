<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('max_execution_time',0);
ini_set('memory_limit', '-1');

require_once ("../app/Mage.php");

Mage::init();

umask(0); 

$order = Mage::getModel('sales/order')->load(3);
$shipment = Mage::getModel('sales/service_order', $order)->prepareShipment($itemQty);
$shipment = new Mage_Sales_Model_Order_Shipment_Api();

$shipmentId = $shipment->create($order->getIncrementId(), $itemQty, 'Shipment created through ShipMailInvoice', true, true);