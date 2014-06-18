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
    $query = "SELECT DISTINCT source FROM dbo.cust_trad WHERE source != 'BMC' AND source != 'CONTRACT' ORDER BY source"; 
    //perform the query 
    $result = odbc_exec($dbhandle, $query);
    $data = odbc_fetch_row($result, 0);      
    $count_field = odbc_num_fields($result);
      
      
      $xml_data =  '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" . 
			'<root>' . "\r\n".
                            '<Groups>' . "\r\n";
      
        while (odbc_fetch_row($result)) { 
          
          $count = 1;
          
          $xml_data .= '<Group>' . "\r\n" ;
          
            while ($count <= $count_field) {

                  $xml_data .= '<'.odbc_field_name ($result, $count).'><![CDATA['.rtrim(odbc_result($result, $count)).']]></'.odbc_field_name ($result, $count).'>' . "\r\n";

            $count++;   
            }
          
          $xml_data .= '</Group>' . "\r\n";          
        }
      
      $xml_data .= '</Groups>' . "\r\n".
              '</root>' . "\r\n";

        odbc_close ($dbhandle);

        $fp = fopen("../files/xml/customer_group.xml","wb");	

        fwrite($fp,$xml_data);
        
        fclose($fp);
        

   