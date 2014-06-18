<?php

//phpinfo(); 

//exit;
    ini_set('max_execution_time',0);

    $server = "217.19.241.203";
    $user = "RemoteSQLUser";
    $password = "gXSeSl3yOLMM)KYwUl1(";
    $database = "webdb_outdoor "; 

    //connection to the database
    $dbhandle = odbc_connect("Driver={SQL Server};Server=$server;Port=1433;Database=$database;", $user, $password) or die("Couldn't connect to SQL Server on $server"); 
    
    //exit;

    //the SQL statement that will query the database 
      $query = "SELECT 'id' as ColA,
	'condition' as ColB,
	'brand' as colC,
	'title' as colD,
	'description' as colE,
	'price' as colF,
	'link' as colG,
	'image_link' as colH,
	'prod_group' as colI,
	'updated' as colJ,
	'0' as Status,				
	'gtin' as gtin,
	'google_product_category' as gpc,
	'mpn' as mpn,
	'shipping_Weight' as weight,
	'availability' as availability,
	'colour' as colour,
	'material' as material,
	'pattern' as pattern, 
	'size' as size,
	'age group' as age_group,
	'gender' as gender,
	'adwords_redirect' as adwords_redirect,
        'web_cat' as web_cat
	UNION
	SELECT DISTINCT rtrim(epar.part) as ColA, 
	 	'new' as ColB, 
		rtrim(summary) as colC,
		rtrim(epar.descr) as colD, 
		rtrim(isNull(full_descr,'')) as colE,
		cast(min(plis_brks.price) as varchar) as colF,	  
		'http://www.theoutdoorshop.com/showPart.asp?part='+LTRIM(RTRIM(epar.part))+'&utm_source=Product_feed&utm_medium=google&utm_campaign=1' as colG,
		'http://www.theoutdoorshop.com/products/img/small/'+LTRIM(RTRIM(epar.part))+'.jpg' as colH,
		rtrim(summary) as colI, 
		CAST (CONVERT(varchar(50),epar.last_updated,112) as varchar) as colJ, 
		'1' AS Status,
		isNull(gtin,'') as gtin,	
		isNull(gpc,'') as gpc,	
		egtin.mpn as mpn,
		CAST (CONVERT(varchar(50),epar_ship.weight,112) as varchar) as weight,
		'in stock' as availability,
		'' as colour,
		'' as material,
		'' as pattern, 
		'' as size,
		'' as age_group,
		'' as gender,
		'http://www.theoutdoorshop.com/showPart.asp?part='+LTRIM(RTRIM(epar.part))+'&utm_source=Product_feed&utm_medium=google&utm_campaign=1@id={adwords_producttargetid}' as adwords_redirect,
                wgrp_part.prod_group as web_cat
		FROM epar    
		JOIN epar_stoc on epar.part = epar_stoc.part
		JOIN epar_ship on epar.part = epar_ship.part      
		JOIN plis_brks ON epar.part = plis_brks.part 
		JOIN wgrp_part on epar.part = wgrp_part.part 
		JOIN wgrp nme on wgrp_PART.prod_group = nme.prod_group
		LEFT JOIN tblEPARGTIN egtin on epar.part = egtin.part
		WHERE epar.status>0  
		AND (plis_brks.price_list='OUR_PRICE'  OR  plis_brks.price_list='SRP'  OR  plis_brks.price_list='CLEAR')
		AND (egtin.verified >0 AND ((egtin.gtin <> '' AND egtin.gtin is not NULL) OR (egtin.mpn <> '' AND egtin.mpn is not NULL)) AND (epar_ship.weight > 0 AND epar_ship.weight is not NULL) )
		AND (egtin.gpc <> '' AND egtin.gpc is not NULL)
		AND (epar_stoc.stock > 0 OR epar_stoc.stock = -999) 
		AND epar.part NOT IN (SELECT Part FROM tblExclusionParts) 
		AND wgrp_PART.prod_group = (SELECT top 1 wgrp.prod_group from wgrp WHERE wgrp.prod_group=nme.prod_group AND isNumeric(wgrp.prod_group)=1) 
                AND epar.part != 'id'
		GROUP BY epar.part, summary, epar.descr, full_descr, nme.descr, epar.last_updated,egtin.gtin,gpc,egtin.mpn,epar_ship.weight,wgrp_part.prod_group
		ORDER BY Status"; 
      //perform the query 
      $result = odbc_exec($dbhandle, $query);
      $data = odbc_fetch_row($result, 0);      
      $count_field = odbc_num_fields($result);
      
function replace($var)
{
    //$search = array('&', '>', '<', '360°');
    //$replace = array('&amp;', '&gt;', '&lt;', '360&deg;');
    //$result = str_replace($search, $replace, $var);  
    $result = htmlentities($var, ENT_IGNORE, 'UTF-8');
    
    return $result;
}



//echo replace('Now compatible with the all-condition adaptability of MSR`s Modular Flotation tails, their most aggressive snowshoes are built on a solid foundation of their advanced, 360° Traction frames and deliver a level of ultralight security that tubular frames simply can`t - especially on traverses. New, dual-component, PosiLock AT bindings offer MSR`s most secure attachment, while aggressive steel cross members, Pivot Crampons and new, easily-engaged Ergo Televators back you up with every step.');
//exit;

function product_colour($colour)
{
    if($colour == ''){
        $result = 'Blue,Violet,Orange,Midnight';
    }
    
    return $result;
}

function product_size($size)
{
    if($size == ''){
        $result = 'Small,Medium,Large,XLarge,XXLarge';
    }
    
    return $result;
}

function product_age($age)
{
    if($age == ''){
        $result = 'adult';
    }
    
    return $result;
}

function product_gender($gender)
{
    if($gender == ''){
        $result = 'unisex';
    }
    
    return $result;
}

function cat_name($cat_id)
{
    $con = mysqli_connect("localhost","root","People1205","csv_db") or die(mysqli_connect_error());
    $query = "SELECT cat_name FROM category WHERE id = '".$cat_id."'";
    $result = mysqli_query($con, $query);
    
    while ($row = $result->fetch_assoc()) {
        
        $info = $row['cat_name'];
    }
    
    return $info;
    mysqli_close($con);
    
    
}

function parent_cat($cat_id)
{
    $con = mysqli_connect("localhost","root","People1205","csv_db") or die(mysqli_connect_error());
    $query = "SELECT parent_id FROM category WHERE id = '".$cat_id."'";
    $result = mysqli_query($con, $query);
    
    while ($row = $result->fetch_assoc()) {
        
        if($row['parent_id'] != 0){
            $info = cat_name($row['parent_id']).'>'.cat_name($cat_id);
        }
        else{
            
            $info = cat_name($cat_id);
            
        }
    }
    
    return $info;
    mysqli_close($con);
}

function product_type($cat_code)
{
    
    $con = mysqli_connect("localhost","root","People1205","csv_db") or die(mysqli_connect_error());
    $query = "SELECT * FROM category WHERE cat_code = '".$cat_code."'";
    $result = mysqli_query($con, $query);
    
    if($result->num_rows){
        while ($row = $result->fetch_assoc()) {
        
            if($row['parent_id'] == 0){
                $info = $row['cat_name'];
            }
            elseif($row['parent_id'] != 0){
                $info = parent_cat($row['parent_id']).'>'.$row['cat_name'];
            }
        }
    }
    else{
        //mysqli_query($con, "INSERT INTO missing_category_code (cat_code, date) VALUES ('".$cat_code."', '".date('d-m-Y')."')");
        $info =  'Home>Winter Clearance Sale>Clearance Accessories';
    }
    return $info;
    mysqli_close($con);
}

//echo parent_cat( );
//echo product_type(150081);
//exit;

//$query = "SELECT * FROM google_shopping";
//$result = mysqli_query($con, $query);

$xml_data =   '<?xml version="1.0" encoding="UTF-8" ?>' . "\r\n" . 
                    '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . "\r\n" . 
                    '<channel>' . "\r\n" .
                    '<title>The Outdoor Shop Outdoor Equipment and Clothing for Rock climbing, Trekking, Backpacking, Hill Walking, Hiking, Mountaineering, Rambling Outdoor Activities and Adventure Travel</title>' . "\r\n" .
                    '<link>http://www.theoutdoorshop.com</link>' . "\r\n" .
                    '<description>Find a huge range of Clothing and Equipment perfect for Rock Climbing, Trekking, Mountaineering, Backpacking, Hill Walking, Hiking, Rambling and all other types of Adventure Travel &amp; Outdoor Activities | Available to buy online or at our Stacey Bushes Trading Centre store, Milton Keynes</description>' . "\r\n";
 

while (odbc_fetch_row($result)) { 
          
//         $count = 1; 
//         
//         while($count <= 23){
//             
//             //if(odbc_result($result, 12) != ''){
//             
//             echo $count.' - '.odbc_field_name($result, $count).' - '.odbc_result($result, $count).'<br/>';
//             
//             //}
//         
//         $count++;    
//         }
//       exit;
   
    if(odbc_result($result, 1) != 'id' && !empty(odbc_result($result, 14))){
     
        $xml_data .=
                    '<item>' . "\r\n"  .
                    //'<count>'.$count.'</count>' . "\r\n" .
                    '<title><![CDATA['.replace(odbc_result($result, 4)).']]></title>' . "\r\n" .
                    '<link><![CDATA['.odbc_result($result, 7).']]></link>' . "\r\n" .
                    '<description><![CDATA['.replace(odbc_result($result, 5)).']]></description>' . "\r\n" .
                    '<g:id><![CDATA['.odbc_result($result, 1).']]></g:id>' . "\r\n" .
                    '<g:price><![CDATA['.odbc_result($result, 6).' GBP]]></g:price>' . "\r\n" .
                    '<g:image_link><![CDATA['.odbc_result($result, 8).']]></g:image_link>' . "\r\n" .			
                    '<g:shipping_weight><![CDATA['.odbc_result($result, 15).']]></g:shipping_weight>' . "\r\n" .
                    '<g:color><![CDATA['.product_colour(odbc_result($result, 17)).']]></g:color>' . "\r\n" .
                    '<g:size><![CDATA['.product_size(odbc_result($result, 20)).']]></g:size>' . "\r\n" .
                    '<g:age_group><![CDATA['.product_age(odbc_result($result, 21)).']]></g:age_group>' . "\r\n" .
                    '<g:gender><![CDATA['.product_gender(odbc_result($result, 22)).']]></g:gender>' . "\r\n" .
                    '<g:brand><![CDATA['.replace(odbc_result($result, 3)).']]></g:brand>' . "\r\n" .
                    '<g:mpn><![CDATA['.odbc_result($result, 14).']]></g:mpn>' . "\r\n" .
                    '<g:availability><![CDATA['.odbc_result($result, 16).']]></g:availability>' . "\r\n" .
                    '<g:condition><![CDATA['.odbc_result($result, 2).']]></g:condition>' . "\r\n" .
                    '<g:google_product_category><![CDATA['.replace(odbc_result($result, 13)).']]></g:google_product_category>' . "\r\n" .
                    '<g:product_type><![CDATA['.replace(product_type(odbc_result($result, 24))).']]></g:product_type>' . "\r\n" .
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
//mysqli_close($con);