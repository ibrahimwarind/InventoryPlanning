<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;

class GeoFancingController extends Controller
{

    function DsfFancing_LockUnlock()
    {
        
    session()->put("titlename","Dsf Wise Fancing Management - Sales Booster Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');$branchname=session('branchname');
    $usercode=session('usercode');
   // $branchid=explode(',', $branchid);

   $productdata=DB::table('sb_dsf_master')
   ->join('sb_dsf_businessline', function($join)
                         {
                             $join->on('sb_dsf_master.branch_code', '=', 'sb_dsf_businessline.branch_code');
                             $join->on('sb_dsf_master.dsf_code', '=', 'sb_dsf_businessline.dsf_code');
                             
                         })
   
   ->where([
    ['sb_dsf_businessline.branch_code','=',$branchid],
    ['sb_dsf_businessline.supply_groupid','=',$usercode],
    ])
   ->paginate(150);


    
   
    return view('dsffancing_lockunlock',['productdata'=>$productdata,'branchid'=>$branchid,'usercode'=>$usercode]);
    }

    function DsfFancingStatusChange(Request $req)
    {
        DB::table('sb_dsf_master')
        ->where([
        ['dsf_id', '=', $req->id],
        ])
        ->update([
            'dsf_fancing' => $req->status
        ]);

        //insert log
        date_default_timezone_set("Asia/Karachi");
        $curr_date = date('Y-m-d h:i:s');
        $logincode=session('usercode');

        DB::table('sb_geofancing_lockunlock_log')
        ->insert([
            'logname' => "DSFLOCK",
            'branch_code' => $req->branchid,
            'code'=>$req->dsfcode,
            'change_status'=>$req->status,
            'change_datetime'=>$curr_date,
            'change_user'=>$logincode,
            
        ]);

   
        return response()->json(['success' => true]);
    }

    function CustomerGeoFancing_Lockunlock()
    {
        
    session()->put("titlename","Customer Wise Fancing Management - Sales Booster Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');$branchname=session('branchname');
    $usercode=session('usercode');
   // $branchid=explode(',', $branchid);

   $productdata=DB::table('sb_dsf_saf_detail_new')
   ->join('sb_dsf_businessline', function($join)
                         {
                             $join->on('sb_dsf_saf_detail_new.branch_code', '=', 'sb_dsf_businessline.branch_code');
                             $join->on('sb_dsf_saf_detail_new.dsf_code', '=', 'sb_dsf_businessline.dsf_code');
                             
                         })
    ->join('sb_customers', 'sb_dsf_saf_detail_new.customer_code', '=', 'sb_customers.customer_code')
    ->select('sb_dsf_saf_detail_new.customer_code','sb_dsf_saf_detail_new.branch_code','sb_customers.is_fancing','sb_customers.customer_name','sb_customers.customer_address','sb_customers.cust_latitude','sb_customers.cust_longitude')
    ->where([
    ['sb_dsf_businessline.branch_code','=',$branchid],
    ['sb_dsf_businessline.supply_groupid','=',$usercode],
    ['sb_customers.branch_code','=',$branchid],
    ['sb_customers.cust_latitude','!=',''],
    ['sb_customers.cust_longitude','!=',''],
    ])
    ->groupBy('sb_dsf_saf_detail_new.branch_code','sb_dsf_saf_detail_new.customer_code')
    ->orderBy('sb_dsf_saf_detail_new.dsf_code', 'asc')
    ->paginate(100);


    
   
    return view('customerfancing_lockunlock',['productdata'=>$productdata,'branchid'=>$branchid,'usercode'=>$usercode,'custcode'=>'']);
    }

    function CustomerFancingStatusChange(Request $req)
    {
        DB::table('sb_customers')
        ->where([
        ['customer_code', '=', $req->custcode],
        ['branch_code', '=', $req->brcode],
        ])
        ->update([
            'is_fancing' => $req->status
        ]);

        //insert log
        date_default_timezone_set("Asia/Karachi");
        $curr_date = date('Y-m-d h:i:s');
        $logincode=session('usercode');

        DB::table('sb_geofancing_lockunlock_log')
        ->insert([
            'logname' => "CUSTOMERLOCK",
            'branch_code' => $req->brcode,
            'code'=>$req->custcode,
            'change_status'=>$req->status,
            'change_datetime'=>$curr_date,
            'change_user'=>$logincode,
            
        ]);

   
        return response()->json(['success' => true]);
    }


    function CustomerGeoFancing_Lockunlock2(Request $req)
    {
        
    session()->put("titlename","Customer Wise Fancing Management - Sales Booster Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');$branchname=session('branchname');
    $usercode=session('usercode');
    $cust_code=$req->cust_code;
   // $branchid=explode(',', $branchid);

   $productdata=DB::table('sb_dsf_saf_detail_new')
   ->join('sb_dsf_businessline', function($join)
                         {
                             $join->on('sb_dsf_saf_detail_new.branch_code', '=', 'sb_dsf_businessline.branch_code');
                             $join->on('sb_dsf_saf_detail_new.dsf_code', '=', 'sb_dsf_businessline.dsf_code');
                             
                         })
    ->join('sb_customers', 'sb_dsf_saf_detail_new.customer_code', '=', 'sb_customers.customer_code')
    ->select('sb_dsf_saf_detail_new.customer_code','sb_dsf_saf_detail_new.branch_code','sb_customers.is_fancing','sb_customers.customer_name','sb_customers.customer_address','sb_customers.cust_latitude','sb_customers.cust_longitude')
    ->where([
    ['sb_dsf_businessline.branch_code','=',$branchid],
    ['sb_dsf_businessline.supply_groupid','=',$usercode],
    ['sb_customers.branch_code','=',$branchid],
    ['sb_customers.cust_latitude','!=',''],
    ['sb_customers.cust_longitude','!=',''],
    [function($query) use ($cust_code){
        $query->orWhere('sb_dsf_saf_detail_new.customer_code', '=', $cust_code);
        $query->orWhere('sb_customers.customer_name', 'LIKE', '%'.$cust_code.'%');
   
        }] ,
    ])
    ->groupBy('sb_dsf_saf_detail_new.branch_code','sb_dsf_saf_detail_new.customer_code')
    ->orderBy('sb_dsf_saf_detail_new.dsf_code', 'asc')
    ->paginate(100);


    
   
    return view('customerfancing_lockunlock',['productdata'=>$productdata,'branchid'=>$branchid,'usercode'=>$usercode,'custcode'=>$cust_code]);
    }
    
    function DsfFancing_Order_Report()
    {
        
    session()->put("titlename","Dsf Wise Fancing Order Report - Sales Booster Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');$branchname=session('branchname');
    $usercode=session('usercode');
   // $branchid=explode(',', $branchid);
   $curdt=date('Y-m-d');
   $curdate=date('Y-m-d', strtotime('-1 day', strtotime($curdt)));

   $dsfdata=DB::SELECT("SELECT df.*,mst.branch_name FROM `sb_dsf_master` df,sb_branch_master mst where df.branch_code=mst.branch_code AND df.dsf_fancing=1 order by df.branch_code");


    
   
    return view('dsffancing_orderreport',['dsfdata'=>$dsfdata,'curdate'=>$curdate]);
    }
    
    function DsfFancing_Order_Report2(Request $req)
    {
        
    session()->put("titlename","Dsf Wise Fancing Order Report - Sales Booster Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');$branchname=session('branchname');
    $usercode=session('usercode');
   // $branchid=explode(',', $branchid);
   $curdt=date('Y-m-d');
   $curdate=$req->str_date;

   $dsfdata=DB::SELECT("SELECT df.*,mst.branch_name FROM `sb_dsf_master` df,sb_branch_master mst where df.branch_code=mst.branch_code AND df.dsf_fancing=1 order by df.branch_code");


    
   
    return view('dsffancing_orderreport',['dsfdata'=>$dsfdata,'curdate'=>$curdate]);
    }
    

    


}