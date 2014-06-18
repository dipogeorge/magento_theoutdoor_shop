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
      $query = 'SELECT DBREL.part AS Part, DBREL.relative AS Related
                FROM dbo.epar_rels DBREL'; 
      //perform the query 
      $result = odbc_exec($dbhandle, $query);
      $data = odbc_fetch_row($result, 0);      
      $count_field = odbc_num_fields($result);
      
      
      $xml_data =  '<?xml version="1.0" encoding="UTF-8"?>' . "\r\n" . 
			'<root>' . "\r\n".
                            '<Products>' . "\r\n";
      
        while (odbc_fetch_row($result)) { 
          
            $count = 1;
          
            $xml_data .= '<Product>' . "\r\n" ;
          
            while ($count <= $count_field) {

                  $xml_data .= '<'.odbc_field_name ($result, $count).'><![CDATA['.rtrim(odbc_result($result, $count)).']]></'.odbc_field_name ($result, $count).'>' . "\r\n";

            $count++;   
            }
          
            $xml_data .= '</Product>' . "\r\n";
          
        }
      
      $xml_data .= '</Products>' . "\r\n".
              '</root>' . "\r\n";

        odbc_close ($dbhandle);

        $fp = fopen("../files/xml/products_rel.xml","wb");	

        fwrite($fp,$xml_data);
        
        fclose($fp);
        

   