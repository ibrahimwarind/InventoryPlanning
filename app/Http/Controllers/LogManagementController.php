<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogManagementController extends Controller
{
    //\
    function BmsApiLog()
    {
    session()->put("titlename","BMS Api Log - Sales Booster Admin Panel - Premier Group Of Companies");
    $apinamedata=DB::SELECT("SELECT api_name FROM `sb_dsf_saf_detail_log` group by api_name");
    
    $apilogdata=DB::table('sb_dsf_saf_detail_log')
    ->orderBy('posted_date', 'desc')
    ->paginate(100);

   $apilogdatacount=DB::table('sb_dsf_saf_detail_log')
    ->count();

    return view('bms_api_log',['apinamedata'=>$apinamedata,'apilogdata'=>$apilogdata,'pro_name'=>'','apilogdatacount'=>$apilogdatacount]);


    }

    function BmsApiLog2(Request $req)
    {
    	session()->put("titlename","BMS Api Log - Sales Booster Admin Panel - Premier Group Of Companies");
    	$pro_name=$req->pro_name;

    $apinamedata=DB::SELECT("SELECT api_name FROM `sb_dsf_saf_detail_log` group by api_name");
    
    $apilogdata=DB::table('sb_dsf_saf_detail_log')
    ->where([
    ['api_name', '=', $pro_name],
    ])
    ->orderBy('posted_date', 'desc')
    ->paginate(100);

   $apilogdatacount=DB::table('sb_dsf_saf_detail_log')
   ->where([
    ['api_name', '=', $pro_name],
    ])
    ->count();

    return view('bms_api_log',['apinamedata'=>$apinamedata,'apilogdata'=>$apilogdata,'pro_name'=>$pro_name,'apilogdatacount'=>$apilogdatacount]);


    }


    function MobileAppApiLog()
    {
    	session()->put("titlename","BMS Mobile App Api Log - Sales Booster Admin Panel - Premier Group Of Companies");
    
    $branchmaster=DB::table('sb_branch_master')
    ->get();

    $apilogdata=DB::table('sb_sync_down_log')
    ->orderBy('log_datetime', 'desc')
    ->paginate(100);

   $apilogdatacount=DB::table('sb_sync_down_log')
    ->count();

    return view('mobileapp_api_log',['branchmaster'=>$branchmaster,'apilogdata'=>$apilogdata,'pro_branch'=>'','pro_dsf'=>'','pro_date'=>'','apilogdatacount'=>$apilogdatacount]);


    }

    function MobileAppApiLog2(Request $req)
    {
    	session()->put("titlename","BMS Mobile App Api Log - Sales Booster Admin Panel - Premier Group Of Companies");
    $pro_branch=$req->pro_branch;
    $pro_dsf=$req->pro_dsf;

    $branchmaster=DB::table('sb_branch_master')
    ->get();


    if($pro_branch !="" && $pro_dsf !="")
    {

    $apilogdata=DB::table('sb_sync_down_log')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['dsf_code', '=', $pro_dsf],
    ])
    ->orderBy('log_datetime', 'desc')
    ->paginate(100);

   $apilogdatacount=DB::table('sb_sync_down_log')
   ->where([
    ['branch_code', '=', $pro_branch],
    ['dsf_code', '=', $pro_dsf],
    ])
    ->count();

    }
    else if($pro_branch !="" && $pro_dsf =="")
    {
     $apilogdata=DB::table('sb_sync_down_log')
    ->where([
    ['branch_code', '=', $pro_branch],
    ])
    ->orderBy('log_datetime', 'desc')
    ->paginate(100);

   $apilogdatacount=DB::table('sb_sync_down_log')
   ->where([
    ['branch_code', '=', $pro_branch],
    
    ])
    ->count();
    }
   


    //get branch name
    $pro_branch_name="";
    $branchdetail=DB::SELECT("SELECT branch_name FROM `sb_branch_master` where branch_code='$pro_branch'");
    foreach($branchdetail as $brfm)
    {
        $pro_branch_name=$brfm->branch_name;
    }


    //get dsf name
    $pro_dsf_name="";
    $dsfdetail=DB::SELECT("SELECT dsf_name FROM `sb_dsf_master` where dsf_code='$pro_dsf' AND branch_code='$pro_branch'");
    foreach($dsfdetail as $dsfm)
    {
        $pro_dsf_name=$dsfm->dsf_name;
    }

    return view('mobileapp_api_log',['branchmaster'=>$branchmaster,'apilogdata'=>$apilogdata,'pro_branch'=>$pro_branch,'pro_dsf'=>$pro_dsf,'pro_date'=>'','pro_branch_name'=>$pro_branch_name,'pro_dsf_name'=>$pro_dsf_name,'apilogdatacount'=>$apilogdatacount]);


    }

    
}
