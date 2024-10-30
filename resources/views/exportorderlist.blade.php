<?php
error_reporting(0);
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Order List.xls");
$con=mysqli_connect(DATABASEHOST,DATABASEUSER,DATABASEPASS,DATABASENAME);
 ?>
 <table width="100%" border='1'>

    <tr>
        <th colspan="10" style='font-size:20px;background-color: skyblue;'>Orders List</th>

    </tr>

    <tr>
    <th style="text-align:center;">Unique ID</th>
										<th style="text-align:center;">DSF Name</th>
										<th style="text-align:center;">Date</th>
                                        <th style="text-align:center;">Day</th>
										<th style="text-align:center;">Total</th>
										
										<th style="text-align:center;">Branch</th>
										
										<th style="text-align:center;">Row Count</th>
                                        <th style="text-align:center;">Customer Count</th>
                                        <th style="text-align:center;">Day Over</th>
										<th style="text-align:center;">Status</th>
	
	                                  
            

    </tr>
  
                 
    @foreach($orderdata as $order)
									<?php $mecount=0;
                                    $fg_shopcnt="SELECT count(DISTINCT customer_code) as totalshop FROM `sb_order_detail` where unique_key='$order->unique_key'";
                                    $run_shopcnt=mysqli_query($con,$fg_shopcnt);
                                    if(mysqli_num_rows($run_shopcnt) !=0)
                                    {
                                      $row_shopcnt=mysqli_fetch_array($run_shopcnt);
                                      $mecount=$row_shopcnt['totalshop'];

                                    }
                                    ?>
									<tr>
										
										<td style="text-align:center;"> {{$order->unique_key}}
											
										</td>
										<td style="width:350px !important">{{$order->dsf_code}} - {{$order->dsf_name}}
                                    </td>
										<td style="text-align: center;">{{date("d-M-Y", strtotime($order->order_date))}}</td>
                                        <td style="text-align: center;">{{date('l', strtotime($order->order_date))}}</td>
										
										<td style="text-align: center;">{{number_format($order->order_total,2)}}</td>
								        <td style="width:258px !important">{{$order->branch_code}} - {{$order->branch_name}}</td>
                                        <td style="text-align: center;">{{$order->count}}</td>
                                        <td style="text-align: center;">{{$mecount}}</td>
                                        <td style="text-align: center;">{{$order->day_over}}</td>
                                        <td style="text-align: center;">{{$order->status}}</td>
										
										
									</tr>
									@endforeach
  


  
      
</table> 