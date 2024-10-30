<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<title>ForeCasting_{{$key}}</title>
<style type="text/css">
  


  .example-print {
        display: none;
    }

    @media print {
        .example-screen {
            display: none;
        }

        .example-print {
            display: block;
        }

        .maih {
            font-size: 15px;
        }
      
        
 .printdiv{
    page-break-after: always !important;

    }
 

    }
    @page { size: auto;  margin: 5mm;size: landscape !important }
      }

    /*#ullist li{
      margin-top: 15px !important;
     } */
</style>

 <!-- Content Wrapper. Contains page content -->

    <!-- Content Header (Page header) -->



<!--   //print screen -->
<?php $con=mysqli_connect('127.0.0.1','root','','db_ipl');?>


<div class="container-fluid example-print">
     @foreach($forecastingdetail as $detail)
           <div class="row">
              <div class="col-sm-6" class="example-print" style="border-bottom: 1px solid black">
                <span class="example-print"><p><b id="" style="font-size: 18px"><br>Premier Sales Private Ltd.</b><br>
<b id="" style="font-size: 13px">Plot # 1-A/15, Sector 15 Korangi Industrial Area, Karachi</b>
<br>
<b id="" style="font-size: 13px">Phone : (021) 35123731</b>
</p></span>
              </div>
                        <div class="col-sm-6 example-print" style="border-bottom: 1px solid black">
   
   <span style='float:right !important'><p style="font-size: 15px;"><b>Inventory Forecasting </b>
   
  <br><b>Branch : {{$detail->branch_id}} - {{$detail->branch_name}} </b>
  <br><b>Company : {{$detail->company_title}} </b>
  <br><b>Month : {{date("M-Y", strtotime($detail->forecasting_month))}} </b>
  <br><b>Version : {{$detail->version_no}} </b>
   </p></span>
  </div>
  </div>

  @endforeach

<?php 
          $fg_detail="SELECT sale_month1,sale_month2,sale_month3,current_stockmonth FROM `ipl_forecasting_detail` where forecasting_id=$fid";
          $run_detail=mysqli_query($con,$fg_detail);
          $row_detail=mysqli_fetch_array($run_detail);
          $sale_month1=$row_detail['sale_month1'];
          $sale_month2=$row_detail['sale_month2'];
          $sale_month3=$row_detail['sale_month3'];
          $current_stockmonth=$row_detail['current_stockmonth'];
                  ?>
  <div class="table-responsive">
     @if($sort == "branchwise")  
               @foreach($forecastingmaster as $mst) 
   <br>
                    <table border="1" style="width:100%">
                       <thead>
                      <tr style="color:black;border: 1px solid black">
                          <th colspan="17" style="padding:10px;border: 1px solid black">{{$mst->branch_row_id}} - {{$mst->branch_name}}</th>
                        </tr>
                      <tr style="border: 1px solid black;font-size:12px">

                          <td style="border: 1px solid black"><center>Product Name</center></td>
                          <td style="border: 1px solid black"><center id="monthname1">{{date("M", strtotime($sale_month1))}} Sales</center></td>
                          <td style="border: 1px solid black"><center id="monthname2">{{date("M", strtotime($sale_month2))}} Sales</center></td>
                          <td style="border: 1px solid black"><center id="monthname3">{{date("M", strtotime($sale_month3))}} Sales</center></td>
                          <td style="border: 1px solid black"><center>Avg Sale</center></td>
                          <td style="border: 1px solid black"><center>Max Sales</center></td>
                          <td style="border: 1px solid black"><center>{{date("M", strtotime($current_stockmonth))}} Stock</center></td>
                          <td style="border: 1px solid black"><center>Br Int</center></td>
                          <td style="border: 1px solid black"><center>Comp Int</center></td>
                          <td style="border: 1px solid black"><center>Net Stock</center></td>
                          <td style="border: 1px solid black"><center>Suggested<br>Plan</center></td>
                          <td style="border: 1px solid black"><center>Demand</center></td>
                          <td style="border: 1px solid black"><center>Diff</center></td>
                           <td style="border: 1px solid black"><center>Final<br>Plan<br>Qty</center></td>
                           <td style="border: 1px solid black"><center>Final<br>Plan<br>Value</center></td>
                           <td style="border: 1px solid black"><center>Final<br>Plan<br>Carton</center></td>
                           
                          
                          
                         
                          <td style="border: 1px solid black"><center>Multiple<br> By</center></td>
                         

                        </tr>
                   </thead>
                    <?php 
                    $fg_groupdata="SELECT det.*,prod.product_name FROM `ipl_forecasting_detail` det inner join ipl_products prod on det.product_code=prod.product_code where det.forecasting_id='$fid' AND branch_row_id='$mst->branch_row_id'";
                    $run_groupdata=mysqli_query($con,$fg_groupdata);
                    while($row_groupdata=mysqli_fetch_array($run_groupdata))
                    {

                      $detail_id=$row_groupdata['detail_id'];
                      $product_code=$row_groupdata['product_code'];
                      $product_name=$row_groupdata['product_name'];
                      $sale_qty1=$row_groupdata['sale_qty1'];
                      $sale_qty2=$row_groupdata['sale_qty2'];
                      $sale_qty3=$row_groupdata['sale_qty3'];
                      $avg_sales=$row_groupdata['avg_sales'];
                      $max_sales=$row_groupdata['max_sales'];
                      $current_stock=$row_groupdata['current_stock'];
                      $current_stockmonth=$row_groupdata['current_stockmonth'];
                      $in_transit_stock=$row_groupdata['in_transit_stock'];
                      $manual_stock=$row_groupdata['manual_stock'];
                      $net_stock=$row_groupdata['net_stock'];
                      $pr_diff=$row_groupdata['pr_diff'];
                      $demand=$row_groupdata['demand'];
                      $pspl_diff=$row_groupdata['pspl_diff'];
                      $multiply_by=$row_groupdata['multiply_by'];
                      $remarks=$row_groupdata['remarks'];
                      $group_detail_id=$row_groupdata['group_detail_id'];
                      $final_planoutput=$row_groupdata['final_planoutput'];
                      $final_output_value=$row_groupdata['final_output_value'];
                      $final_output_packingsize=$row_groupdata['final_output_packingsize'];


                      $fg_grpdata="SELECT group_name FROM `ipl_group_master` where group_id='$group_detail_id'";
                      $grpname="";
                      $run_grpdata=mysqli_query($con,$fg_grpdata);
                      if(mysqli_num_rows($run_grpdata) !=0)
                      {
                        $row_grpdata=mysqli_fetch_array($run_grpdata);
                        $grpname=$row_grpdata['group_name'];
                      }
                      


                      echo "
              <tr style='border:1px solid black;font-size:12px'>
                <td style='border:1px solid black;color:black;font-size:12px'>$product_code - $product_name</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($sale_qty1)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($sale_qty2)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($sale_qty3)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($avg_sales)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($max_sales)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($current_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($in_transit_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($manual_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($net_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($pr_diff)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($demand)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($pspl_diff)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($final_planoutput)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($final_output_value)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($final_output_packingsize)."</td>
                
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($multiply_by)."</td>
                
                </tr>

                      ";

                    }
                    ?>
                    </table>
                    @endforeach

                 

                  @elseif($sort == "productwise")   
               @foreach($forecastingmaster as $mst) 
   <br>
                    <table border="1" style="width:100%">
                        <thead>
                      <tr style="color:black;">
                          <th colspan="17" style="padding:10px;border: 1px solid black">{{$mst->product_code}} - {{$mst->product_name}}</th>
                        </tr>
                      <tr style="border: 1px solid black;font-size:13px">

                          <td style="border: 1px solid black"><center>Branch Name</center></td>
                          <td style="border: 1px solid black"><center id="monthname1">{{date("M", strtotime($sale_month1))}} Sales</center></td>
                          <td style="border: 1px solid black"><center id="monthname2">{{date("M", strtotime($sale_month2))}} Sales</center></td>
                          <td style="border: 1px solid black"><center id="monthname3">{{date("M", strtotime($sale_month3))}} Sales</center></td>
                          <td style="border: 1px solid black"><center>Avg Sale</center></td>
                          <td style="border: 1px solid black"><center>Max Sales</center></td>
                          <td style="border: 1px solid black"><center>{{date("M", strtotime($current_stockmonth))}} Stock</center></td>
                          <td style="border: 1px solid black"><center>Br Int</center></td>
                          <td style="border: 1px solid black"><center>Comp Int</center></td>
                          <td style="border: 1px solid black"><center>Net Stock</center></td>
                          <td style="border: 1px solid black"><center>Suggested<br>Plan</center></td>
                          <td style="border: 1px solid black"><center>Demand</center></td>
                          <td style="border: 1px solid black"><center>Diff</center></td>
                           <td style="border: 1px solid black"><center>Final<br>Plan<br>Qty</center></td>
                           <td style="border: 1px solid black"><center>Final<br>Plan<br>Value</center></td>
                           <td style="border: 1px solid black"><center>Final<br>Plan<br>Carton</center></td>
                          
                          
                          <td style="border: 1px solid black"><center>Multiple By</center></td>
                          

                        </tr>
                      </thead>
                   
                    <?php 
                    $fg_groupdata="SELECT det.*,prod.product_name FROM `ipl_forecasting_detail` det inner join ipl_products prod on det.product_code=prod.product_code where det.forecasting_id='$fid' AND det.product_code='$mst->product_code'";
                    $run_groupdata=mysqli_query($con,$fg_groupdata);
                    while($row_groupdata=mysqli_fetch_array($run_groupdata))
                    {

                      $detail_id=$row_groupdata['detail_id'];
                      $product_code=$row_groupdata['product_code'];
                      $product_name=$row_groupdata['product_name'];
                      $sale_qty1=$row_groupdata['sale_qty1'];
                      $sale_qty2=$row_groupdata['sale_qty2'];
                      $sale_qty3=$row_groupdata['sale_qty3'];
                      $avg_sales=$row_groupdata['avg_sales'];
                      $max_sales=$row_groupdata['max_sales'];
                      $current_stock=$row_groupdata['current_stock'];
                      $current_stockmonth=$row_groupdata['current_stockmonth'];
                      $in_transit_stock=$row_groupdata['in_transit_stock'];
                      $manual_stock=$row_groupdata['manual_stock'];
                      $net_stock=$row_groupdata['net_stock'];
                      $pr_diff=$row_groupdata['pr_diff'];
                      $demand=$row_groupdata['demand'];
                      $pspl_diff=$row_groupdata['pspl_diff'];
                      $multiply_by=$row_groupdata['multiply_by'];
                      $remarks=$row_groupdata['remarks'];
                      $group_detail_id=$row_groupdata['group_detail_id'];
                      $branch_row_id=$row_groupdata['branch_row_id'];
                      $final_planoutput=$row_groupdata['final_planoutput'];
                      $final_output_value=$row_groupdata['final_output_value'];
                      $final_output_packingsize=$row_groupdata['final_output_packingsize'];

                      $fg_grpdata="SELECT group_name FROM `ipl_group_master` where group_id='$group_detail_id'";
                      $grpname="";
                      $run_grpdata=mysqli_query($con,$fg_grpdata);
                      if(mysqli_num_rows($run_grpdata) !=0)
                      {
                        $row_grpdata=mysqli_fetch_array($run_grpdata);
                        $grpname=$row_grpdata['group_name'];
                      }

                      $brnames="";
                      $fg_branchdata="select branch_name from ipl_branch_master where branch_code='$branch_row_id'";
                      $run_branchdata=mysqli_query($con,$fg_branchdata);
                      if(mysqli_num_rows($run_branchdata) !=0)
                      {
                        $row_branchdata=mysqli_fetch_array($run_branchdata);
                        $brnames=$row_branchdata['branch_name'];
                      }
                      


                      echo "
              <tr style='border:1px solid black;font-size:12px'>
                <td style='border:1px solid black;color:black;font-size:13px'>$branch_row_id - $brnames</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($sale_qty1)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($sale_qty2)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($sale_qty3)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($avg_sales)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($max_sales)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($current_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($in_transit_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($manual_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($net_stock)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($pr_diff)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($demand)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($pspl_diff)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($final_planoutput)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($final_output_value)."</td>
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($final_output_packingsize)."</td>
              
                <td style='border:1px solid black;color:black;text-align:center'>".number_format($multiply_by)."</td>
                
                </tr>

                      ";

                    }
                    ?>
                    </table>
                    @endforeach
  @endif
  </div>


  </div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
<script type="text/javascript">
  window.print();
 
   setTimeout(function() { 
      // window.open('../dues_report.php','_self');
      window.top.close();
    }, 4000);
</script>
