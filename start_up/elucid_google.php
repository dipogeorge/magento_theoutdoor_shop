<?php

//phpinfo(); 

//exit;
    ini_set('max_execution_time',0);
    
    $con = mysqli_connect("localhost","root","People1205","test") or die(mysqli_connect_error());
    
    $server = "217.19.241.203";
    $user = "RemoteSQLUser";
    $password = "gXSeSl3yOLMM)KYwUl1(";
    $database = "webdb_outdoor "; 

    //connection to the database
    $dbhandle = odbc_connect("Driver={SQL Server};Server=$server;Port=1433;Database=$database;", $user, $password) or die("Couldn't connect to SQL Server on $server");

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
		GROUP BY epar.part, summary, epar.descr, full_descr, nme.descr, epar.last_updated,egtin.gtin,gpc,egtin.mpn,epar_ship.weight,wgrp_part.prod_group
		ORDER BY Status"; 
       
      $result = odbc_exec($dbhandle, $query);
      $data = odbc_fetch_row($result, 0);      
      $count_field = odbc_num_fields($result);
      
      
      
function replace($var)
{
    $result = htmlentities($var, ENT_IGNORE, 'UTF-8');
    
    return $result;
}



//echo replace('Now compatible with the all-condition adaptability of MSR`s Modular Flotation tails, their most aggressive snowshoes are built on a solid foundation of their advanced, 360° Traction frames and deliver a level of ultralight security that tubular frames simply can`t - especially on traverses. New, dual-component, PosiLock AT bindings offer MSR`s most secure attachment, while aggressive steel cross members, Pivot Crampons and new, easily-engaged Ergo Televators back you up with every step.');
//exit;

function product_colour($colour)
{
    if($colour == ''){
        $result = 'As Seen';
    }
    
    return $result;
}

function product_size($size)
{
    if($size == ''){
        $result = 'One Size';
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
            $info = cat_name($row['parent_id']).' > '.cat_name($cat_id);
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
                $info = parent_cat($row['parent_id']).' > '.$row['cat_name'];
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
     mysqli_query($con, "TRUNCATE TABLE google_shopping");
     mysqli_query($con, "ALTER TABLE google_shopping DROP INDEX `index1`");
     
      while (odbc_fetch_row($result)) { 
//          
//         $count = 1; 
//         
//         while($count <= 20){
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

            $title = str_replace("'", "`", rtrim(odbc_result($result, 4)));
            $desc = rtrim(odbc_result($result, 5));
            $desc = str_replace("'", "`", $desc);
            $price = rtrim(odbc_result($result, 6));          
    
            mysqli_query($con, "INSERT INTO google_shopping (
                   product_id, 
                   title, 
                   description, 
                   price, 
                   image_link, 
                   link, 
                   shipping_weight,
                   brand, 
                   mpn, 
                   item_condition, 
                   availability, 
                   google_product_category, 
                   product_type,
                   colour,
                   size,
                   age_group,
                   gender
                   ) 
                   VALUES 
                   (
                   '".rtrim(odbc_result($result, 1))."',
                   '".replace($title)."',
                   '".replace($desc)."',
                   '".$price."', 
                   '".replace(rtrim(odbc_result($result, 8)))."', 
                   '".replace(rtrim(odbc_result($result, 7)))."', 
                   '".replace(rtrim(odbc_result($result, 15)))."',
                   '".replace(rtrim(odbc_result($result, 3)))."', 
                   '".replace(rtrim(odbc_result($result, 14)))."', 
                   'new', 
                   'in stock', 
                   '".replace(rtrim(odbc_result($result, 13)))."', 
                   '".replace(product_type(rtrim(odbc_result($result, 24))))."',
                   '".product_colour(rtrim(odbc_result($result, 17)))."',
                   '".product_size(rtrim(odbc_result($result, 20)))."',
                   '".product_age(rtrim(odbc_result($result, 21)))."',
                   '".product_gender(rtrim(odbc_result($result, 22)))."'
                   )
                   ") or die(mysqli_error($con));                   
                  
            //$count++;   
          //}
           
        } 
        
        mysqli_query($con, "DELETE FROM google_shopping WHERE product_id = 'id'");
        mysqli_query($con, "ALTER IGNORE TABLE google_shopping ADD UNIQUE KEY index1(product_id)");
        
        mysqli_close($con);
        
         
        

   