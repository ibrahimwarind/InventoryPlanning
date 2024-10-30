<?php 
include("../services/function/function.php");


$fg_products="SELECT product_code FROM `sb_businessline_product` where product_code in (select product_code from sb_products_attribute where branch_code=139 AND company_code=10018) AND branch_code=248 and supply_groupname like 'Pharma' group by product_code";
$run_products=mysqli_query($con,$fg_products);
while($row_products=mysqli_fetch_array($run_products))
{
    $product_code=$row_products['product_code'];

    $insert="INSERT INTO `sb_businessline_product`(branch_code,supply_groupid,supply_groupname,product_code,status,is_productive) VALUES 
    (139,1019,'ALLIDE FORCE','$product_code',1,1)";
    $run_insert=mysqli_query($con,$insert);
}


?>