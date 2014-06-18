<?php

$con = mysqli_connect("localhost","root","People1205","csv_db") or die(mysqli_connect_error());


$query = "SELECT * FROM google_products";
$result = mysqli_query($con, $query);

while ($row = $result->fetch_assoc()) {
    
    //$google_cat = str_replace(',', '...', $row['google_category']);

    //echo $row['product_code'].'<br/>';
     mysqli_query($con, "UPDATE google_shopping SET mpn = '".$row['mpn']."', google_product_category = '".$row['google_category']."', colour = '".$row['colour']."' WHERE product_id = '".$row['product_code']."' ") or die(mysqli_error($con));;

     
}


/* close connection */
mysqli_close($con);

