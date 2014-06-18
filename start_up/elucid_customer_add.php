<?php

//phpinfo(); 

//exit;
    ini_set('max_execution_time',0);

    $server = "192.168.0.2";
    $user = "dipo";
    $password = "People1205";
    $database = "eluciddb"; 

    //connection to the database
    $dbhandle = odbc_connect("Driver={SQL Server};Server=$server;Port=1433;Database=$database;", $user, $password) or die("Couldn't connect to SQL Server on $server"); 

    //the SQL statement that will query the database 
      $query = "SELECT DADD.customer, DADD.address AS del_address, DADD.city AS del_city, DADD.county AS del_county, DADD.postcode AS del_postcode, DADD.country AS del_country
                FROM dbo.cust_addr DADD WHERE DADD.address_ref != 'MAIN'
                --JOIN dbo.cust_addr DCADDM on DC.customer = DCADDM.customer AND DCADDM.address_ref = 'MAIN'
                --JOIN dbo.cust_addr DCADD on DC.customer = DCADD.customer AND DCADD.address_ref = 'DELV'
                --JOIN dbo.cust_attr DCAT on DC.customer = DCAT.customer
                --JOIN dbo.cust_trad DCTR on DC.customer = DCTR.customer
                ORDER BY DADD.customer"; 
      //perform the query 
      $result = odbc_exec($dbhandle, $query);
      $data = odbc_fetch_row($result, 0);      
      $count_field = odbc_num_fields($result);
      
      
      $xml_data =  '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" . 
			'<root>' . "\r\n" .
                            '<Addresses>' . "\r\n";
      
        while (odbc_fetch_row($result)) { 
          
          $count = 1;
          
          $xml_data .= '<Address>' . "\r\n" ;
          
            while ($count <= $count_field) {

                  $xml_data .= '<'.odbc_field_name ($result, $count).'><![CDATA['.rtrim(odbc_result($result, $count)).']]></'.odbc_field_name ($result, $count).'>' . "\r\n";

            $count++;   
            }
          
        $xml_data .= '</Address>' . "\r\n"; 
          
        }
      
      $xml_data .= '</Addresses>' . "\r\n".
              '</root>' . "\r\n";

        odbc_close ($dbhandle);

        $fp = fopen("../files/xml/customer_add.xml","wb");	

        fwrite($fp,$xml_data);
        
        fclose($fp);
        

   