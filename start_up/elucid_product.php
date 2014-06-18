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
      $query = "SELECT      TOP (100) PERCENT supl.supplier AS Supplier, supl.full_name AS [Supplier_Name], faml_pgrp.descr AS Family, part.part AS [Part_Number], part.master_part AS [Master_Part], DSHIP.weight AS [Weight],
                -- dbo.wgrp_part.prod_group AS [Navigation_Group], 
                dbo.epar.tax_code AS [Tax_Code], dbo.epar.part_type AS [Part_type],
                VSUPL_DEFL.supplier_part AS [Supplier_Part], part.descr AS Description, dbo.epar.full_descr AS [Full_Description], vstyle_master_parts4.colour_descr AS Colour, vstyle_master_parts4.size_descr AS Size, 
                ROUND(dbo.supl_brks.price, 2) AS Trade_Price, ROUND(part_trad.part_price, 2) AS SRP, dbo.VOUR_PRICE_PLIS.price AS Our_Price, dbo.VBMC_PLIS.price AS BMC, 
                dbo.VCONTRACT_PLIS.price AS Contract, dbo.VCLEAR_PLIS.price AS Clearance, 
                vods_whse_shop_stock.whse_stock + vods_whse_shop_stock.shop_stock AS Free_Stock

                FROM    dbo.VSUPL_DEFL AS VSUPL_DEFL INNER JOIN
                        dbo.supl_brks ON VSUPL_DEFL.part = dbo.supl_brks.part RIGHT OUTER JOIN
                        dbo.part AS part INNER JOIN
                        --dbo.wgrp_part ON part.part = dbo.wgrp_part.part RIGHT OUTER JOIN
                        dbo.epar ON part.part = dbo.epar.part RIGHT OUTER JOIN                            
                        --JOIN dbo.wgrp_part DCNAV on part.part = DCNAV.part
                        --dbo.wgrp_part AS nav_group INNER JOIN
                        dbo.part_trad AS part_trad ON part.part = part_trad.part LEFT OUTER JOIN
                        dbo.VOUR_PRICE_PLIS ON part.part = dbo.VOUR_PRICE_PLIS.part LEFT OUTER JOIN
                        dbo.VBMC_PLIS ON part.part = dbo.VBMC_PLIS.part LEFT OUTER JOIN
                        dbo.VCONTRACT_PLIS ON part.part = dbo.VCONTRACT_PLIS.part LEFT OUTER JOIN
                        dbo.VCLEAR_PLIS ON part.part = dbo.VCLEAR_PLIS.part LEFT OUTER JOIN
                        dbo.pgrp AS pgrp ON part.prod_group = pgrp.prod_group LEFT OUTER JOIN
                        dbo.vstyle_master_parts4 AS vstyle_master_parts4 ON part.part = vstyle_master_parts4.part LEFT OUTER JOIN
                        dbo.VODS_PORD_LINE AS VODS_PORD_LINE ON part.part = VODS_PORD_LINE.Part LEFT OUTER JOIN
                        dbo.VODS_PART_QTY_SOLD_ALL AS VODS_PART_QTY_SOLD_ALL ON part.part = VODS_PART_QTY_SOLD_ALL.part ON 
                        VSUPL_DEFL.part = part.part LEFT OUTER JOIN
                        dbo.vods_whse_shop_stock AS vods_whse_shop_stock ON part.part = vods_whse_shop_stock.part LEFT OUTER JOIN
                        dbo.part_plan AS part_plan ON part.part = part_plan.part LEFT OUTER JOIN
                        dbo.supl AS supl ON part_plan.prefer_supplier = supl.supplier LEFT OUTER JOIN
                        dbo.faml_pgrp AS faml_pgrp ON pgrp.prod_family = faml_pgrp.prod_family
                        --JOIN dbo.epar_ship ON part.part = dbo.epar_ship.part RIGHT OUTER JOIN
                        LEFT JOIN dbo.part_dims DSHIP on part.part = DSHIP.part

                --WHERE   (part.status = 20) AND (Weight IS NULL)
                --WHERE   (supl.supplier = 'OUO15') AND (part.status = 20)
                WHERE   part.status = 20 
                ORDER BY [Part_Number]"; 
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

        $fp = fopen("../files/xml/products.xml","wb");	

        fwrite($fp,$xml_data);
        
        fclose($fp);
        

   