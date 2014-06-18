<?php

// Create connection
$con = mysqli_connect("localhost","root","People1205","csv_db");

$xml_data = 	
				'<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" . 
					'<root>' . "\r\n";     
 
			$result = mysqli_query($con, "select * from category");

					$xml_data .='<Items>' . "\r\n";
					
						while($row = mysqli_fetch_array($result)) {
						
							$xml_data .= 
											'<Item>' . "\r\n" .
											'<CATID>OSCAT' . $row['id'] . '</CATID>' . "\r\n" .
											'<PARENTID>' . $row['parent_id']  . '</PARENTID>' . "\r\n" .
											'<CATCODE><![CDATA[' . $row['cat_code']  . ']]></CATCODE>' . "\r\n" .
											'<CATNAME><![CDATA[' . str_replace("&", "&amp;", $row['cat_name'])  . ']]></CATNAME>' . "\r\n" .	
											'</Item>' . "\r\n";
						}
					 
						$xml_data .='</Items>' . "\r\n".
					 '</root>' . "\r\n";
 
    mysqli_close($con);
    //Create the XML file
    $fp = fopen("../files/xml/category.xml","wb");
 
    //Write the XML nodes
    fwrite($fp,$xml_data);
 
    //Close the database connection
    fclose($fp);

