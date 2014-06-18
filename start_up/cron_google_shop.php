<?php

$con = mysqli_connect("localhost","root","People1205","test") or die(mysqli_connect_error());

$query = "SELECT * FROM google_shopping";
$result = mysqli_query($con, $query);

$xml_data =   '<?xml version="1.0" encoding="UTF-8" ?>' . "\r\n" . 
                    '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . "\r\n" . 
                    '<channel>' . "\r\n" .
                    '<title>The Outdoor Shop Outdoor Equipment and Clothing for Rock climbing, Trekking, Backpacking, Hill Walking, Hiking, Mountaineering, Rambling Outdoor Activities and Adventure Travel</title>' . "\r\n" .
                    '<link>http://www.theoutdoorshop.com</link>' . "\r\n" .
                    '<description>Find a huge range of Clothing and Equipment perfect for Rock Climbing, Trekking, Mountaineering, Backpacking, Hill Walking, Hiking, Rambling and all other types of Adventure Travel &amp; Outdoor Activities | Available to buy online or at our Stacey Bushes Trading Centre store, Milton Keynes</description>' . "\r\n";
 //$count = 1;
while ($row = $result->fetch_assoc()) {
    
   
    if($row['mpn'] != ''){
        
        //echo $row['mpn'].'<br/>';
     
        $xml_data .=
                    '<item>' . "\r\n"  .
                    //'<count>'.$count.'</count>' . "\r\n" .
                    '<title><![CDATA['.$row['title'].']]></title>' . "\r\n" .
                    '<link><![CDATA['.$row['link'].']]></link>' . "\r\n" .
                    '<description><![CDATA['.$row['description'].']]></description>' . "\r\n" .
                    '<g:id><![CDATA['.$row['product_id'].']]></g:id>' . "\r\n" .
                    '<g:price><![CDATA['.$row['price'].' GBP]]></g:price>' . "\r\n" .
                    '<g:image_link><![CDATA['.$row['image_link'].']]></g:image_link>' . "\r\n" .			
                    '<g:shipping_weight><![CDATA['.$row['shipping_weight'].']]></g:shipping_weight>' . "\r\n" .
                    '<g:color><![CDATA['.$row['colour'].']]></g:color>' . "\r\n" .
                    '<g:size><![CDATA['.$row['size'].']]></g:size>' . "\r\n" .
                    '<g:brand><![CDATA['.$row['brand'].']]></g:brand>' . "\r\n" .
                    '<g:mpn><![CDATA['.$row['mpn'].']]></g:mpn>' . "\r\n" .
                    '<g:availability><![CDATA['.$row['availability'].']]></g:availability>' . "\r\n" .
                    '<g:condition><![CDATA['.$row['item_condition'].']]></g:condition>' . "\r\n" .
                    '<g:google_product_category><![CDATA['.$row['google_product_category'].']]></g:google_product_category>' . "\r\n" .
                    '<g:product_type><![CDATA['.$row['product_type'].']]></g:product_type>' . "\r\n" .
                    '<g:age_group><![CDATA['.$row['age_group'].']]></g:age_group>' . "\r\n" .
                    '<g:gender><![CDATA['.$row['gender'].']]></g:gender>' . "\r\n" .
                    '</item>' . "\r\n";
    }
//$count++;
}

$xml_data .= '</channel>' . "\r\n".
                '</rss>' . "\r\n";

$fp = fopen("../files/xml/googlebase.xml","wb");	

fwrite($fp,$xml_data);

fclose($fp);


/* close connection */
mysqli_close($con);