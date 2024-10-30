<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;

class MustSellingManagementController extends Controller
{
    function AddMustSellingProduct()
    {
        
    session()->put("titlename","Add Must Selling Product - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
    $admintype=session('admintype');
    $usercode=session('usercode');

    $brcount=count($branchid);
    if($brcount > 1)
    {
        $branchdata = DB::table('sb_branch_master')
        ->whereIn('branch_code',$branchid)
        ->get();

        return view('addmustselling_product_branchselect',['branchdata'=>$branchdata]);
    }
    else{
   
    
    if($admintype == "DsfSupervisor")
    {
        $productdata = DB::table('sb_products')
    ->join('sb_businessline_product', 'sb_products.product_code', '=', 'sb_businessline_product.product_code')
    ->join('sb_supervisor_businessgroup', 'sb_businessline_product.supply_groupid', '=', 'sb_supervisor_businessgroup.group_id')
    ->where([
        ['sb_businessline_product.branch_code', '=', $branchid2],
        ['sb_supervisor_businessgroup.branch_id', '=', $branchid2],
        ['sb_supervisor_businessgroup.supervisor_code', '=', $usercode],
    ])
    ->groupBy('sb_businessline_product.product_code')
    ->orderBy('sb_products.company_code', 'asc')
    ->paginate(100);

        $companymaster=DB::SELECT("SELECT bline.company_code,comp.company_title FROM `sb_supervisor_businessgroup` sb,sb_business_line bline,sb_company_master comp where sb.group_id=bline.supply_groupid AND bline.branch_code='$branchid2' AND bline.company_code=comp.company_code AND sb.supervisor_code='$usercode' group by bline.company_code");
    }
    else{

    $productdata = DB::table('sb_products')
    ->join('sb_businessline_product', 'sb_products.product_code', '=', 'sb_businessline_product.product_code')
    ->where([
        ['sb_businessline_product.branch_code', '=', $branchid2],
    ])
    ->groupBy('sb_businessline_product.product_code')
    ->orderBy('sb_products.company_code', 'asc')
    ->paginate(100);

    $companymaster=DB::table('sb_company_master')
    ->get();
    
    }

    

    

    return view('addmustselling_product',['productdata'=>$productdata,'companymaster'=>$companymaster,'c_code'=>'','c_name'=>'Select Company','pro_code'=>'','brid'=>$branchid2]);
   
   }    


}


function AddMustSellingProduct2(Request $req)
    {
        
    session()->put("titlename","Add Must Selling Product - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=$req->pro_branch;
   
    
    $productdata = DB::table('sb_products')
    ->join('sb_businessline_product', 'sb_products.product_code', '=', 'sb_businessline_product.product_code')
    ->where([
        ['sb_businessline_product.branch_code', '=', $branchid],
    ])
    ->groupBy('sb_businessline_product.product_code')
    ->orderBy('sb_products.company_code', 'asc')
    ->paginate(100);

    $companymaster=DB::table('sb_company_master')
    ->get();
    
    $productdata->appends(['pro_branch' => $branchid]);
    

    return view('addmustselling_product',['productdata'=>$productdata,'companymaster'=>$companymaster,'c_code'=>'','c_name'=>'Select Company','pro_code'=>'','brid'=>$branchid]);
   
      


}


function AddMustSellingProduct3(Request $req)
    {
        
    session()->put("titlename","Add Must Selling Product - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=$req->brid;
    $pro_code=$req->pro_code;
    $pro_company=$req->pro_company;

    $admintype=session('admintype');
    $usercode=session('usercode');
   
    
    
    $productdata = DB::table('sb_products')
    ->join('sb_businessline_product', 'sb_products.product_code', '=', 'sb_businessline_product.product_code')
    ->join('sb_supervisor_businessgroup', 'sb_businessline_product.supply_groupid', '=', 'sb_supervisor_businessgroup.group_id')
    ->where([
        ['sb_businessline_product.branch_code', '=', $branchid],
        ['sb_supervisor_businessgroup.branch_id', '=', $branchid],
        ['sb_supervisor_businessgroup.supervisor_code', '=', $usercode],
    ]);
    if($pro_code !="")
    {
        
         $productdata=$productdata->where([
            [function($query) use ($pro_code){
                $query->orWhere('sb_businessline_product.product_code', '=', $pro_code);
                $query->orWhere('sb_products.product_name', 'LIKE', '%'.$pro_code.'%');
                }]
         ]);
    }
    if($pro_company !="")
    {
         $productdata=$productdata->where('sb_products.company_code', $pro_company);
    }

    $productdata=$productdata->groupBy('sb_businessline_product.product_code');
    $productdata=$productdata->orderBy('sb_products.company_code', 'asc');
    $productdata=$productdata->paginate(100);

    
    if($admintype == "DsfSupervisor")
    {
        $companymaster=DB::SELECT("SELECT bline.company_code,comp.company_title FROM `sb_supervisor_businessgroup` sb,sb_business_line bline,sb_company_master comp where sb.group_id=bline.supply_groupid AND bline.branch_code='$branchid' AND bline.company_code=comp.company_code AND sb.supervisor_code='$usercode' group by bline.company_code");
    }
    else{
        $companymaster=DB::table('sb_company_master')
        ->get();
    }
    

    $productdata->appends(['brid' => $branchid,'pro_code'=>$pro_code,'pro_company'=>$pro_company]);

    $compname="Select Company";
    if($pro_company !="")
    {
        $companyseach=DB::table('sb_company_master')
        ->where([
            ['sb_company_master.company_code', '=', $pro_company],
        ])
        ->get();

        foreach($companyseach as $search)
        {
            $compname=$search->company_title;
        }
    }

    

    return view('addmustselling_product',['productdata'=>$productdata,'companymaster'=>$companymaster,'c_code'=>$pro_company,'c_name'=>$compname,'pro_code'=>$pro_code,'brid'=>$branchid]);
   
      


}

function AddProductMsl(Request $req)
    {
        $brcode=$req->brcode;
        $prodcode=$req->prodcode;
        $mst_qty=$req->mst_qty;
       
        $myuserid=session("adminname");
        date_default_timezone_set("Asia/Karachi");
        $currentdate=date('Y-m-d h:i:s');

 
        DB::table('sb_must_selling_product')
           ->insert([
            'product_code' => $prodcode,
            'branch_code' => $brcode,
            'is_active' => 1,
            'msl_qty' => $mst_qty,
            'post_user' => $myuserid,
            'post_datetime'=>$currentdate
          
            
           ]);
        return response()->json(true);
    }


    function MustSellingList()
    {
        
    session()->put("titlename","Must Selling Product List - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
   
    
    $productdata = DB::table('sb_must_selling_product')
    ->join('sb_products', 'sb_must_selling_product.product_code', '=', 'sb_products.product_code')
    ->join('sb_branch_master', 'sb_must_selling_product.branch_code', '=', 'sb_branch_master.branch_code')
    ->whereIn('sb_must_selling_product.branch_code',$branchid)
    ->where([
        ['sb_must_selling_product.is_active', '=', 1],
    ])
    ->orderBy('sb_products.company_code', 'asc')
    ->paginate(100);

    $companymaster=DB::table('sb_company_master')
    ->get();
    $branchdata = DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->get();

    

    return view('mustselling_list',['productdata'=>$productdata,'companymaster'=>$companymaster,'branchdata'=>$branchdata,'c_code'=>'','c_name'=>'Select Company','b_code'=>$branchid2,'b_name'=>'Select Branch','pro_code'=>'']);
   
      


}


function MustSellingList2(Request $req)
    {
        
    session()->put("titlename","Must Selling Product List - Sales Booster Admin Panel - Premier Group Of Companies");
    $pro_branch=$req->pro_branch;
    $pro_code=$req->pro_code;
    $pro_company=$req->pro_company;

    $branchid=explode(',', $pro_branch);
    $branchid2=session('branchid');
    $branchid2=explode(',', $branchid2);
   
    $productdata = DB::table('sb_must_selling_product')
    ->join('sb_products', 'sb_must_selling_product.product_code', '=', 'sb_products.product_code')
    ->join('sb_branch_master', 'sb_must_selling_product.branch_code', '=', 'sb_branch_master.branch_code')
    // ->whereIn('sb_must_selling_product.branch_code',$branchid)
    ->where([
        ['sb_must_selling_product.is_active', '=', 1],
    ]);
    if($pro_code !="")
    {
        
         $productdata=$productdata->where([
            [function($query) use ($pro_code){
                $query->orWhere('sb_must_selling_product.product_code', '=', $pro_code);
                $query->orWhere('sb_products.product_name', 'LIKE', '%'.$pro_code.'%');
                }]
         ]);
    }
    if($pro_company !="")
    {
         $productdata=$productdata->where('sb_products.company_code', $pro_company);
    }
    if($pro_branch !="")
    {
         $productdata=$productdata->whereIn('sb_must_selling_product.branch_code', $branchid);
    }

    $productdata=$productdata->orderBy('sb_products.company_code', 'asc');
    $productdata=$productdata->paginate(100);

    $companymaster=DB::table('sb_company_master')
    ->get();
    $branchdata = DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid2)
    ->get();

    $productdata->appends(['pro_branch' => $pro_branch,'pro_code'=>$pro_code,'pro_company'=>$pro_company]);

    $compname="Select Company";
    if($pro_company !="")
    {
        $companyseach=DB::table('sb_company_master')
        ->where([
            ['sb_company_master.company_code', '=', $pro_company],
        ])
        ->get();

        foreach($companyseach as $search)
        {
            $compname=$search->company_title;
        }
    }

    $brcname="Select Branch";
    $brcount=count($branchid);
    if($brcount == 1)
    {
        $branchseach=DB::table('sb_branch_master')
        ->where([
            ['sb_branch_master.branch_code', '=', $pro_branch],
        ])
        ->get();

        foreach($branchseach as $search)
        {
            $brcname=$search->branch_name;
        }
    }
    

    return view('mustselling_list',['productdata'=>$productdata,'companymaster'=>$companymaster,'branchdata'=>$branchdata,'c_code'=>$pro_company,'c_name'=>$compname,'b_code'=>$pro_branch,'b_name'=>$brcname,'pro_code'=>$pro_code]);
   


    

}


function DeleteMslList($sid)
    {
       
        $myuserid=session("adminname");
        date_default_timezone_set("Asia/Karachi");
        $currentdate=date('Y-m-d h:i:s');

 
        DB::table('sb_must_selling_product')
        ->where([
            ['sb_must_selling_product.selling_id', '=', $sid],
        ])
           ->update([
            'is_active' => 0,
            'update_datetime' => $currentdate,
            
           ]);

           DB::table('sb_must_selling_log')
           ->insert([
            'selling_id' => $sid,
            'action_name' => 'Delete',
            'post_id' => $myuserid,
            'post_datetime' => $currentdate,
            
           ]);

        return redirect('/mustsellinglist');
    }

    
function UpdateProductMsl(Request $req)
{
    $mslid=$req->mslid;
    $mst_qty=$req->mst_qty;
    $old_qty=$req->old_qty;
    
    $myuserid=session("adminname");
    date_default_timezone_set("Asia/Karachi");
    $currentdate=date('Y-m-d h:i:s');
    
    DB::table('sb_must_selling_product')
        ->where([
            ['sb_must_selling_product.selling_id', '=', $mslid],
        ])
        ->update([
            'msl_qty' => $mst_qty,
            'update_datetime' => $currentdate,
            
        ]);

        DB::table('sb_must_selling_log')
        ->insert([
         'selling_id' => $mslid,
         'action_name' => 'Update',
         'post_id' => $myuserid,
         'post_datetime' => $currentdate,
         'old_qty' => $old_qty,
         'new_qty'=>$mst_qty
       
         
        ]);

    return response()->json(true);
}



}