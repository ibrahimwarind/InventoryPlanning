<?php
include("header.php");
?>
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
    /*.printdiv{
    page-break-after: always !important;

    }*/
        
 
 

    }
    @page { size: auto;  margin: 0mm; }

</style>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header cont2" style="height: 35px !important">
      <div class="row" >
        <div class="col-sm-6">
          <h3 style="margin-top: -15px !important">Delivery Challan Listing
          
          </h3>
   
        </div>
        <div class="col-sm-6">
          <div style="margin-top: -15px">
<!--  <a href="import_sale_invoice.php" class="btn btn-primary" style="color:white;float: right !important;margin-right: 10px" >Add Sale Invoice</a> -->
 
          </div>

        </div>

      </div>
    </section>

     
      <section class="content-header" style="height: 55px !important;margin-top: 7px">
        <form method="post">
      <div class="row"  style="margin-top: -15px">
        <div class="col-sm-1">

          <input type="text" placeholder="Inv No" name="s_code" style="height: 30px;width:100%">
        </div>
        <div class="col-sm-2">

          <input type="date" placeholder="Date" name="s_date" style="height: 30px;width:100%">
        </div>
        <div class="col-sm-2">

          <input type="date" placeholder="Date" name="e_date" style="height: 30px;width:100%">
        </div>
        <div class="col-sm-3">
            <select style="width: 100%;height: 30px;" name="s_party" id="s_party">
              <option value="" hidden="">Select Party</option>
              <?php 
              $fg_par="select * from tbl_masaccount where status='active' AND account_id3=2";
              $run_par=mysqli_query($con,$fg_par);
              while($row_par=mysqli_fetch_array($run_par))
              {
                $account_id=$row_par['account_id'];
                $account_name=$row_par['account_name'];
                echo "<option value='$account_id'>$account_name</option>";
              }
              ?>
            </select>
        </div> 
        <div class="col-md-1 col-1 col-xl-1">
                <div class="form-group">
                  <select class="form-control" name="segment" id="segment">
                    <option value="" hidden="">Segment</option>
                    <?php 
                    $fg_getsegment="SELECT segment FROM `tbl_sale_invoice_detail` group by segment";
                    $run_getsegment=mysqli_query($con,$fg_getsegment);
                    while($row_getsegment=mysqli_fetch_array($run_getsegment))
                    {
                      $segment=$row_getsegment['segment'];
                      echo "<option>$segment</option>";
                    }

                    ?>
                  </select>
                </div>
        </div>
        <div class="col-md-2 col-2 col-xl-2">
                <div class="form-group">
                  <select class="form-control" name="saletype" id="saletype">
                    <option value="" hidden="">Sale Type</option>
                    <option>Sales Invoice</option>
                    <option>Credit Note</option>
               
                  </select>
                </div>
        </div>
         
        <div class="col-sm-1">
            <input type="submit" value="Search" name="usearch" class="btn btn-info">
            
        </div>

      </div>
    </form>
      </section>


    <!-- Main content -->
    <section class="content example-screen">
      <div class="row">
        <div class="col-12">
         
          <div class="box box-solid bg-dark">
           
            <!-- /.box-header -->
            <div class="box-body">
        <div class="table-responsive">
            <form method="post" action="action.php" target="_blank">
             <!--  <input type="button" class="btn btn-warning" value="Check All" id="checkAllBtn">
            <input type="hidden" value="<?php echo $invoice_type ?>" name="invvtype">
           <input type="submit" class="btn btn-primary" value="Delete Multi Invoice" name="btnmulti" id="btnmulti" onclick="return confirm('Are you sure you want to delete this item?');" > -->
          <table border="1" style="width:100%">
          <thead>
            <tr style="background-color: skyblue;color:black;font-weight: bold;padding: 5px !important">
            	<th style="padding: 5px !important"><center>DC No</center></th>
              <th style="padding: 5px !important"><center>Inv No</center></th>
                      
             
              <th><center>Date</center></th>
                <th><center>Account Party</center></th>
               <th><center>Sale Type</center></th>
               
               <th><center>Amount</center></th>
               <th><center>Sales Tax</center></th>
               <th><center>Discount</center></th>
               <th><center>Adv Sales Tax</center></th>
               <th><center>Net Amount</center></th>
               <th><center>Action</center></th>
         
              
            </tr>
          </thead>
          <tbody>

<?php 
if(isset($_POST['usearch']))
{
  $s_code=$_POST['s_code'];
  $s_date=$_POST['s_date'];
  $e_date=$_POST['e_date'];
  $s_party=$_POST['s_party'];
  $segment=$_POST['segment'];
  $saletype=$_POST['saletype'];
 
$fg_getmasterinv="SELECT mst.*,sum(det.total_value) as totalvalue,sum(det.sale_tax_amount) as totalsaletax,sum(det.discount) as totaldiscount,sum(det.gh_amount) as totalghamount,sum(det.total_amount) as totalamount FROM `tbl_sale_invoice_master` mst inner join tbl_sale_invoice_detail det on mst.invoice_id=det.invoice_id where mst.is_save=1 AND mst.sale_type='Sales Invoice'";
if($s_code !="")
{
  $fg_getmasterinv.=" AND mst.invoice_no='$s_code'";
}
if($s_date !="")
{
  $fg_getmasterinv.=" AND STR_TO_DATE(mst.invoice_date, '%d/%m/%Y') between '$s_date' AND '$e_date'";
}
if($s_party !="")
{
  $fg_getmasterinv.=" AND mst.customer_id='$s_party'";
}
if($segment !="")
{
  $fg_getmasterinv.=" AND det.segment='$segment'";
}
if($saletype !="")
{
  $fg_getmasterinv.=" AND mst.sale_type='$saletype'";
}
$fg_getmasterinv.=" group by mst.invoice_id order by mst.invoice_id desc";

}
else
{
$fg_getmasterinv="SELECT mst.*,sum(det.total_value) as totalvalue,sum(det.sale_tax_amount) as totalsaletax,sum(det.discount) as totaldiscount,sum(det.gh_amount) as totalghamount,sum(det.total_amount) as totalamount FROM `tbl_sale_invoice_master` mst inner join tbl_sale_invoice_detail det on mst.invoice_id=det.invoice_id where mst.is_save=1 AND mst.sale_type='Sales Invoice' group by mst.invoice_id order by mst.invoice_id desc limit 250";  
}

$run_us=mysqli_query($con,$fg_getmasterinv);

$overalltotalvalue=0;$overallsaletax=0;$overalldiscount=0;$overallghamount=0;$overalltotalamount=0;
$overalltotalvalue2=0;$overallsaletax2=0;$overalldiscount2=0;$overallghamount2=0;$overalltotalamount2=0;
$index=1;
while($rows=mysqli_fetch_array($run_us))
{
$invoice_id=$rows['invoice_id'];
$invoice_no=$rows['invoice_no'];
$split_invoice = explode("-", $invoice_no); 
$new_invoice = $split_invoice[1];
$sale_type=$rows['sale_type'];
$customer_id=$rows['customer_id'];
$invoice_date=$rows['invoice_date'];
$totalvalue=$rows['totalvalue'];
$totalsaletax=$rows['totalsaletax'];
$totaldiscount=$rows['totaldiscount'];
$totalghamount=$rows['totalghamount'];
$totalamount=$rows['totalamount'];

$overalltotalvalue=$overalltotalvalue + $totalvalue;
$overallsaletax=$overallsaletax + $totalsaletax;
$overalldiscount=$overalldiscount + $totaldiscount;
$overallghamount=$overallghamount + $totalghamount;
$overalltotalamount=$overalltotalamount + $totalamount;

if($sale_type == "Sales Invoice")
{
  $overalltotalvalue2=$overalltotalvalue2 + $totalvalue;
$overallsaletax2=$overallsaletax2 + $totalsaletax;
$overalldiscount2=$overalldiscount2 + $totaldiscount;
$overallghamount2=$overallghamount2 + $totalghamount;
$overalltotalamount2=$overalltotalamount2 + $totalamount;
}
// else if($sale_type == "Credit Note")
//     {
// $overalltotalvalue2=$overalltotalvalue2 - $totalvalue;
// $overallsaletax2=$overallsaletax2 - $totalsaletax;
// $overalldiscount2=$overalldiscount2 - $totaldiscount;
// $overallghamount2=$overallghamount2 - $totalghamount;
// $overalltotalamount2=$overalltotalamount2 - $totalamount;
//     }

$fg_acc="select * from tbl_masaccount where account_id='$customer_id'";
$run_acc=mysqli_query($con,$fg_acc);
$row_acc=mysqli_fetch_array($run_acc);
$account_code=$row_acc['account_code'];
$account_name=$row_acc['account_name'];

echo "

<tr style='color:black'>
              <td><center>DC-$new_invoice</center></td>
              <td><center>$invoice_no</center></td>
              <td><center>$invoice_date</center></td>
              <td><span>$account_code - $account_name</span></td>
              <td><center>$sale_type</center></td>
              <td><span style='float:right'>Rs ".number_format($totalvalue)."&nbsp;</span></td>
            <td><span style='float:right'>Rs ".number_format($totalsaletax)."&nbsp;</span></td>
            <td><span style='float:right'>Rs ".number_format($totaldiscount)."&nbsp;</span></td>
            <td><span style='float:right'>Rs ".number_format($totalghamount)."&nbsp;</span></td>
            <td><span style='float:right'>Rs ".number_format($totalamount)."&nbsp;</span></td>
              
           
   

            

        
              
          ";?>
       <td>
<center>

 <?php echo "
 a href='print/print_delivery_challan.php?s_code=DC-$new_invoice&s_date=&e_date=&s_party=&segment=&saletype=' class='text-danger' target='_blank'>Print </a></center>
     </td>
            
            </tr>

";

$index =$index + 1;

}

?>
<tr style="background-color: lightgray;color:black">
   <th colspan="4"><center><b style="color:black">Grant Total</b></center></th>
   <th><span style='float:right;color:black;font-weight: bold'>Rs <?php echo number_format($overalltotalvalue) ?>&nbsp;</span></th>
   <th><span style='float:right;color:black;font-weight: bold'>Rs <?php echo number_format($overallsaletax) ?>&nbsp;</span></th>
   <th><span style='float:right;color:black;font-weight: bold'>Rs <?php echo number_format($overalldiscount) ?>&nbsp;</span></th>
   <th><span style='float:right;color:black;font-weight: bold'>Rs <?php echo number_format($overallghamount) ?>&nbsp;</span></th>
   <th><span style='float:right;color:black;font-weight: bold'>Rs <?php echo number_format($overalltotalamount) ?>&nbsp;</span></th>
   <th></th>

</tr>

<tr style="background-color: lightgray;color:black">
   <th colspan="4"><center><b style="color:black">Grant Total (Sales Invoice - Credit Note)</b></center></th>
   <th><span style='float:right;color:black;font-weight: bold'>Rs <?php echo number_format($overalltotalvalue2) ?>&nbsp;</span></th>
   <th><span style='float:right;color:black;font-weight: bold'>Rs <?php echo number_format($overallsaletax2) ?>&nbsp;</span></th>
   <th><span style='float:right;color:black;font-weight: bold'>Rs <?php echo number_format($overalldiscount2) ?>&nbsp;</span></th>
   <th><span style='float:right;color:black;font-weight: bold'>Rs <?php echo number_format($overallghamount2) ?>&nbsp;</span></th>
   <th><span style='float:right;color:black;font-weight: bold'>Rs <?php echo number_format($overalltotalamount2) ?>&nbsp;</span></th>
   <th></th>

</tr>

            
            
          </tbody>          
          
        </table>
        </form>
        </div>              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->   
         
       
      
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->


  </div>
  <!-- /.content-wrapper -->

<div class="example-screen">
  <?php include("footer.php");?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous"/>
<script type="text/javascript">
  $('#s_party').chosen();
  $('#segment').chosen();

$('#saletype').chosen();
$('#btnmulti').hide();
  $(".heckbox").change(function() {
    if(this.checked) {
 $('#btnmulti').show();
    }

  });

  $('#checkAllBtn').click(function() {
    var allChecked = $('.heckbox').length === $('.heckbox:checked').length;
    $('.heckbox').prop('checked', !allChecked);
    $('#btnmulti').toggle($('.heckbox:checked').length > 0);
  });
  
  document.title="Sale Invoice List";
</script>