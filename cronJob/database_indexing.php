<?php 
include("../services/function/function.php");

//indexing

//business line product
// $db_index1="CREATE INDEX index_name ON sb_businessline_product (branch_code,supply_groupid,supply_groupname,product_code,status)";
// $run_index1=mysqli_query($con,$db_index1);

// //business line 
// $db_index2="CREATE INDEX index_name ON sb_business_line (branch_code,supply_groupid,company_code)";
// $run_index2=mysqli_query($con,$db_index2);

// //company 
// $db_index3="CREATE INDEX index_name ON sb_company_master (company_code)";
// $run_index3=mysqli_query($con,$db_index3);

// //customer 
// $db_index4="CREATE INDEX index_name ON sb_customers (branch_code,customer_code,brick_code,subarea_code,area_code,saletype,generaltype,customer_nicno)";
// $run_index4=mysqli_query($con,$db_index4);


// //dsf business line 
// $db_index5="CREATE INDEX index_name ON sb_dsf_businessline (branch_code,supply_groupid,dsf_code)";
// $run_index5=mysqli_query($con,$db_index5);

// //dsf master
// $db_index6="CREATE INDEX index_name ON sb_dsf_master (branch_code,dsf_code,loginname,loginpass,status)";
// $run_index6=mysqli_query($con,$db_index6);

// //sb_dsf_saf_detail
// $db_index7="CREATE INDEX index_name ON sb_dsf_saf_detail (branch_code,supply_groupid,dsf_code,customer_code,day_name)";
// $run_index7=mysqli_query($con,$db_index7);

// //product
// $db_index8="CREATE INDEX index_name ON sb_products (company_code,product_code)";
// $run_index8=mysqli_query($con,$db_index8);

// //product attribute
// $db_index8="CREATE INDEX index_name ON sb_products_attribute (branch_code,product_code)";
// $run_index8=mysqli_query($con,$db_index8);


// echo "Success";



$fg_getting="SELECT branch_code,supply_groupid,dsf_code,customer_code FROM `sb_dsf_saf_detail_new` where dsf_code=1329 AND branch_code=140 AND day_name='MONDAY'";
$run_getting=mysqli_query($con,$fg_getting);
while($row_getting=mysqli_fetch_array($run_getting))
{
    $branch_code=$row_getting['branch_code'];
    $supply_groupid=$row_getting['supply_groupid'];
    $dsf_code=$row_getting['dsf_code'];
    $customer_code=$row_getting['customer_code'];

    $ins_saf="INSERT INTO `sb_dsf_saf_detail_new`(branch_code,supply_groupid,dsf_code,customer_code,day_name) 
    VALUES ('$branch_code','$supply_groupid','$dsf_code','$customer_code','FRIDAY')";
    $run_saf=mysqli_query($con,$ins_saf);
}

?>