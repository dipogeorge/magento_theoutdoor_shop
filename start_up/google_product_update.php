<?php 

ini_set('max_execution_time',0);

//connect to the database
$con = mysqli_connect("localhost","root","People1205","csv_db") or die(mysqli_connect_error());

    //$file = '../files/csv/google_update.csv';
    $file = '../files/csv/google_colour_size.csv';
    $file_handle = fopen($file,"r");  
    
    
    while (!feof($file_handle) ) {

        $line_of_text = fgetcsv($file_handle, 1024);
        
        //echo $line_of_text[0].'<br/>';
        
        //mysqli_query($con, "UPDATE google_shopping SET brand ='".$line_of_text[1]."' , mpn = '".$line_of_text[2]."', google_product_category = '".$line_of_text[3]."', product_type = '".$line_of_text[4]."', colour = '".$line_of_text[5]."' WHERE product_id = '".$line_of_text[0]."'") or die(mysqli_error($con));
        mysqli_query($con, "UPDATE google_shopping SET colour ='".$line_of_text[1]."' , size = '".$line_of_text[2]."' WHERE product_id = '".$line_of_text[0]."'") or die(mysqli_error($con));



        }

        fclose($file_handle);


	mysqli_close($con);

?>