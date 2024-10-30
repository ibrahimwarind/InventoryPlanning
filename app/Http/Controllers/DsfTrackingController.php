<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DsfTrackingController extends Controller
{
    function DsfRouteTracking()
    {
        
    session()->put("titlename","DSF Route Tracking - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
    $admintype=session('admintype');
    $usercode=session('usercode');

    $branchdata = DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->get();

   $curdate=date('Y-m-d');
//$curdate="2024-05-18";
    if($admintype == "DsfSupervisor")
    {
        $dsfdata = DB::table('sb_dsf_businessline')
        ->join('sb_order_master', function($join)
       {
         $join->on('sb_order_master.dsf_code', '=', 'sb_dsf_businessline.dsf_code');
         $join->on('sb_order_master.branch_code', '=', 'sb_dsf_businessline.branch_code');
      
       })
        ->where([
            ['sb_order_master.order_date', '=', $curdate],
            ['sb_dsf_businessline.supply_groupid', '=', $usercode],
        ])
        ->whereIn('sb_order_master.branch_code', $branchid)
        ->groupBy('sb_dsf_businessline.dsf_code')
        ->orderBy('sb_dsf_businessline.supply_groupid', 'asc')
        ->orderBy('sb_dsf_businessline.branch_code', 'asc')
        ->paginate(10);

    }
    else 
    {

        $dsfdata = DB::table('sb_dsf_businessline')
        ->join('sb_order_master', function($join)
       {
         $join->on('sb_order_master.dsf_code', '=', 'sb_dsf_businessline.dsf_code');
         $join->on('sb_order_master.branch_code', '=', 'sb_dsf_businessline.branch_code');
      
       })
        ->where([
            ['sb_order_master.order_date', '=', $curdate],
        ])
        ->whereIn('sb_order_master.branch_code', $branchid)
        ->groupBy('sb_dsf_businessline.dsf_code')
        ->orderBy('sb_dsf_businessline.supply_groupid', 'asc')
        ->orderBy('sb_dsf_businessline.branch_code', 'asc')
        ->paginate(10);

        

    }    
    
    return view('dsf_route_tracking',['branchdata'=>$branchdata,'dsfdata'=>$dsfdata,'curdate'=>$curdate]);

    }



    function DsfRouteTracking2(Request $req)
    {
        
    session()->put("titlename","DSF Route Tracking - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
    $admintype=session('admintype');
    $usercode=session('usercode');

    $pro_branch=$req->pro_branch;
    $pro_dsf=$req->pro_dsf;
    $pro_date=$req->pro_date;

  

    $branchdata = DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->get();

   // $curdate=date('Y-m-d');
    $curdate=$pro_date;
    if($admintype == "DsfSupervisor")
    {
        $dsfdata = DB::table('sb_dsf_businessline')
        ->join('sb_order_master', function($join)
       {
         $join->on('sb_order_master.dsf_code', '=', 'sb_dsf_businessline.dsf_code');
         $join->on('sb_order_master.branch_code', '=', 'sb_dsf_businessline.branch_code');
      
       })
        ->where([
            ['sb_order_master.order_date', '=', $curdate],
            ['sb_dsf_businessline.supply_groupid', '=', $usercode],
        ]);
        $dsfdata=$dsfdata->whereIn('sb_order_master.branch_code', $branchid);
        if($pro_dsf !="")
        {
            $dsfdata=$dsfdata->where([
                ['sb_order_master.dsf_code', '=', $pro_dsf],
            ]);
        }
        if($pro_branch !="")
        {
            $dsfdata=$dsfdata->where([
                ['sb_order_master.branch_code', '=', $pro_branch],
            ]);
        }
        
        $dsfdata=$dsfdata->groupBy('sb_dsf_businessline.dsf_code');
        $dsfdata=$dsfdata-->orderBy('sb_dsf_businessline.supply_groupid', 'asc');
        $dsfdata=$dsfdata-->orderBy('sb_dsf_businessline.branch_code', 'asc');
        $dsfdata=$dsfdata-->paginate(10);

    }
    else 
    {

        $dsfdata = DB::table('sb_dsf_businessline')
        ->join('sb_order_master', function($join)
       {
         $join->on('sb_order_master.dsf_code', '=', 'sb_dsf_businessline.dsf_code');
         $join->on('sb_order_master.branch_code', '=', 'sb_dsf_businessline.branch_code');
      
       })
        ->where([
            ['sb_order_master.order_date', '=', $curdate],
        ])
        ->whereIn('sb_order_master.branch_code', $branchid);
        if($pro_dsf !="")
        {
            $dsfdata=$dsfdata->where([
                ['sb_order_master.dsf_code', '=', $pro_dsf],
            ]);
        }
        if($pro_branch !="")
        {
            $dsfdata=$dsfdata->where([
                ['sb_order_master.branch_code', '=', $pro_branch],
            ]);
        }
        $dsfdata=$dsfdata->groupBy('sb_dsf_businessline.dsf_code');
        $dsfdata=$dsfdata->orderBy('sb_dsf_businessline.supply_groupid', 'asc');
        $dsfdata=$dsfdata->orderBy('sb_dsf_businessline.branch_code', 'asc');
        $dsfdata=$dsfdata->paginate(10);

        

    }    
    $dsfdata->appends(['pro_branch' => $pro_branch,'pro_dsf'=>$pro_dsf,'pro_date'=>$pro_date]);
    
    return view('dsf_route_tracking',['branchdata'=>$branchdata,'dsfdata'=>$dsfdata,'curdate'=>$curdate]);

    }


    function DsfDay_Route($dsfcode,$brcode,$bdate,$branchname,$dsfname,$hour,$minute,$strtime,$endtime)
    {
        
        $getdsfroute=DB::SELECT("SELECT concat(latitude,',',longitude) as latlng FROM `sb_dsf_latlng` where dsf_code='$dsfcode' AND branch_code='$brcode' AND date(tag_datetime) = '$bdate' order by tag_datetime asc");
        $str_lat="";$str_lng="";$end_lat="";$end_lng="";
        $fg_start=DB::SELECT("SELECT * FROM `sb_dsf_latlng` where date(tag_datetime) = '$bdate' AND branch_code='$brcode' AND dsf_code='$dsfcode' order by auto_id asc limit 1");
        foreach($fg_start as $fg)
        {
            $str_lat=$fg->latitude;
            $str_lng=$fg->longitude;
        }

        $fg_end=DB::SELECT("SELECT * FROM `sb_dsf_latlng` where date(tag_datetime) = '$bdate' AND branch_code='$brcode' AND dsf_code='$dsfcode' order by auto_id desc limit 1");
        foreach($fg_end as $fg)
        {
            $end_lat=$fg->latitude;
            $end_lng=$fg->longitude;
        }

        $bookingamount=0;$productivecustomer=0;
        $fg_bookingam=DB::SELECT("SELECT sum(rate * quantity) as totalbooking,count(DISTINCT customer_code) as totalproductive FROM `sb_order_detail` where branch_code='$brcode' AND dsf_code='$dsfcode' AND date(booking_datetime) = '$bdate' AND comment='Productive'");
        foreach($fg_bookingam as $fgbk)
        {
            $bookingamount=$fgbk->totalbooking;
            $productivecustomer=$fgbk->totalproductive;
        }
        $overallcustomer=0;
        $fg_bookingam=DB::SELECT("SELECT count(DISTINCT customer_code) as totalproductive FROM `sb_order_detail` where branch_code='$brcode' AND dsf_code='$dsfcode' AND date(booking_datetime) = '$bdate'");
        foreach($fg_bookingam as $fgbk)
        {
            $overallcustomer=$fgbk->totalproductive;
        }


        $countdsfroute=DB::table("sb_dsf_latlng")
        ->where([
            ['dsf_code', '=', $dsfcode],
            ['branch_code', '=', $brcode],
        ])
        ->where(DB::raw("date(tag_datetime)"), "=", $bdate)
        ->count();

        $branchlat="";$branchlng="";
        $branchlatlng=DB::SELECT("SELECT location_latitude,location_longitude FROM `sb_branch_location` where branch_code='$brcode'");
        foreach($branchlatlng as $latlng)
        {
            $branchlat=$latlng->location_latitude;
            $branchlng=$latlng->location_longitude;
        }

    
        return view('dsf_day_route',['dsfcode'=>$dsfcode,'brcode'=>$brcode,'bdate'=>$bdate,'getdsfroute'=>$getdsfroute,'countdsfroute'=>$countdsfroute,'str_lat'=>$str_lat,'str_lng'=>$str_lng,'end_lat'=>$end_lat,'end_lng'=>$end_lng,'branchname'=>$branchname,'dsfname'=>$dsfname,'bookingamount'=>$bookingamount,'productivecustomer'=>$productivecustomer,'overallcustomer'=>$overallcustomer,'hour'=>$hour,'minute'=>$minute,'strtime'=>$strtime,'endtime'=>$endtime,'branchlat'=>$branchlat,'branchlng'=>$branchlng]);
    }
    
    function GetDsfDailyRoute(Request $req)
    {
        $dsfcode=$req->dsfcode;
        $brcode=$req->brcode;
        $bdate=$req->bdate;


        $getdsfroute=DB::SELECT("SELECT concat(latitude,',',longitude) as latlng FROM `sb_dsf_latlng` where dsf_code='$dsfcode' AND branch_code='$brcode' AND date(tag_datetime) = '$bdate' order by tag_datetime asc");

       return response()->json($getdsfroute);
    }
    
    function DsfBooking_Analysis()
    {
        
    session()->put("titlename","DSF Booking Analysis Management - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
    $admintype=session('admintype');
    $usercode=session('usercode');

    $branchdata = DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->get();

   $enddate=date('Y-m-d');
   $startdate=date('Y-m')."-01";

    if($admintype == "DsfSupervisor")
    {
        $dsfdata = DB::table('sb_dsf_businessline')
        ->join('sb_order_master', function($join)
       {
         $join->on('sb_order_master.dsf_code', '=', 'sb_dsf_businessline.dsf_code');
         $join->on('sb_order_master.branch_code', '=', 'sb_dsf_businessline.branch_code');
      
       })
        ->where([
            ['sb_dsf_businessline.supply_groupid', '=', $usercode],
        ])
        ->whereBetween('sb_order_master.order_date', [$startdate, $enddate])
        ->whereIn('sb_order_master.branch_code', $branchid)
        ->groupBy('sb_dsf_businessline.dsf_code')
        ->orderBy('sb_dsf_businessline.supply_groupid', 'asc')
        ->orderBy('sb_dsf_businessline.branch_code', 'asc')
        ->paginate(25);

    }
    else 
    {

        $dsfdata = DB::table('sb_dsf_businessline')
        ->join('sb_order_master', function($join)
       {
         $join->on('sb_order_master.dsf_code', '=', 'sb_dsf_businessline.dsf_code');
         $join->on('sb_order_master.branch_code', '=', 'sb_dsf_businessline.branch_code');
      
       })
        ->whereBetween('sb_order_master.order_date', [$startdate, $enddate])
        ->whereIn('sb_order_master.branch_code', $branchid)
        ->groupBy('sb_dsf_businessline.dsf_code')
        ->orderBy('sb_dsf_businessline.supply_groupid', 'asc')
        ->orderBy('sb_dsf_businessline.branch_code', 'asc')
        ->paginate(25);

        

    }    
    
    return view('dsf_booking_analysis',['startdate'=>$startdate,'enddate'=>$enddate,'branchdata'=>$branchdata,'dsfdata'=>$dsfdata]);

    }



    function DsfBooking_Analysis2(Request $req)
    {
        
    session()->put("titlename","DSF Booking Analysis Management - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
    $admintype=session('admintype');
    $usercode=session('usercode');

    $pro_branch=$req->pro_branch;
    $pro_group=$req->pro_group;
    $str_date=$req->str_date;
    $end_date=$req->end_date;

    $branchdata = DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->get();

   $enddate=$end_date;
   $startdate=$str_date;

    if($admintype == "DsfSupervisor")
    {
        $dsfdata = DB::table('sb_dsf_businessline')
        ->join('sb_order_master', function($join)
       {
         $join->on('sb_order_master.dsf_code', '=', 'sb_dsf_businessline.dsf_code');
         $join->on('sb_order_master.branch_code', '=', 'sb_dsf_businessline.branch_code');
      
       })
        ->where([
            ['sb_dsf_businessline.supply_groupid', '=', $usercode],
        ])
        ->whereBetween('sb_order_master.order_date', [$startdate, $enddate])
        ->whereIn('sb_order_master.branch_code', $branchid)
        ->groupBy('sb_dsf_businessline.dsf_code')
        ->orderBy('sb_dsf_businessline.supply_groupid', 'asc')
        ->orderBy('sb_dsf_businessline.branch_code', 'asc')
        ->paginate(25);

    }
    else 
    {

        $dsfdata = DB::table('sb_dsf_businessline')
        ->join('sb_order_master', function($join)
       {
         $join->on('sb_order_master.dsf_code', '=', 'sb_dsf_businessline.dsf_code');
         $join->on('sb_order_master.branch_code', '=', 'sb_dsf_businessline.branch_code');
      
       })
        ->whereBetween('sb_order_master.order_date', [$startdate, $enddate]);
        if($pro_group !="")
        {
            $dsfdata=$dsfdata->where([
                ['sb_dsf_businessline.supply_groupid', '=', $pro_group],
            ]);
        }
        if($pro_branch !="")
        {
            $dsfdata=$dsfdata->where([
                ['sb_order_master.branch_code', '=', $pro_branch],
            ]);
        }
        if($pro_branch =="")
        {
            $dsfdata=$dsfdata->whereIn('sb_order_master.branch_code', $branchid);
        }
        
        $dsfdata=$dsfdata->groupBy('sb_dsf_businessline.dsf_code');
        $dsfdata=$dsfdata->orderBy('sb_dsf_businessline.supply_groupid', 'asc');
        $dsfdata=$dsfdata->orderBy('sb_dsf_businessline.branch_code', 'asc');
        $dsfdata=$dsfdata->paginate(25);

        

    }    
    
    return view('dsf_booking_analysis',['startdate'=>$startdate,'enddate'=>$enddate,'branchdata'=>$branchdata,'dsfdata'=>$dsfdata]);

    }

    public function Get_Markers(Request $req)
    {
        $dsfcode=$req->dsfcode;
        $brcode=$req->brcode;
        $bdate=$req->bdate;
        // Replace this with actual data fetching logic

        $markersData = DB::select("SELECT det.customer_code,cust.customer_name,det.comment,cust.cust_latitude,cust.cust_longitude FROM sb_order_detail det inner join sb_customers cust on det.customer_code=cust.customer_code AND det.branch_code=cust.branch_code where det.dsf_code='$dsfcode' AND det.branch_code='$brcode' AND date(det.booking_datetime)='$bdate' AND cust.cust_latitude is not null group by det.customer_code");

        // Markers data ko format kar rahe hain
        $markers = array_map(function($marker) {
            $iconUrl = "http://maps.google.com/mapfiles/ms/icons/red-dot.png"; // Default icon
            if ($marker->comment == 'Productive') {
                $iconUrl = "http://maps.google.com/mapfiles/ms/icons/green-dot.png";
            } else {
                $iconUrl = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            }
            return [
                'position' => ['lat' => floatval($marker->cust_latitude), 'lng' => floatval($marker->cust_longitude)],
                'title' => $marker->customer_name." - (".$marker->comment.")",
                'icon' => [
                    'url' => $iconUrl // Default icon, aap icon URL ko customize kar sakte hain
                ]
            ];
        }, $markersData);

        return response()->json($markers);
    }

    function DsfSaf_InMap($dsfcode,$brcode,$bdate,$branchname,$dsfname,$hour,$minute,$strtime,$endtime)
    {
        
        $getdsfroute=DB::SELECT("SELECT concat(latitude,',',longitude) as latlng FROM `sb_dsf_latlng` where dsf_code='$dsfcode' AND branch_code='$brcode' AND date(tag_datetime) = '$bdate' order by tag_datetime asc");
        $str_lat="";$str_lng="";$end_lat="";$end_lng="";
        $fg_start=DB::SELECT("SELECT * FROM `sb_dsf_latlng` where date(tag_datetime) = '$bdate' AND branch_code='$brcode' AND dsf_code='$dsfcode' order by auto_id asc limit 1");
        foreach($fg_start as $fg)
        {
            $str_lat=$fg->latitude;
            $str_lng=$fg->longitude;
        }

        $fg_end=DB::SELECT("SELECT * FROM `sb_dsf_latlng` where date(tag_datetime) = '$bdate' AND branch_code='$brcode' AND dsf_code='$dsfcode' order by auto_id desc limit 1");
        foreach($fg_end as $fg)
        {
            $end_lat=$fg->latitude;
            $end_lng=$fg->longitude;
        }

        $bookingamount=0;$productivecustomer=0;
        $fg_bookingam=DB::SELECT("SELECT sum(rate * quantity) as totalbooking,count(DISTINCT customer_code) as totalproductive FROM `sb_order_detail` where branch_code='$brcode' AND dsf_code='$dsfcode' AND date(booking_datetime) = '$bdate' AND comment='Productive'");
        foreach($fg_bookingam as $fgbk)
        {
            $bookingamount=$fgbk->totalbooking;
            $productivecustomer=$fgbk->totalproductive;
        }
        $overallcustomer=0;
        $fg_bookingam=DB::SELECT("SELECT count(DISTINCT customer_code) as totalproductive FROM `sb_order_detail` where branch_code='$brcode' AND dsf_code='$dsfcode' AND date(booking_datetime) = '$bdate'");
        foreach($fg_bookingam as $fgbk)
        {
            $overallcustomer=$fgbk->totalproductive;
        }


        $countdsfroute=DB::table("sb_dsf_latlng")
        ->where([
            ['dsf_code', '=', $dsfcode],
            ['branch_code', '=', $brcode],
        ])
        ->where(DB::raw("date(tag_datetime)"), "=", $bdate)
        ->count();

        $branchlat="";$branchlng="";
        $brnames=db::select("SELECT location_latitude,location_longitude FROM `sb_branch_location` where branch_code='$brcode'");
        foreach($brnames as $br)
        {
            $branchlat=$br->location_latitude;
            $branchlng=$br->location_longitude;
        }
    
        return view('dsf_saf_map',['dsfcode'=>$dsfcode,'brcode'=>$brcode,'bdate'=>$bdate,'getdsfroute'=>$getdsfroute,'countdsfroute'=>$countdsfroute,'str_lat'=>$str_lat,'str_lng'=>$str_lng,'end_lat'=>$end_lat,'end_lng'=>$end_lng,'branchname'=>$branchname,'dsfname'=>$dsfname,'bookingamount'=>$bookingamount,'productivecustomer'=>$productivecustomer,'overallcustomer'=>$overallcustomer,'hour'=>$hour,'minute'=>$minute,'strtime'=>$strtime,'endtime'=>$endtime,'branchlat'=>$branchlat,'branchlng'=>$branchlng]);
    }
    

    function SalesmanRouteTracking()
    {
        
    session()->put("titlename","Salesman Route Tracking - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
    $admintype=session('admintype');
    $usercode=session('usercode');

    $branchdata = DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->get();

   $curdate=date('Y-m-d');
   $curdate2=date('d-M-Y');
    
  

    return view('salesman_route_tracking',['branchdata'=>$branchdata,'curdate'=>$curdate,'branchid'=>$branchid2,'curdate2'=>$curdate2]);

    }

    function SalesmanRouteTracking2(Request $req)
    {
        
    session()->put("titlename","Salesman Route Tracking - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
    $admintype=session('admintype');
    $usercode=session('usercode');

    $branchdata = DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->get();

   $curdate=$req->pro_date;
   $curdate2=date('d-M-Y', strtotime($req->pro_date));

   $pro_branch=$req->pro_branch;
   if($pro_branch !="")
   {
    $branchid2=$pro_branch;
   }
    
  

    return view('salesman_route_tracking',['branchdata'=>$branchdata,'curdate'=>$curdate,'branchid'=>$branchid2,'curdate2'=>$curdate2]);

    }
    

    function SalesmanDay_Route($smancode,$smanname,$strtime,$endtime,$totalcust,$hour,$minute,$brcode,$tripdate,$tripstrlat,$tripstrlong,$tripendlat,$tripendlong)
    {
        $brname="";
        $brnames=db::select("select * from sb_branch_master where branch_code='$brcode'");
        foreach($brnames as $br)
        {
            $brname=$br->branch_name;
        }

        $branchlat="";$branchlng="";
        $branchlatlng=DB::SELECT("SELECT location_latitude,location_longitude FROM `sb_branch_location` where branch_code='$brcode'");
        foreach($branchlatlng as $latlng)
        {
            $branchlat=$latlng->location_latitude;
            $branchlng=$latlng->location_longitude;
        }
        $tripdate2=date('d-M-Y', strtotime($tripdate));

        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://202.143.120.43:5000/salesmanstartendlocation?branchcode='.$brcode.'&suppdate='.$tripdate2.'&smancode='.$smancode.'',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic WHlqS25VazpQeXRob25AMTIzQA=='
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$response_data = json_decode($response);$indexcount=1;
           foreach ($response_data as $user) {
                 $startlat=floatval($user->startlat);
                 $startlng=floatval($user->startlng);
                 if($indexcount == 1)
                 {
                    $tripstrlat=floatval($startlat);
                    $tripstrlong=floatval($startlng);
                 }
                 else{
                    $tripendlat=floatval($startlat);
                    $tripendlong=floatval($startlng);
                 }
                 $indexcount=$indexcount + 1;
           }

         
        
        
        return view('salesman_day_route',['smancode'=>$smancode,'smanname'=>$smanname,'strtime'=>$strtime,'endtime'=>$endtime,'totalcust'=>$totalcust,'hour'=>$hour,'minute'=>$minute,'brcode'=>$brcode,'brname'=>$brname,'tripdate'=>$tripdate,'tripstrlat'=>$tripstrlat,'tripstrlong'=>$tripstrlong,'tripendlat'=>$tripendlat,'tripendlong'=>$tripendlong,'branchlat'=>$branchlat,'branchlng'=>$branchlng]);
    }
    
    function GetSalesmanDailyRoute(Request $req)
    {
        $smancode=$req->smancode;
        $brcode=$req->brcode;
        $tripdate=$req->tripdate;
        $userid=$brcode."".$smancode;
        $tripdate2=date('d-M-Y', strtotime($tripdate));


        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://202.143.120.43:5000/salesmanjourneylocation?branchcode='.$brcode.'&suppdate='.$tripdate2.'&smancode='.$smancode.'',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Basic WHlqS25VazpQeXRob25AMTIzQA=='
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $results = [];
        $response_data = json_decode($response);
        foreach ($response_data as $user) {
             $latlng=$user->latlng;
             $results[] = ['latlng' => $latlng];
        }
        
       return response()->json($results);
    }

    function Salesman_Route_Detail($smancode,$smanname,$strtime,$endtime,$totalcust,$hour,$minute,$brcode,$tripdate,$tripstrlat,$tripstrlong,$tripendlat,$tripendlong)
    {
       
        
        return view('salesman_route_detail',['smancode'=>$smancode,'smanname'=>$smanname,'strtime'=>$strtime,'endtime'=>$endtime,'totalcust'=>$totalcust,'hour'=>$hour,'minute'=>$minute,'brcode'=>$brcode,'tripdate'=>$tripdate,'tripstrlat'=>$tripstrlat,'tripstrlong'=>$tripstrlong,'tripendlat'=>$tripendlat,'tripendlong'=>$tripendlong]);
    }

    function Salesman_Shop_Detail($smancode,$smanname,$strtime,$endtime,$totalcust,$hour,$minute,$brcode,$tripdate,$tripstrlat,$tripstrlong,$tripendlat,$tripendlong)
    {
        $brname="";
        $brnames=db::select("select * from sb_branch_master where branch_code='$brcode'");
        foreach($brnames as $br)
        {
            $brname=$br->branch_name;
        }

        $branchlat="";$branchlng="";
        $brnames=db::select("SELECT location_latitude,location_longitude FROM `sb_branch_location` where branch_code='$brcode'");
        foreach($brnames as $br)
        {
            $branchlat=$br->location_latitude;
            $branchlng=$br->location_longitude;
        }

        
        return view('salesman_shop_detail',['smancode'=>$smancode,'smanname'=>$smanname,'strtime'=>$strtime,'endtime'=>$endtime,'totalcust'=>$totalcust,'hour'=>$hour,'minute'=>$minute,'brcode'=>$brcode,'tripdate'=>$tripdate,'tripstrlat'=>$tripstrlat,'tripstrlong'=>$tripstrlong,'tripendlat'=>$tripendlat,'tripendlong'=>$tripendlong,'brname'=>$brname,'branchlat'=>$branchlat,'branchlng'=>$branchlng]);
    }

    public function Get_Markers2(Request $req)
    {
        $smancode=$req->smancode;
        $brcode=$req->brcode;
        $tripdate=$req->tripdate;
        // Replace this with actual data fetching logic
        $userid=$brcode."".$smancode;

        $connection_string = 'DRIVER={ODBC Driver 18 for SQL Server};SERVER=tcp:202.142.180.146,9438;DATABASE=DeliveryManagementDB;TrustServerCertificate=yes;';
        $conn = odbc_connect( $connection_string,  'sa', 'Database123$' ) or die (odbc_errormsg());

        $markersData ="SELECT DISTINCT det.custcode,det.custname,det.iscancel,det.cancelreason,mst.phy_lat,mst.phy_long FROM mobile_timing mst left join DeliverOrdersDetail det on mst.ordercustid=det.ordercustid AND mst.userid=det.userid where mst.userid='$smancode' AND CAST(mst.starttime as Date) = '$tripdate' AND det.iscancel in (1,0)";
        $queryResult = odbc_exec($conn,$markersData);
        $markers = [];
		$indexcount=0;$overalltime=0;$overallstarttime=0;
		while(odbc_fetch_row( $queryResult )) {
			
            $custcode =odbc_result($queryResult,"custcode");
            $custname =odbc_result($queryResult,"custname");
            $iscancel =odbc_result($queryResult,"iscancel");
            $cancelreason =odbc_result($queryResult,"cancelreason");
            $phy_lat =odbc_result($queryResult,"phy_lat");
            $phy_long =odbc_result($queryResult,"phy_long");

            $cancelreasonname="";
        if($cancelreason !=0 && $cancelreason !="")
        {
            $queryreason="select reason_name from Reason_setup where reason_code='$cancelreason' AND Cancel_type=1";
            $exereason = odbc_exec($conn,$queryreason);
            while(odbc_fetch_row( $exereason )) {
			
                $cancelreasonname =odbc_result($exereason,"reason_name");

          }
        }

        $iconUrl = "https://maps.google.com/mapfiles/ms/icons/red-dot.png"; // Default icon
        if ($iscancel == '0') {
            $iconUrl = "https://maps.google.com/mapfiles/ms/icons/green-dot.png";
        } else {
            $iconUrl = "https://maps.google.com/mapfiles/ms/icons/red-dot.png";
        }

        $markers[] = [
            'position' => ['lat' => floatval($phy_lat), 'lng' => floatval($phy_long)],
            'title' => $custname." - (".$cancelreasonname.")",
            'icon' => [
                'url' => $iconUrl // Default icon, aap icon URL ko customize kar sakte hain
            ]
        ];


            
        }
       
       

        return response()->json($markers);
    }

    


}