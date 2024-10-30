<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
class IndexController extends Controller
{
    // 

    function Index()
    {
       if(session()->has("adminname"))
        {
            session()->put("titlename","Home - Inventory Planning Admin Panel - Premier Group Of Companies");
            $branchid=session('branchid');
            $branchid2=session('branchid');
            $branchid=explode(',', $branchid);

                  
         $curmnt=date('m');
        $curdy=date('d');
        $curyr=date('Y');
        $curdate=$curyr."-".$curmnt."-01";

        $ds=cal_days_in_month(CAL_GREGORIAN,$curmnt,$curyr);
        $endsdate=$curyr."-".$curmnt."-".$ds;

     
       return View('index');
        
        }
        else
        {
            session()->put("titlename","Inventory Planning - Admin Login - Premier Group Of Companies");
            return view('login',['data'=>'']);
        }
        
    }

 function Index2(Request $req)
    {
        if(session()->has("adminname"))
        {
            $branchid=session('branchid');
            $admintype=session('admintype');
                
            $currentDate = date('Y-m-d');
            $last30DaysDate = date('Y-m-d',strtotime('-30 days',strtotime($currentDate)));
            session()->put("titlename","Dashboard - Uraan B2B Online Store Admin Panel - Premier Group Of Companies");
                 // date_default_timezone_set("Asia/Karachi");
            $curmnt=date('m');
            $curdy=date('d');
            $curyr=date('Y');
            $curdate=$curyr."-".$curmnt."-01";
            
            $pendingcount=DB::SELECT("select count(order_id) as counts from z_orders where ord_status=1 AND date_ >= '2022-06-09' AND store_id in ($branchid)");
            foreach($pendingcount as $cnt)
            {
                $pendingcount=$cnt->counts;
            }

        $ds=cal_days_in_month(CAL_GREGORIAN,$curmnt,$curyr);
        $endsdate=$curyr."-".$curmnt."-".$ds;
        $avgcount=DB::SELECT("select count(order_id)/$curdy as css from z_orders where date_ between '$curdate' AND '$endsdate' AND store_id in ($branchid)");
        foreach($avgcount as $cnt)
        {
            $avgcount=$cnt->css;
        }
        $avgbook=DB::SELECT("select avg(order_total) as css from z_orders where date_ between '$curdate' AND '$endsdate' AND store_id in ($branchid) AND ord_status!=7");
        foreach($avgbook as $cnt)
        {
            $avgbook=$cnt->css;
        }
            // $pendingcount=$pendingcount[0]->counts;
    
    
            // $pendingcount=DB::table('z_orders')
            // ->where([
            // ['ord_status','=',1],
            // ['date_', '>=', '2022-06-09'],
            // ])
            // ->whereIn('store_id',[$branchid])
            // ->count();
            $transfercount=DB::SELECT("select count(order_id) as counts from z_orders where ord_status=2 AND date_ >= '2022-06-09' AND store_id in ($branchid)");
            foreach($transfercount as $cnt)
            {
                $transfercount=$cnt->counts;
            }
            // $transfercount=DB::table('z_orders')
            // ->where([
            // ['ord_status','=',2],
            // ['date_', '>=', '2022-06-09'],
            // ])
            // ->count();
            $confirmcount=DB::SELECT("select count(order_id) as counts from z_orders where ord_status=3 AND date_ >= '2022-06-09' AND store_id in ($branchid)");
            foreach($confirmcount as $cnt)
            {
                $confirmcount=$cnt->counts;
            }
            // $confirmcount=DB::table('z_orders')
            // ->where([
            // ['ord_status','=',3],
            // ['date_', '>=', '2022-06-09'],
            // ])
            // ->count();
            $dispatchcount=DB::SELECT("select count(order_id) as counts from z_orders where ord_status=4 AND date_ >= '2022-06-09' AND store_id in ($branchid)");
            foreach($dispatchcount as $cnt)
            {
                $dispatchcount=$cnt->counts;
            }
            // $dispatchcount=DB::table('z_orders')
            // ->where([
            // ['ord_status','=',4],
            // ['date_', '>=', '2022-06-09'],
            // ])
            // ->count();
            $delivercount=DB::SELECT("select count(order_id) as counts from z_orders where ord_status=5 AND date_ >= '2022-06-09' AND store_id in ($branchid)");
            foreach($delivercount as $cnt)
            {
                $delivercount=$cnt->counts;
            }
            // $delivercount=DB::table('z_orders')
            // ->where([
            // ['ord_status','=',5],
            // ['date_', '>=', '2022-06-09'],
            // ])
            // ->count();
            $cancelcount=DB::SELECT("select count(order_id) as counts from z_orders where ord_status in (7,9) AND date_ >= '2022-06-09' AND store_id in ($branchid)");
            foreach($cancelcount as $cnt)
            {
                $cancelcount=$cnt->counts;
            }
    
            // $cancelcount=DB::table('z_orders')
            // ->where([
            // // ['ord_status','=',7],
            // ['date_', '>=', '2022-06-09'],
            // ])
            // ->whereIn('ord_status',array(7,9))
            // ->count();
            $totalcount=DB::SELECT("select count(order_id) as counts from z_orders where  date_ >= '2022-06-09' AND store_id in ($branchid)");
            foreach($totalcount as $cnt)
            {
                $totalcount=$cnt->counts;
            }
            // $totalcount=DB::table('z_orders')
            // ->where([
            // ['date_', '>=', '2022-06-09'],
            // ])
            // ->count();
            $lasttotalcount=DB::SELECT("select count(order_id) as counts from z_orders where  date_ >= '$curdate' AND store_id in ($branchid)");
            foreach($lasttotalcount as $cnt)
            {
                $lasttotalcount=$cnt->counts;
            }
            // $lasttotalcount=DB::table('z_orders')
            // ->where([
            // ['z_orders.date_', '>=', $curdate],
            // ])
            // ->count();
            $lastdelivercount=DB::SELECT("select count(order_id) as counts from z_orders where  date_ >= '$curdate' AND ord_status=5 AND store_id in ($branchid)");
            foreach($lastdelivercount as $cnt)
            {
                $lastdelivercount=$cnt->counts;
            }
            // $lastdelivercount=DB::table('z_orders')
            // ->where([
            // ['ord_status','=',5],
            // ['z_orders.date_', '>=', $curdate],
            // ])
            // ->count();
            $lastcancelcount=DB::SELECT("select count(order_id) as counts from z_orders where  date_ >= '$curdate' AND ord_status=7 AND store_id in ($branchid)");
            foreach($lastcancelcount as $cnt)
            {
                $lastcancelcount=$cnt->counts;
            }
            // $lastcancelcount=DB::table('z_orders')
            // ->where([
            // ['ord_status','=',7],
            // ['z_orders.date_', '>=', $curdate],
            // ])
            // ->count();
    
            $totalrevenue=DB::SELECT("select sum(fnl.net_price) as renprice from z_orders_slab_price_final fnl,z_orders ord where ord.order_id=fnl.order_id AND fnl.posted_date >= '2022-06-09'  AND ord.store_id in ($branchid)");
            foreach($totalrevenue as $cnt)
            {
                $totalrevenue=$cnt->renprice;
            }
        //   $totalrevenue = DB::table('z_orders_slab_price_final')
        //    ->where([
        //     ['posted_date', '>=', '2022-06-09'],
        //    ])
        // ->sum('net_price');
    
    
    
         // $startdate=YEAR(CURRENT_DATE()) + '-'+MONTH(CURRENT_DATE())+'-01';
         // $enddate=YEAR(CURRENT_DATE()) + '-'+MONTH(CURRENT_DATE())+'-30';
         $totalcurrentmonth=DB::SELECT("select sum(fnl.net_price) as renprice from z_orders_slab_price_final fnl,z_orders ord where fnl.order_id=ord.order_id AND month(ord.date_)='".date('m')."' and year(ord.date_)='".date('Y')."' AND ord.ord_status=5 AND ord.store_id in ($branchid)");
            foreach($totalcurrentmonth as $cnt)
            {
                $totalcurrentmonth=$cnt->renprice;
            }
    
        //  $totalcurrentmonth = DB::table('z_orders')
        // ->join('z_orders_slab_price_final', 'z_orders.order_id', '=', 'z_orders_slab_price_final.order_id')
        // ->whereMonth('z_orders.date_',date('m'))
        // ->whereYear('z_orders.date_',date('Y'))
        // ->where([
        //     ['z_orders.ord_status', '=', 5],
        // ])
        // ->sum('z_orders_slab_price_final.net_price');
    
        $overallcurrentmonth=DB::SELECT("select count(order_id) as orderids from z_orders where  date_ >= '$curdate' AND ord_status=5 AND store_id in ($branchid)");
      
        $totalcurrentperformamonth=DB::SELECT("select sum(fnl.net_price) as renprice from z_orders_slab_price fnl,z_orders ord where fnl.order_id=ord.order_id AND month(ord.date_)='".date('m')."' and year(ord.date_)='".date('Y')."' AND ord.store_id in ($branchid)");
            foreach($totalcurrentperformamonth as $cnt)
            {
                $totalcurrentperformamonth=$cnt->renprice;
            }
        // $totalcurrentperformamonth = DB::table('z_orders')
        // ->join('z_orders_slab_price', 'z_orders.order_id', '=', 'z_orders_slab_price.order_id')
        // ->whereMonth('z_orders.date_',date('m'))
        // ->whereYear('z_orders.date_',date('Y'))
        // ->sum('z_orders_slab_price.net_price');
    
        $overallcurrentperformamonth=DB::SELECT("SELECT count(DISTINCT ord.order_id) as orderids FROM z_orders ord,z_orders_slab_price fnl WHERE ord.order_id=fnl.order_id AND YEAR(ord.date_) = '".date('Y')."' AND MONTH(ord.date_) = '".date('m')."' AND ord.store_id in ($branchid)");
    
        $totalcurrentbookingmonth=DB::SELECT("select sum(ord.order_total) as renprice from z_orders ord where month(ord.date_)='".date('m')."' and year(ord.date_)='".date('Y')."' AND ord.store_id in ($branchid)");
            foreach($totalcurrentbookingmonth as $cnt)
            {
                $totalcurrentbookingmonth=$cnt->renprice;
            }
      
        // $totalcurrentbookingmonth = DB::table('z_orders')
        // ->whereMonth('z_orders.date_',date('m'))
        // ->whereYear('z_orders.date_',date('Y'))
        // ->sum('z_orders.order_total');
    
        $overallcurrentbookingmonth=DB::SELECT("SELECT count(DISTINCT ord.order_id) as orderids FROM z_orders ord WHERE YEAR(ord.date_) = '".date('Y')."' AND MONTH(ord.date_) = '".date('m')."' AND ord.store_id in ($branchid) ");
        // $overallcurrentmonth = DB::table('z_orders')
        // ->join('z_orders_slab_price_final', 'z_orders.order_id', '=', 'z_orders_slab_price_final.order_id')
        // ->whereMonth('z_orders.date_',date('m'))
        // ->whereYear('z_orders.date_',date('Y'))
        // ->select('z_orders.order_id')
        // ->groupBy('z_orders.order_id')
        // ->count();
    if(date('m') == "01" || date('m') == "1")
    {
        $lastmn=12;
        $lastyr=date('Y') -1;
    }
    else{
        $lastmn=date('m')-1;
        $lastyr=date('Y');
    }
    
    
    $totallastmonth=DB::SELECT("select sum(fnl.net_price) as renprice from z_orders_slab_price_final fnl,z_orders ord where fnl.order_id=ord.order_id AND month(ord.date_)='$lastmn' and year(ord.date_)='$lastyr' AND ord.ord_status=5 AND ord.store_id in ($branchid)");
            foreach($totallastmonth as $cnt)
            {
                $totallastmonth=$cnt->renprice;
            }
    
    // $totallastmonth = DB::table('z_orders')
    //     ->join('z_orders_slab_price_final', 'z_orders.order_id', '=', 'z_orders_slab_price_final.order_id')
    //     ->whereMonth('z_orders.date_',$lastmn)
    //     ->whereYear('z_orders.date_',$lastyr)
    //     ->where([
    //         ['z_orders.ord_status', '=', 5],
    //     ])
    //     ->sum('z_orders_slab_price_final.net_price');
    
    
        $overalllastmonth=DB::SELECT("SELECT count(DISTINCT ord.order_id) as orderids FROM z_orders ord,z_orders_slab_price_final fnl WHERE ord.order_id=fnl.order_id AND YEAR(ord.date_) = '".$lastyr."' AND MONTH(ord.date_) = '".$lastmn."' AND ord.store_id in ($branchid)");
       
        //performa
        $totallastperformamonth=DB::SELECT("select sum(fnl.net_price) as renprice from z_orders_slab_price fnl,z_orders ord where fnl.order_id=ord.order_id AND month(ord.date_)='$lastmn' and year(ord.date_)='$lastyr' AND ord.store_id in ($branchid)");
            foreach($totallastperformamonth as $cnt)
            {
                $totallastperformamonth=$cnt->renprice;
            }
        // $totallastperformamonth = DB::table('z_orders')
        // ->join('z_orders_slab_price', 'z_orders.order_id', '=', 'z_orders_slab_price.order_id')
        // ->whereMonth('z_orders.date_',$lastmn)
        // ->whereYear('z_orders.date_',$lastyr)
        // ->sum('z_orders_slab_price.net_price');
    
        $overalllastperformamonth=DB::SELECT("SELECT count(DISTINCT ord.order_id) as orderids FROM z_orders ord,z_orders_slab_price fnl WHERE ord.order_id=fnl.order_id AND YEAR(ord.date_) = '".$lastyr."' AND MONTH(ord.date_) = '".$lastmn."' AND ord.store_id in ($branchid)");
       
         //booking
        $totallastbookingmonth=DB::SELECT("select sum(ord.order_total) as renprice from z_orders ord where  month(ord.date_)='$lastmn' and year(ord.date_)='$lastyr' AND ord.store_id in ($branchid)");
            foreach($totallastbookingmonth as $cnt)
            {
                $totallastbookingmonth=$cnt->renprice;
            }
         // $totallastbookingmonth = DB::table('z_orders')
         // ->whereMonth('z_orders.date_',$lastmn)
         // ->whereYear('z_orders.date_',$lastyr)
         // ->sum('z_orders.order_total');
     
         $overalllastbookingmonth=DB::SELECT("SELECT count(DISTINCT ord.order_id) as orderids FROM z_orders ord WHERE YEAR(ord.date_) = '".$lastyr."' AND MONTH(ord.date_) = '".$lastmn."' AND ord.store_id in ($branchid)");
       
        // $overalllastmonth = DB::table('z_orders')
        // ->join('z_orders_slab_price_final', 'z_orders.order_id', '=', 'z_orders_slab_price_final.order_id')
        // ->whereMonth('z_orders.date_',date('m')-1)
        // ->whereYear('z_orders.date_',date('Y'))
        // ->sum('z_orders_slab_price_final.net_price');
        $totalsale=DB::SELECT("select sum(order_total) as renprice from z_orders where  ord_status!=7 AND date_ ='".date('Y-m-d')."' AND store_id in ($branchid)");
            foreach($totalsale as $cnt)
            {
                $totalsale=$cnt->renprice;
            }
    
        // $totalsale = DB::table('z_orders')
        // ->where([
        //     ['ord_status', '!=', 7],
        //     ['date_', '=', date('Y-m-d')],
        // ])
        // ->sum('order_total');
    
          // $totalusers = DB::table('member')
        
          // ->count();
         $totalusers=DB::SELECT("SELECT user_id FROM `z_orders` where date_ >= '2022-06-09' AND store_id in ($branchid) group by user_id ");
         $indexs=0;
            foreach($totalusers as $cnt)
            { 
                $indexs=$indexs + 1;
            }
         $totalusers=$indexs;
    
        // $totalusers=DB::table('z_orders')
        //     ->select('user_id')
        //     ->where([
        //         ['date_', '>=', '2022-06-09'],
        //         ])
        //     ->groupBy('user_id')
            
        //     ->get();
    
          //not user active user
          $activeusers = DB::table('member')
          ->where([
           ['status','=',1]
          ])
        
          ->count();
         $lasttotalusers=DB::SELECT("SELECT user_id FROM `z_orders` where date_ >= '$curdate' AND store_id in ($branchid) group by user_id ");
         $indexs=0;
            foreach($lasttotalusers as $cnt)
            { 
                $indexs=$indexs + 1;
            }
         $lasttotalusers=$indexs;
    
          // $lasttotalusers=DB::table('z_orders')
          //   ->select('user_id')
          //   ->where([
          //   ['date_', '>=', $curdate],
          //   ])
          //   ->groupBy('user_id')
          //   ->get(); 
        // $customerdata = DB::table('member')
        // // ->orderBy('memberid', 'desc')
        // ->get();
         $allorder=DB::SELECT("select count(ord.order_id) as renprice from z_orders ord,b2b_city ct where ord.delivery_city=ct.id AND  date_ >='2022-06-09' AND ord.store_id in ($branchid)");
            foreach($allorder as $cnt)
            {
                $allorder=$cnt->renprice;
            }
    
           // $allorder=DB::table('z_orders')
           //  ->join('b2b_city', 'z_orders.delivery_city', '=', 'b2b_city.id')
    
           //  ->where([
           //  ['z_orders.date_', '>=', '2022-06-09'],
           //   ])
           //  ->count();
    
        
    
    //1 active
    
        // MONTH(date_) = MONTH(CURRENT_DATE()) AND YEAR(date_) = YEAR(CURRENT_DATE())
    
    $topcompany=DB::select("SELECT  sum(fnl.qty) as tqty,sum(fnl.net_price) as tprice,pro.company_id as tcompany,comp.title FROM `z_orders_slab_price_final` fnl,product pro,company comp,z_orders ord where fnl.posted_date >='$last30DaysDate' AND fnl.product_code=pro.productcode AND pro.company_id=comp.id AND fnl.order_id=ord.order_id AND ord.store_id in ($branchid) group by pro.company_id order by sum(fnl.net_price) desc limit 5");
    
        $topproduct=DB::SELECT("SELECT sum(fnl.qty) as tqty,sum(fnl.net_price) as tprice,pro.productname FROM `z_orders_slab_price_final` fnl,product pro,z_orders ord where fnl.posted_date >='$last30DaysDate' AND fnl.product_code=pro.productcode AND fnl.order_id=ord.order_id AND ord.store_id in ($branchid) group by fnl.product_code order by sum(fnl.net_price) desc limit 5");
    
        $topbrick=DB::SELECT("SELECT  sum(fnl.qty) as tqty,sum(fnl.net_price) as tprice,mem.brick_name FROM `z_orders_slab_price_final` fnl,z_orders ord,member mem where fnl.posted_date >='$last30DaysDate' AND fnl.order_id=ord.order_id AND ord.user_id=mem.memberid  AND ord.store_id in ($branchid) group by mem.brick_name order by sum(fnl.net_price) desc limit 5");
    

          return view('index',['pendingcount'=>$pendingcount,'transfercount'=>$transfercount,'confirmcount'=>$confirmcount,'dispatchcount'=>$dispatchcount,'delivercount'=>$delivercount,'cancelcount'=>$cancelcount,'totalcount'=>$totalcount,'totalrevenue'=>$totalrevenue,'totalcurrentmonth'=>$totalcurrentmonth,'totalsale'=>$totalsale,'totalusers'=>$totalusers,'activeusers'=>$activeusers,'allorder'=>$allorder,'sdate'=>$req->str_date,'totallastmonth'=>$totallastmonth,'topcompany'=>$topcompany,'topproduct'=>$topproduct,'topbrick'=>$topbrick,'lasttotalcount'=>$lasttotalcount,'lastdelivercount'=>$lastdelivercount,'lastcancelcount'=>$lastcancelcount,'lasttotalusers'=>$lasttotalusers,'overallcurrentmonth'=>$overallcurrentmonth,'overalllastmonth'=>$overalllastmonth,'totalcurrentperformamonth'=>$totalcurrentperformamonth,'overallcurrentperformamonth'=>$overallcurrentperformamonth,'totallastperformamonth'=>$totallastperformamonth,'overalllastperformamonth'=>$overalllastperformamonth,'totalcurrentbookingmonth'=>$totalcurrentbookingmonth,'overallcurrentbookingmonth'=>$overallcurrentbookingmonth,'totallastbookingmonth'=>$totallastbookingmonth,'overalllastbookingmonth'=>$overalllastbookingmonth,'avgcount'=>$avgcount,'avgbook'=>$avgbook]);
        }
        else
        {
            session()->put("titlename","Admin Login - Uraan B2B Online Store Admin Panel - Premier Group Of Companies");
            return view('login',['data'=>'']);
        }
        
    }


    function CustomerPerformance(Request $req)
    {
session()->put("titlename","Customer Performance - Uraan B2B Online Store Admin Panel - Premier Group Of Companies");

    $str_date=$req->str_date;
    $end_date=$req->end_date;
    $usid=$req->usid;
    $branchid=session('branchid');
    $admintype=session('admintype');
    $branchid=explode(',', $branchid);
    //echo count($branchid);
    
    if($usid == 0)
    {


    $orderuser = DB::table('z_orders')
    ->select('z_orders.user_id')
    ->join('z_orders_slab_price_final', 'z_orders.order_id', '=', 'z_orders_slab_price_final.order_id')
    
    
    ->whereBetween('z_orders.date_', [$str_date, $end_date])
    ->whereIn('z_orders.store_id', $branchid)
    ->where([
        ['z_orders.ord_status','!=',7],
        ])
    ->groupBy('z_orders.user_id')
    ->paginate(50);


    }
    else {

    $orderuser = DB::table('z_orders')
    ->select('z_orders.user_id')
    ->join('z_orders_slab_price_final', 'z_orders.order_id', '=', 'z_orders_slab_price_final.order_id')
    
    
    ->whereBetween('z_orders.date_', [$str_date, $end_date])
    ->whereIn('z_orders.store_id', $branchid)
    ->where([
        ['z_orders.ord_status','!=',7],
        ['z_orders.user_id','=',$usid],
        ])
    ->groupBy('z_orders.user_id')
    ->paginate(50);

    }
    $orderuser->appends(['str_date' => $str_date,'end_date'=>$end_date,'usid'=>$usid]);




    return view('customer_performance',['orderuser'=>$orderuser,'str_date'=>$str_date,'end_date'=>$end_date,'usid'=>$usid]);
    
    }

    function ExportPerformanceReport($sdate,$edate)
    {
    
    

   $branchid=session('branchid');
   $branchid=explode(',', $branchid);
   
    $orderuser = DB::table('z_orders')
    ->select('z_orders.user_id')
    ->join('z_orders_slab_price_final', 'z_orders.order_id', '=', 'z_orders_slab_price_final.order_id')
    ->whereBetween('z_orders.date_', [$sdate, $edate])
    ->whereIn('z_orders.store_id', $branchid)
    ->where([
        ['z_orders.ord_status','!=',7],
        ])
    ->groupBy('z_orders.user_id')
    ->get();


    return view('export_customer_performance',['orderuser'=>$orderuser,'str_date'=>$sdate,'end_date'=>$edate]);
    
    }

    function CustomerPerformanceOrderDetail($sdate,$edate,$id)
    {


    $orderdetail = DB::table('z_orders_detail')
    ->join('product', 'z_orders_detail.prd_id', '=', 'product.productid')
    ->join('z_orders', 'z_orders_detail.ord_id', '=', 'z_orders.order_id')
    ->whereBetween('z_orders.date_', [$sdate, $edate])
    ->where([
         ['z_orders.ord_status','!=',7],
        ['z_orders.user_id','=',$id],
      
    ])
    ->get();

    //  $proformadetail = DB::table('z_orders_slab_price')

    // ->join('product', 'z_orders_slab_price.product_code', '=', 'product.productcode')
    // ->where([
    //     ['z_orders_slab_price.order_id', '=', $id],
      
    // ])
    // ->get();

    // $deliverdetail = DB::table('z_orders_slab_price_final')
    // ->join('product', 'z_orders_slab_price_final.product_code', '=', 'product.productcode')
    // ->where([
    //     ['z_orders_slab_price_final.order_id', '=', $id],
      
    // ])
    // ->get();



    // $orderuser = DB::table('z_orders')
    // ->join('product', 'z_orders_slab_price_final.product_code', '=', 'product.productcode')
    // ->whereBetween('z_orders.date_', [$sdate, $edate])
    // ->where([
    //     ['z_orders.ord_status','!=',7],
    //     ['z_orders.user_id','=',$id],
    //     ])
    // ->get();


    return view('customer_performance_order_detail',['orderuser'=>$orderdetail,'str_date'=>$sdate,'end_date'=>$edate,'uid'=>$id]);

    }
  
  function ChangeSession($id)
    {
        if($id == 140)
        {
             session()->put("branchid",140);
             session()->put("branchname","Mehran Town Branch"); 
        }
        else if($id ==143)
        {
             session()->put("branchid",143);
             session()->put("branchname","FB Area Branch"); 
          
        }
        else if($id ==105)
        {
             session()->put("branchid",105);
             session()->put("branchname","S.I.T.E Area Branch"); 
          
        }
        else if($id ==110)
        {
             session()->put("branchid",110);
             session()->put("branchname","Hyderabad Branch"); 
          
        }
    else if($id ==145)
        {
        
             session()->put("branchid","140,143,105,110");
             session()->put("branchname","All Branch's"); 
        
        }

        return redirect('/index');
    }


    
  function TestPage()
  {
    $con=mysqli_connect("127.0.0.1","ytwpdncbtd","9DKdWEz2sr","ytwpdncbtd");

    // $fg_getadmin="select admin_id from admin where admin_type in ('DsfSupervisor','user')";
    // $run_getadmin=mysqli_query($con,$fg_getadmin);
    // while($row_getadmin=mysqli_fetch_array($run_getadmin))
    // {
    //     $admin_id=$row_getadmin['admin_id'];

    //     $ins_userright="INSERT INTO `sb_user_rights`(user_id,sub_menu_id,main_menu_id) 
    //     VALUES ('$admin_id',89,26)";
    //     $run_userright=mysqli_query($con,$ins_userright);

    //     $ins_userright="INSERT INTO `sb_user_rights`(user_id,sub_menu_id,main_menu_id) 
    //     VALUES ('$admin_id',90,27)";
    //     $run_userright=mysqli_query($con,$ins_userright);
    // }

    // $curl = curl_init();

    // curl_setopt_array($curl, array(
    //   CURLOPT_URL => 'https://api-gw-test.jazzcash.com.pk/jazzcash/thrid-pary-integrations/rest/api/v3/account/thirdparty/qrcode/generate',
    //   CURLOPT_RETURNTRANSFER => true,
    //   CURLOPT_ENCODING => '',
    //   CURLOPT_MAXREDIRS => 10,
    //   CURLOPT_TIMEOUT => 0,
    //   CURLOPT_FOLLOWLOCATION => true,
    //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //   CURLOPT_CUSTOMREQUEST => 'POST',
    //   CURLOPT_POSTFIELDS =>'{
    //     "data": "8577571F83A4CBDC89C0D69155596BC2E526F741167C914DA45329C7B1D6D99BA5B4077A484207C4404F6B1BB2E0CAFC25EEBA803647EF30B48BE1A6FDC5958C"
    // }',
    //   CURLOPT_HTTPHEADER => array(
    //     'X-CHANNEL: merchantApp',
    //     'X-MSISDN: 923094006278',
    //     'X-IBM-CLIENT-ID: 5ef5a981c1aa06f5103cdefeec2d990b',
    //     'X-IBM-CLIENT-SECRET: ba3d0dff0b40af95c042e2a8f724c24c',
    //     'Content-Type: application/json'
    //   ),
    // ));
    
    // $response = curl_exec($curl);
    
    
    // echo $response;
    
    
    // if (curl_errno($curl)) {
    //   echo  $error_msg = curl_error($curl);
    // }
    // curl_close($curl);
    // $fg_pl="SELECT * FROM `tbl_bbc_mapping` where branch_id=140 and brick_code=10083";
    // $run_pl=mysqli_query($con,$fg_pl);
    // while($row_pl=mysqli_fetch_array($run_pl))
    // {
    //     $company_id=$row_pl['company_id'];

    //     $ins_new="INSERT INTO `tbl_bbc_mapping`(brick_code,company_id,branch_id,noofinvoice) VALUES ('10039','$company_id',140,0)";
    //     $run_new=mysqli_query($con,$ins_new);
    // }
  
//     $fg_apisaf="SELECT * FROM `tbl_dsf_api_saf` where dsf_code=1977";
//     $run_apisaf=mysqli_query($con,$fg_apisaf);
//     while($row_apisaf=mysqli_fetch_array($run_apisaf))
//     {
//         $dsf_id=3;
//         $branch_code=$row_apisaf['branch_code'];
//         $day_name=$row_apisaf['day_name'];
//         $customer_code=$row_apisaf['customer_code'];
//         $brick_code=$row_apisaf['brick_code'];

//         $chk_cust="SELECT dsaf.dsf_code,dsaf.day_name,df.dsf_id FROM `tbl_dsf_api_saf` dsaf,tbl_dsf df where dsaf.dsf_code=df.dsf_code AND dsaf.customer_code='$customer_code' AND dsaf.dsf_code!=1977";
//         $run_cust=mysqli_query($con,$chk_cust);
//         if(mysqli_num_rows($run_cust) !=0){
//         while($row_cust=mysqli_fetch_array($run_cust))
//         {
//             $newdsf_code=$row_cust['dsf_code'];
//             $newdsf_id=$row_cust['dsf_id'];
//             $newday_name=$row_cust['day_name'];

//             //company check
//             $fg_dcomp="select * from tbl_dsf_company where company_id in (SELECT company_id FROM `tbl_dsf_company` where dsf_id='$dsf_id') AND dsf_id='$newdsf_id'";
//             $run_dcomp=mysqli_query($con,$fg_dcomp);
//             $row_dcomp=mysqli_num_rows($run_dcomp);
//             if($row_dcomp !=0)
//             {
//                 if($day_name == "MONDAY"){$finaldayname="MONDAY,TUESDAY,WEDNESDAY";}
//                 else if($day_name == "TUESDAY"){$finaldayname="TUESDAY,WEDNESDAY,THURSDAY";}
//                 else if($day_name == "WEDNESDAY"){$finaldayname="WEDNESDAY,THURSDAY,FRIDAY";}
//                 else if($day_name == "THURSDAY"){$finaldayname="THURSDAY,FRIDAY,SATURDAY";}
//                 else if($day_name == "FRIDAY"){$finaldayname="FRIDAY,SATURDAY,SUNDAY";}
//                 else if($day_name == "SATURDAY"){$finaldayname="SATURDAY,SUNDAY,MONDAY";}
//                 else if($day_name == "SUNDAY"){$finaldayname="SUNDAY,MONDAY,TUESDAY";}
//                 else if($day_name == "MONDAY-2"){$finaldayname="MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY";}
//                 else if($day_name == "TUESDAY-2"){$finaldayname="MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY";}
//                 else if($day_name == "WEDNESDAY-2"){$finaldayname="MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY";}
//                 else if($day_name == "THURSDAY-2"){$finaldayname="MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY";}
//                 else if($day_name == "FRIDAY-2"){$finaldayname="MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY";}
//                 else if($day_name == "SATURDAY-2"){$finaldayname="MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY";}
               

//             }
//             else{
//                 $finaldayname="MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY";
//             }

//         }

//       }
//       else{
//         $finaldayname="MONDAY,TUESDAY,WEDNESDAY,THURSDAY,FRIDAY,SATURDAY,SUNDAY";
        
//       }

// $newstr=explode(",",$finaldayname);
// $gh_mem="select memberid from member where customer_code='$customer_code'";
// $run_mem=mysqli_query($con,$gh_mem);
// $row_mem=mysqli_fetch_array($run_mem);
// $memberid=$row_mem['memberid'];
// foreach($newstr as $new)
// {
//     echo $dsf_id;
//     //ins
//     $ins_master="INSERT INTO `tbl_dsf_brick2`(dsf_id,brick_code,customer_id,area_name,day_name,posted_date) VALUES ('$dsf_id','$brick_code','$memberid','','$new',NOW())";
//     $run_master=mysqli_query($con,$ins_master);

// }
        



    // }

    
    //    return view('welcome');
  }
}
