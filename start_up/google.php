<?php

ini_set('max_execution_time',0);

$con = mysqli_connect("localhost","root","People1205","csv_db") or die(mysqli_connect_error());

    function xmlFile() 
    {
        $content = utf8_encode(file_get_contents('http://localhost/projects/magento/files/xml/google_shopping.xml'));
        $xml = simplexml_load_string($content);
		
        return $xml;	
    }
    
	foreach(xmlFile()->Googles->Google as $google){
            
            //if($google->master_part == ''){
	
		//echo $google->id.'<br/>';
                $title = str_replace("'", "`", $google->title );
                $desc = str_replace("'", "`", $google->description);

                //mysqli_query($con, "INSERT INTO test (code, desc) VALUES ('".odbc_result($result, 4)."', '".odbc_result($result, 10)."')");
                mysqli_query($con, "INSERT INTO google_shop (product_id, title, description, price, image_link, link, shipping_weight, item_group_id, brand, mpn, item_condition, availability, google_product_category, product_type) VALUES ('".$google->id."', '".$title."', '".$desc."', '".$google->price."', '".$google->image_link."', '".$google->link."', '".$google->shipping_weight."', '".$google->master_part."', '".$google->brand."', '".$google->mpn."', '".$google->condition."', '".$google->availability."', '".$google->google_product_category."', '".$google->product_type."')") or die(mysqli_error($con));
                
            //}

    }

//$result = mysqli_query($con, "SELECT * FROM `google_shop` WHERE item_group_id = '' AND price = '0.00'");
//
//while($row = $result->fetch_array())
// {
//    echo $row['product_id'].'<br/>';
// }
//
//echo '<pre>';
//print_r($row);
   
mysqli_close($con);

	