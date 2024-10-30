<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderManagementController extends Controller
{
    
    function OrderList()
    {

      if(session()->has("adminname"))
      {
       session()->put("titlename","Manage Order - Sales Booster Admin Panel - Premier Group Of Companies");
       
       $branchid=session('branchid');
       $branchid2=session('branchid');
       $branchid=explode(',', $branchid);

       $totalorder=DB::table('sb_order_master')
       ->join('sb_dsf_master', function($join)
       {
         $join->on('sb_dsf_master.dsf_code', '=', 'sb_order_master.dsf_code');
         $join->on('sb_dsf_master.branch_code', '=', 'sb_order_master.branch_code');
      
       })
       ->join('sb_branch_master', 'sb_order_master.branch_code', '=', 'sb_branch_master.branch_code')
       ->whereIn('sb_order_master.branch_code', $branchid)
       ->count();

       $totalorderamount=DB::table('sb_order_master')
       ->join('sb_dsf_master', function($join)
       {
         $join->on('sb_dsf_master.dsf_code', '=', 'sb_order_master.dsf_code');
         $join->on('sb_dsf_master.branch_code', '=', 'sb_order_master.branch_code');
      
       })
       ->join('sb_branch_master', 'sb_order_master.branch_code', '=', 'sb_branch_master.branch_code')
       ->whereIn('sb_order_master.branch_code', $branchid)
       ->sum('sb_order_master.order_total');

       $orderdata=DB::table('sb_order_master')
       ->join('sb_dsf_master', function($join)
       {
         $join->on('sb_dsf_master.dsf_code', '=', 'sb_order_master.dsf_code');
         $join->on('sb_dsf_master.branch_code', '=', 'sb_order_master.branch_code');
      
       })
       ->join('sb_branch_master', 'sb_order_master.branch_code', '=', 'sb_branch_master.branch_code')
       ->whereIn('sb_order_master.branch_code', $branchid)
       ->orderBy('sb_order_master.order_id', 'desc')
       ->paginate(50);


      return view('orderlist',['totalorder'=>$totalorder,'totalorderamount'=>$totalorderamount,'orderdata'=>$orderdata,'str_date'=>'','end_date'=>'','ord_id'=>'','brid'=>$branchid2,'dfid'=>'','dover'=>'','status'=>'']);

    }
    else
    {
        session()->put("titlename","Admin Login - Sales Booster Admin Panel - Premier Group Of Companies");
        return view('login',['data'=>'']);
    }
    
    }



    function SearchOrder(Request $req)
    {

      if(session()->has("adminname"))
      {
       session()->put("titlename","Manage Order - Sales Booster Admin Panel - Premier Group Of Companies");
       $ord_id=$req->ord_id;
       $branch_name=$req->branch_name;
       $dsf_code=$req->dsf_code;
       $str_date=$req->str_date;
       $end_date=$req->end_date;
       $day_over=$req->day_over;
       $status=$req->status;
              

       $branchid=session('branchid');
       $branchid2=session('branchid');
       $branchid=explode(',', $branchid);
       

      //  $totalorder=DB::table('sb_order_master')
      //  ->join('sb_dsf_master', function($join)
      //  {
      //    $join->on('sb_dsf_master.dsf_code', '=', 'sb_order_master.dsf_code');
      //    $join->on('sb_dsf_master.branch_code', '=', 'sb_order_master.branch_code');
      
      //  })
      //  ->join('sb_branch_master', 'sb_order_master.branch_code', '=', 'sb_branch_master.branch_code')
      //  ->whereIn('sb_order_master.branch_code', $branchid)
      //  ->count();

       $totalorderamount=DB::table('sb_order_master')
       ->join('sb_dsf_master', function($join)
       {
         $join->on('sb_dsf_master.dsf_code', '=', 'sb_order_master.dsf_code');
         $join->on('sb_dsf_master.branch_code', '=', 'sb_order_master.branch_code');
      
       })
       ->join('sb_branch_master', 'sb_order_master.branch_code', '=', 'sb_branch_master.branch_code')
       ->whereIn('sb_order_master.branch_code', $branchid);
       if($ord_id !="")
       {
         $totalorderamount=$totalorderamount->where('sb_order_master.unique_key', $ord_id);
       }
       if($branch_name !="")
       {
        $branch_name2=explode(',', $branch_name);
         $totalorderamount=$totalorderamount->whereIn('sb_order_master.branch_code', $branch_name2);
       }
       if($str_date !="" && $end_date =="")
       {
         $totalorderamount=$totalorderamount->where('sb_order_master.order_date', $str_date);
       }
       if($str_date !="" && $end_date !="")
       {
         $totalorderamount=$totalorderamount->whereBetween('sb_order_master.order_date', [$str_date, $end_date]);
       }
       if($day_over !="")
       {
         $totalorderamount=$totalorderamount->where('sb_order_master.day_over', $day_over);
       }
       if($status !="")
       {
         $totalorderamount=$totalorderamount->where('sb_order_master.status', $status);
       }
       if($dsf_code !="")
       {

         $totalorderamount=$totalorderamount->where('sb_order_master.dsf_number', $dsf_code);
       }
       $totalorderamount=$totalorderamount->sum('sb_order_master.order_total');


       $orderdata=DB::table('sb_order_master')
       ->join('sb_dsf_master', function($join)
       {
         $join->on('sb_dsf_master.dsf_code', '=', 'sb_order_master.dsf_code');
         $join->on('sb_dsf_master.branch_code', '=', 'sb_order_master.branch_code');
      
       })
       ->join('sb_branch_master', 'sb_order_master.branch_code', '=', 'sb_branch_master.branch_code')
       ->whereIn('sb_order_master.branch_code', $branchid);

       if($ord_id !="")
       {
         $orderdata=$orderdata->where('sb_order_master.unique_key', $ord_id);
       }
       if($branch_name !="")
       {
        $branch_name2=explode(',', $branch_name);
         $orderdata=$orderdata->whereIn('sb_order_master.branch_code', $branch_name2);
       }
       if($str_date !="" && $end_date =="")
       {
         $orderdata=$orderdata->where('sb_order_master.order_date', $str_date);
       }
       if($str_date !="" && $end_date !="")
       {
         $orderdata=$orderdata->whereBetween('sb_order_master.order_date', [$str_date, $end_date]);
       }
       if($day_over !="")
       {
         $orderdata=$orderdata->where('sb_order_master.day_over', $day_over);
       }
       if($status !="")
       {
         $orderdata=$orderdata->where('sb_order_master.status', $status);
       }
       if($dsf_code !="")
       {

         $orderdata=$orderdata->where('sb_order_master.dsf_number', $dsf_code);
       }
       $orderdata=$orderdata->orderBy('sb_order_master.order_id', 'desc');
       $orderdata=$orderdata->paginate(50);

       $totalorder=$orderdata->count();
      //  $totalorderamount=$orderdata->sum('sb_order_master.order_total');

      $orderdata->appends(['ord_id' => $ord_id,'branch_name' => $branch_name,'dsf_code' => $dsf_code,'str_date' => $str_date,'end_date' => $end_date,'day_over' => $day_over,'status' => $status]);

     
      return view('orderlist',['totalorder'=>$totalorder,'totalorderamount'=>$totalorderamount,'orderdata'=>$orderdata,'str_date'=>$str_date,'end_date'=>$end_date,'ord_id'=>$ord_id,'brid'=>$branch_name,'dfid'=>$dsf_code,'dover'=>$day_over,'status'=>$status]);

    }
    else
    {
        session()->put("titlename","Admin Login - Sales Booster Admin Panel - Premier Group Of Companies");
        return view('login',['data'=>'']);
    }
    
    }
    function GetBranchDsfData(Request $req)
    {

         $branch_name=$req->branch_name;
         // $dsfbrick=$req->dsf_brick;
         $user=DB::select("SELECT * FROM `sb_dsf_master` where branch_code='$branch_name'");
       return response()->json($user);

        
    }

    
    function AddOrderRemark(Request $req)
    {
        $oid=$req->oid;
        $remarks=$req->remarks;
        $status=$req->status;
       
        $myuserid=session("adminname");
        date_default_timezone_set("Asia/Karachi");
        $currentdate=date('Y-m-d');

 
        DB::table('tbl_order_remark_dashboard')
           ->insert([
            'order_id' => $oid,
            'remark_text' => $remarks,
            'remark_status' => $status,
            'remark_date' => $currentdate,
            'post_user' => $myuserid,
          
            
           ]);
        return response()->json(true);
    }
    
    //order detail page
   function ViewOrder($id,$uid)
   {
   
    $masterdata=DB::table('sb_order_master')
       ->join('sb_dsf_master', function($join)
       {
         $join->on('sb_dsf_master.dsf_code', '=', 'sb_order_master.dsf_code');
         $join->on('sb_dsf_master.branch_code', '=', 'sb_order_master.branch_code');
      
       })
       ->join('sb_branch_master', 'sb_order_master.branch_code', '=', 'sb_branch_master.branch_code')
       ->where([
        ['sb_order_master.order_id', '=', $id],
      
       ])
       
       ->get();
    
  
    $orderdetail = DB::table('sb_order_detail')
    ->join('sb_customers', function($join)
       {
         $join->on('sb_customers.customer_code', '=', 'sb_order_detail.customer_code');
         $join->on('sb_customers.branch_code', '=', 'sb_order_detail.branch_code');
      
       })
    // ->join('sb_products', 'sb_order_detail.product_code', '=', 'sb_products.product_code')
    ->where([
        ['sb_order_detail.order_id', '=', $id],
      
    ])
    ->orderBy('sb_order_detail.customer_code', 'asc')
    ->get();

     $summarydata=DB::SELECT("SELECT comp.company_title,prod.product_name,sum(det.quantity) as totalqty FROM `sb_order_detail` det,sb_products prod,sb_company_master comp where det.product_code=prod.product_code AND prod.company_code=comp.company_code AND det.order_id='$id' group by prod.product_code order by comp.company_title asc");
   
    $remarkdetail = DB::table('tbl_order_remark_dashboard')
    ->where([
        ['order_id', '=', $id],
    ])
    ->get();

    
    return view('order_detail',['masterdata'=>$masterdata,'orderdetail'=>$orderdetail,'remarkdetail'=>$remarkdetail,'summarydata'=>$summarydata,'oid'=>$id,'uid'=>$uid]);


   }

   function ExportOrderDetail($id,$uid)
   {
   
    
    
  
    $orderdetail = DB::table('sb_order_detail')
    ->join('sb_customers', function($join)
       {
         $join->on('sb_customers.customer_code', '=', 'sb_order_detail.customer_code');
         $join->on('sb_customers.branch_code', '=', 'sb_order_detail.branch_code');
      
       })
    ->join('sb_products', 'sb_order_detail.product_code', '=', 'sb_products.product_code')
    ->join('sb_dsf_master', function($join)
       {
         $join->on('sb_dsf_master.dsf_code', '=', 'sb_order_detail.dsf_code');
         $join->on('sb_dsf_master.branch_code', '=', 'sb_order_detail.branch_code');
      
       })
    ->join('sb_branch_master', 'sb_order_detail.branch_code', '=', 'sb_branch_master.branch_code')
    ->where([
        ['sb_order_detail.order_id', '=', $id],
      
    ])
    ->get();

    
    
    return view('export_order_detail',['orderdetail'=>$orderdetail,'oid'=>$id,'uid'=>$uid]);


   }



   function ExportOrderList($sdate,$edate,$oid,$brid,$dfid,$dover,$status)
    {

       $ord_id=$oid;
       $branch_name=$brid;
       $dsf_code=$dfid;
       $str_date=$sdate;
       $end_date=$edate;
       $day_over=$dover;
       $status=$status;
              

       $branchid=session('branchid');
       $branchid2=session('branchid');
       $branchid=explode(',', $branchid);
       


       $orderdata=DB::table('sb_order_master')
       ->join('sb_dsf_master', function($join)
       {
         $join->on('sb_dsf_master.dsf_code', '=', 'sb_order_master.dsf_code');
         $join->on('sb_dsf_master.branch_code', '=', 'sb_order_master.branch_code');
      
       })
       ->join('sb_branch_master', 'sb_order_master.branch_code', '=', 'sb_branch_master.branch_code')
       ->whereIn('sb_order_master.branch_code', $branchid);

       if($ord_id !=0)
       {
         $orderdata=$orderdata->where('sb_order_master.unique_key', $ord_id);
       }
       if($branch_name !=0)
       {
        $branch_name2=explode(',', $branch_name);
         $orderdata=$orderdata->whereIn('sb_order_master.branch_code', $branch_name2);
       }
       if($str_date !=0 && $end_date ==0)
       {
         $orderdata=$orderdata->where('sb_order_master.order_date', $str_date);
       }
       if($str_date !=0 && $end_date !=0)
       {
         $orderdata=$orderdata->whereBetween('sb_order_master.order_date', [$str_date, $end_date]);
       }
       if($day_over !=0)
       {
         $orderdata=$orderdata->where('sb_order_master.day_over', $day_over);
       }
       if($status !=0)
       {
         $orderdata=$orderdata->where('sb_order_master.status', $status);
       }
       if($dsf_code !=0)
       {

         $orderdata=$orderdata->where('sb_order_master.dsf_number', $dsf_code);
       }
       $orderdata=$orderdata->orderBy('sb_order_master.order_id', 'desc');
       $orderdata=$orderdata->paginate(50);


      return view('exportorderlist',['orderdata'=>$orderdata,'str_date'=>$str_date,'end_date'=>$end_date,'ord_id'=>$ord_id,'brid'=>$branch_name,'dfid'=>$dsf_code,'dover'=>$day_over,'status'=>$status]);

  
    
    }


    function ImportOrderCsv()
    {

    if(session()->has("adminname"))
    {
       session()->put("titlename","Import Order CSV - Sales Booster Admin Panel - Premier Group Of Companies");
       
      return view('import_ordercsv',['data'=>'']);

    }
    else
    {
        session()->put("titlename","Admin Login - Sales Booster Admin Panel - Premier Group Of Companies");
        return view('login',['data'=>'']);
    }
    
    }


    function CsvOrderDetail($sysid,$uqid,$dfcode,$brcode)
   {
   
    $masterdata=DB::table('sb_order_csv_log')
       ->where([
        ['sb_order_csv_log.system_generate_id', '=', $sysid],
      
       ])
      ->get();

      $mastercount=DB::table('sb_order_csv_log')
      ->where([
       ['sb_order_csv_log.system_generate_id', '=', $sysid],
     
      ])
     ->count();   
    
   //get branch name
   $branchname="";
   $sbbranch=DB::table('sb_branch_master')
   ->where([
    ['branch_code', '=', $brcode],
  
   ])
  ->get();
  foreach($sbbranch as $br)
  {
    $branchname=$br->branch_name;
  }


  //get dsf name
  $dsfname="";
  $sbdsf=DB::table('sb_dsf_master')
  ->where([
   ['branch_code', '=', $brcode],
   ['dsf_code', '=', $dfcode],
 
  ])
 ->get();
 foreach($sbdsf as $df)
 {
   $dsfname=$df->dsf_name;
 }

    
    return view('csv_order_detail',['masterdata'=>$masterdata,'sysid'=>$sysid,'uqid'=>$uqid,'dfcode'=>$dfcode,'brcode'=>$brcode,'branchname'=>$branchname,'dsfname'=>$dsfname,'mastercount'=>$mastercount]);


   }


   function CsvPlaceOrder(Request $req)
   {

      session()->put("titlename","CSV Order - Sales Booster Admin Panel - Premier Group Of Companies");
      $sysid=$req->sysid;
      $count=$req->count;
      $ordertotal=$req->ordertotal;
      $con=mysqli_connect("127.0.0.1","ytwpdncbtd","9DKdWEz2sr","ytwpdncbtd");
             

      $adminid=session('adminid');
      date_default_timezone_set("Asia/Karachi");
      $curr_date = date('y-m-d h:i:s');
      $StatusDetails = array();
      $DataDetails = array();
      $jsonTempData  = array();
      $jsonStatData = array();

      $fg_firstdata="SELECT * FROM `sb_order_csv_log` where system_generate_id='$sysid' order by log_id asc limit 1";
      $run_firstdata=mysqli_query($con,$fg_firstdata);
      $row_firstdata=mysqli_fetch_array($run_firstdata);
      $dsf_code=$row_firstdata['dsf_code'];
      $branch_code=$row_firstdata['branch_code'];
      $dsf_number=$branch_code."".$dsf_code;
      $order_date=$row_firstdata['order_date'];
      $order_date=date("Y-m-d", strtotime($order_date));
      $unique_key=$row_firstdata['unique_key'];
      
      //first check unique not exist
      $fg_uqkey="SELECT * FROM `sb_order_master` where unique_key='$unique_key'";
      $run_uqkey=mysqli_query($con,$fg_uqkey);
      if(mysqli_num_rows($run_uqkey) == 0)
      {

     $ins_master="INSERT INTO `sb_order_master`(dsf_code,branch_code,dsf_number,order_date,day_over,count,status,server_date,unique_key,order_total) VALUES
      ('$dsf_code','$branch_code','$dsf_number','$order_date','Y','$count',0,'$curr_date','$unique_key','$ordertotal')";
     $run_master=mysqli_query($con,$ins_master);

     $fg_lastid="SELECT order_id FROM `sb_order_master` order by order_id desc limit 1";
     $run_lastid=mysqli_query($con,$fg_lastid);
     $row_lastid=mysqli_fetch_array($run_lastid);
     $auto_oid=$row_lastid['order_id'];

      }
      else{
         $row_uqkey=mysqli_fetch_array($run_uqkey);
         $auto_oid=$row_uqkey['order_id'];

         $upd_mstord="update `sb_order_master` set day_over='Y',count='$count',order_total='$ordertotal' where order_id='$auto_oid'";
         $run_mstord=mysqli_query($con,$upd_mstord);
         


      }

      //master header set for api
      $StatusDetails['dsf_code'] =$dsf_code;
      $StatusDetails['branch_code'] =$branch_code;
      $StatusDetails['dsf_number'] =$dsf_number;
      $StatusDetails['order_date'] =$order_date;
      $StatusDetails['day_over'] ="Y";
      $StatusDetails['count'] ="$count"; 
      $StatusDetails['server_date'] =date("Y-m-d h:i:s");
      $StatusDetails['unique_key'] =$unique_key;
      $StatusDetails['orderid'] =$auto_oid;
      $StatusDetails['order_total'] =$ordertotal;



     $fg_getdetail="SELECT * FROM `sb_order_csv_log` where system_generate_id='$sysid'";
     $run_getdetail=mysqli_query($con,$fg_getdetail);
     while($row_getdetail=mysqli_fetch_array($run_getdetail))
     {
       $cust_code=$row_getdetail['cust_code'];
       $prod_code=$row_getdetail['prod_code'];
       $rate=$row_getdetail['rate'];
       $quantity=$row_getdetail['quantity'];
       $booking_datetime=$row_getdetail['booking_datetime'];
       $booking_datetime=date("Y-m-d h:i:s", strtotime($booking_datetime));
       $device_name=$row_getdetail['device_name'];
       $latitude=$row_getdetail['latitude'];
       $longitude=$row_getdetail['longitude'];
       $week_day=$row_getdetail['week_day'];
       $trip_no=$row_getdetail['trip_no'];
       $day_id=$row_getdetail['day_id'];
       $cash_credit=$row_getdetail['cash_credit'];
       $route=$row_getdetail['route'];
       $comment=$row_getdetail['comment'];

       //fg already check
       $fg_already="SELECT * FROM `sb_order_detail` where unique_key='$unique_key' AND customer_code='$cust_code' AND product_code='$prod_code' AND dsf_code='$dsf_code'";
       $run_already=mysqli_query($con,$fg_already);
       if(mysqli_num_rows($run_already) == 0)
       {

      $ins_detail="INSERT INTO `sb_order_detail`(order_id,unique_key,customer_code,product_code,rate,quantity,booking_datetime,device_name,server_datetime,latitude,longitude,weekdays,dsf_code,branch_code,trip_no,day_id,cash_credit,route,comment,distance,employee_code,shop_status) 
      VALUES  ('$auto_oid','$unique_key','$cust_code','$prod_code','$rate','$quantity','$booking_datetime','$device_name','$curr_date','$latitude','$longitude','$week_day','$dsf_code','$branch_code','$trip_no','$day_id','$cash_credit','$route','$comment','','$dsf_number','No')";
      $run_detail=mysqli_query($con,$ins_detail);



    $DataDetails['customer_code'] = $cust_code;
    $DataDetails['product_code'] = $prod_code;
    $DataDetails['rate'] = intval($rate);
    $DataDetails['quantity'] = intval($quantity);
    $DataDetails['booking_datetime'] = date("Y-m-d h:i:s", strtotime($booking_datetime));
    $DataDetails['device_name'] = $device_name;
    $DataDetails['server_datetime'] = date("Y-m-d h:i:s");
    $DataDetails['latitude'] = $latitude;
    $DataDetails['longitude'] = $longitude;
    $DataDetails['weekdays'] = $week_day;
    $DataDetails['dsf_code'] = "$dsf_code";
    $DataDetails['branch_code'] = "$branch_code";
    $DataDetails['trip_no'] = "$trip_no";
    $DataDetails['day_id'] = "$day_id";
    $DataDetails['cash_credit'] = "$cash_credit";
    $DataDetails['route'] = $route;
    $DataDetails['comment'] = $comment;
    $DataDetails['distance'] = "0";
    $DataDetails['employee_code'] = "$dsf_number";
    $DataDetails['shop_status'] = 'No';

    $jsonTempData[] = $DataDetails;

       
      }

     }
     $jsonStatData['OrderData'] = $jsonTempData;

$jsonData['dsf_code'] = $StatusDetails['dsf_code'];
$jsonData['branch_code'] = $StatusDetails['branch_code'];
$jsonData['dsf_number'] = $StatusDetails['dsf_number'];
$jsonData['order_date'] = $StatusDetails['order_date'];
$jsonData['day_over'] = $StatusDetails['day_over'];
$jsonData['count'] = $StatusDetails['count'];
$jsonData['server_date'] = $StatusDetails['server_date'];
$jsonData['unique_key'] = $StatusDetails['unique_key'];
$jsonData['orderid'] = $StatusDetails['orderid'];
$jsonData['order_total'] = $StatusDetails['order_total'];

$jsonData['OrderDetail'] = $jsonStatData;
$passdata=json_encode($jsonData);
// print_r($passdata);

     //insert log
     $ins_logdata="INSERT INTO `sb_order_history_log`(system_id,unique_id,row_count,upload_datetime,post_by) VALUES 
     ('$sysid','$unique_key','$count','$curr_date','$adminid')";
     $run_logdata=mysqli_query($con,$ins_logdata);

     //delete log data
     $del_logdata="delete FROM `sb_order_csv_log` where system_generate_id='$sysid'";
     $run_dellog=mysqli_query($con,$del_logdata);

    // }

    // api hit
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://test-dotnet.premiergroup.com.pk:8255/api/SND360/ReceiveOrders',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$passdata,
  CURLOPT_HTTPHEADER => array(
    'Pr3mKEY: W74=Jse==ZU1JWR158TjJuUjlVN@t3Zz09',
    'Authorization: Basic UHJFbSFlci5Hcm91cCQkJCsrOkNyRThpVmUmKl4xMjM0NTYrKw==',
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$response_data = json_decode($response);
$responsecode=$response_data->Status_Code;
$responsemessafe=$response_data->Status_Message;
if($response_data->Status_Code == 200)
{
   $upd_status="update `sb_order_master` set status=1 where unique_key='$unique_key'";
   $run_status=mysqli_query($con,$upd_status);
}
//insert bms api return log
$ins_bmslog="INSERT INTO `sb_dayend_log`(unique_key,dsf_code,bms_api_return_code,bms_api_return_status,api_name,post_datetime) VALUES 
('$unique_key','$dsf_code','$responsecode','$responsemessafe','BMSORDERPUSH','$curr_date')";
$run_bmslog=mysqli_query($con,$ins_bmslog);


    $orderdetail = DB::table('sb_order_detail')
    ->where([
        ['order_id', '=', $auto_oid],
    ])
    ->get();

    
    
    return view('export_csvorder_forbms',['orderdetail'=>$orderdetail,'oid'=>$auto_oid,'uqid'=>$unique_key]);
    
   
   }


   function ExportOrderCsvforBms($oid,$uqid)
   {
   
    $orderdetail = DB::table('sb_order_detail')
    ->where([
        ['unique_key', '=', $uqid],
    ])
    ->get();

    
    
    return view('export_csvorder_forbms',['orderdetail'=>$orderdetail,'oid'=>$oid,'uqid'=>$uqid]);


   }
   
   function ViewDsfRoute($oid,$uqid)
   {
   
    $dsfcode="";
    $dsfname="";
    $branchcode="";
    $branchname="";
    $orderdate="";
    $starttime="";
    $endtime="";
    
    $mstdata=DB::SELECT("SELECT mst.dsf_code,sd.dsf_name,mst.branch_code,sb.branch_name,mst.order_date FROM `sb_order_master` mst,sb_dsf_master sd,sb_branch_master sb where mst.dsf_code=sd.dsf_code AND sd.branch_code=mst.branch_code AND mst.branch_code=sb.branch_code AND mst.order_id='$oid'");
    foreach($mstdata as $mst)
    {
      $dsfcode=$mst->dsf_code;
      $dsfname=$mst->dsf_name;
      $branchcode=$mst->branch_code;
      $branchname=$mst->branch_name;
      $orderdate=$mst->order_date;
     
    }

    $fgstr=DB::SELECT("SELECT tag_datetime,latitude,longitude FROM `sb_dsf_latlng` where unique_key='$uqid'  order by auto_id asc limit 1");
    foreach($fgstr as $str)
    {
      $starttime=$str->tag_datetime;
      $branchlatitude=$str->latitude;
      $branchlongitude=$str->longitude;
    }

    $fgstr2=DB::SELECT("SELECT tag_datetime,latitude,longitude FROM `sb_dsf_latlng` where unique_key='$uqid'  order by auto_id desc limit 1");
    foreach($fgstr2 as $ends)
    {
      $endtime=$ends->tag_datetime;
      $endbranchlatitude=$ends->latitude;
    $endbranchlongitude=$ends->longitude;
    }
    
   
    
    
    
    
    return view('view_dsf_order_route',['unique_id'=>$uqid,'order_id'=>$oid,'dsfcode'=>$dsfcode,'dsfname'=>$dsfname,'branchcode'=>$branchcode,'branchname'=>$branchname,'orderdate'=>$orderdate,'starttime'=>$starttime,'endtime'=>$endtime,'branchlatitude'=>$branchlatitude,'branchlongitude'=>$branchlongitude,'endbranchlatitude'=>$endbranchlatitude,'endbranchlongitude'=>$endbranchlongitude]);


   }

   function GetOrderLatLng(Request $req)
    {

      $id=$req->id;
      $uid=$req->uid;
      $totalorder=DB::select("SELECT latitude,longitude FROM `sb_dsf_latlng` where unique_key='$uid' order by auto_id asc limit 1,8");
    
      return response()->json($totalorder);

    	
    }

    
    
    
   
   


}    