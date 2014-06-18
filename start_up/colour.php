<?php

// Create connection
$con = mysqli_connect("localhost","root","People1205","csv_db");

$xml_data = 	
				'<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" . 
					'<root>' . "\r\n";     
 
			$result = mysqli_query($con, "SELECT DISTINCT colour FROM `colour_size` ORDER by colour ASC");

					$xml_data .='<Items>' . "\r\n";
					
						while($row = mysqli_fetch_array($result)) {
						
							$xml_data .= 
											'<Item>' . "\r\n" .
											'<COLOUR><![CDATA[' . $row['colour'] . ']]></COLOUR>' . "\r\n" .	
											'</Item>' . "\r\n";
						}
					 
						$xml_data .='</Items>' . "\r\n".
					 '</root>' . "\r\n";
 
    mysqli_close($con);
    //Create the XML file
    $fp = fopen("../files/xml/colour.xml","wb");
 
    //Write the XML nodes
    fwrite($fp,$xml_data);
 
    //Close the database connection
    fclose($fp);
