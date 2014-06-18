<?php 

ini_set('max_execution_time',0);

//connect to the database
$con = mysqli_connect("localhost","root","People1205","csv_db") or die(mysqli_connect_error());

    $file = '../files/csv/google_match.csv';
    $file_handle = fopen($file,"r");  
    
    
    while (!feof($file_handle) ) {

        $line_of_text = fgetcsv($file_handle, 1024);
        
         mysqli_query($con, "INSERT INTO google_products (product_code, mpn, google_category, colour) VALUES
                (
                   '".$line_of_text[0]."',
                   '".$line_of_text[1]."',
                   '".$line_of_text[2]."',
                   '".$line_of_text[3]."'    
               )
            ");


        }

        fclose($file_handle);


	mysqli_close($con);

?>