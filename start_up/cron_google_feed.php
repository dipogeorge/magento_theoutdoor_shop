<?php

/* 
 $xml_data =   '<?xml version="1.0" encoding="UTF-8" ?>' . "\r\n" . 
                    '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . "\r\n" . 
                    '<channel>' . "\r\n" .
                    '<title>The Outdoor Shop Outdoor Equipment and Clothing for Rock climbing, Trekking, Backpacking, Hill Walking, Hiking, Mountaineering, Rambling Outdoor Activities and Adventure Travel</title>' . "\r\n" .
                    '<link>http://www.theoutdoorshop.com</link>' . "\r\n" .
                    '<description>A description of your content</description>' . "\r\n"; 
$xml_data .=
                            '<item>' . "\r\n"  .
                            '<title><![CDATA['.rtrim(odbc_result($result, 9)).']]></title>' . "\r\n" .
                            '<link><![CDATA[http://www.theoutdoorshop.com/showPart.asp?part='.rtrim(odbc_result($result, 4)).'&amp;utm_source=Product_feed&amp;utm_medium=google&amp;utm_campaign=1]]></link>' . "\r\n" .
                            '<description><![CDATA['.strip_tags($desc).']]></description>' . "\r\n" .
                            '<g:id><![CDATA['.rtrim(odbc_result($result, 4)).']]></g:id>' . "\r\n" .
                            '<g:price><![CDATA['.rtrim(number_format(odbc_result($result, 15), 2, '.', ',')).' GBP]]></g:price>' . "\r\n" .
                            '<g:image_link>http://www.theoutdoorshop.com/products/img/small/'.rtrim(odbc_result($result, 4)).'.jpg</g:image_link>' . "\r\n" .			
                            '<g:shipping_weight><![CDATA['.rtrim(odbc_result($result, 6)).']]></g:shipping_weight>' . "\r\n";
                            
                            if(odbc_result($result, 11) != ''){
                                $xml_data .= '<g:color><![CDATA['.rtrim(odbc_result($result, 11)).']]></g:color>' . "\r\n";
                            }
                            
                            if(odbc_result($result, 12) != ''){
                                $xml_data .= '<g:size><![CDATA['.rtrim(odbc_result($result, 12)).']]></g:size>' . "\r\n";
                            }
                            
                            if(odbc_result($result, 4) != ''){
                            
                                  $xml_data .= '<g:item_group_id><![CDATA['.rtrim(odbc_result($result, 4)).']]></g:item_group_id>' . "\r\n";
                            
                            }
                            
          $xml_data .=      '<g:brand><![CDATA['.rtrim(odbc_result($result, 2)).']]></g:brand>' . "\r\n" .
                            '<g:mpn><![CDATA['.rtrim(odbc_result($result, 8)).']]></g:mpn>' . "\r\n" .
                            '<g:availability>in stock</g:availability>' . "\r\n" .
                            '<g:condition>new</g:condition>' . "\r\n" .
                            '<g:google_product_category></g:google_product_category>' . "\r\n" .
                            '<g:product_type></g:product_type>' . "\r\n";
                            
          $xml_data .=      '</item>' . "\r\n";

$xml_data .= '</channel>' . "\r\n".
                '</rss>' . "\r\n";
 */

