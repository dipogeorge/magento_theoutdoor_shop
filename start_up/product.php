<?php

// Create connection
$con = mysqli_connect("localhost","root","People1205","csv_db");

$xml_data = 	
            '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" . 
                    '<root>' . "\r\n";     

                    $result = mysqli_query($con, "select * from product");

                    $xml_data .='<Items>' . "\r\n";

                            while($row = mysqli_fetch_array($result)) {

                                    $xml_data .= 
                                                '<Item>' . "\r\n" .
                                                '<ID>' . $row['id'] . '</ID>' . "\r\n" .
                                                '<CODE><![CDATA[' . $row['product_code']  . ']]></CODE>' . "\r\n" .
                                                '<CATCODE>' . $row['cat_code']  . '</CATCODE>' . "\r\n" .
                                                '<SDESC><![CDATA[' . str_replace("&", "&amp;", $row['short_description'])  . ']]></SDESC>' . "\r\n" .
                                                '<DESC><![CDATA[' . str_replace("&", "&amp;", $row['description'])  . ']]></DESC>' . "\r\n" .
                                                '<NAME><![CDATA[' . str_replace("&", "&amp;", $row['product_name'])  . ']]></NAME>' . "\r\n" .	
                                                '<SRP>' . $row['srp_price'] . '</SRP>' . "\r\n" .
                                                '<OUR_PRICE>' . $row['our_price'] . '</OUR_PRICE>' . "\r\n" .
                                                '<CLEAR>' . $row['clear_price'] . '</CLEAR>' . "\r\n" .
                                                '<BMC>' . $row['bmc_price'] . '</BMC>' . "\r\n" .
                                                '<CONTRACT>' . $row['contract_price'] . '</CONTRACT>' . "\r\n" .
                                                '<WEIGHT>' . $row['weight'] . '</WEIGHT>' . "\r\n" .
                                                '<QTY>' . $row['qty'] . '</QTY>' . "\r\n" .
                                                '</Item>' . "\r\n";
                            }

            $xml_data .='</Items>' . "\r\n".
                                    '</root>' . "\r\n";
 
    mysqli_close($con);
    //Create the XML file
    $fp = fopen("../files/xml/product.xml","wb");
 
    //Write the XML nodes
    fwrite($fp,$xml_data);
 
    //Close the database connection
    fclose($fp);