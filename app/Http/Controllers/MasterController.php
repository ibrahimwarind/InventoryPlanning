<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{
    // 

    function FormulaManagement()
    {
        
    session()->put("titlename","Formula Management - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $formuladata=DB::table('ipl_formula_master')
    ->get();
 


return view('add_formuladetail',['formuladata'=>$formuladata,'data'=>'']);
    } 

    function FormulaManagementList()
    {
        
    session()->put("titlename","Formula Management List - Inventory Planning Admin Panel - Premier Group Of Companies");

    
    $formulalist=DB::SELECT("SELECT mst.formula_id,mst.formula_name,det.formula_description,det.multiply_by,det.is_default,det.detail_id FROM `ipl_formula_master` mst inner join ipl_formula_detail det on mst.formula_id=det.formula_id order by mst.formula_id asc");



   

    return view('formula_managementlist',['formulalist'=>$formulalist]);
    }

    function EditFormulaManagement($id)
    {
        
    session()->put("titlename","Upate Formula Management - Inventory Planning Admin Panel - Premier Group Of Companies");

    
    $formulalist=DB::SELECT("SELECT * FROM `ipl_formula_detail` where detail_id='$id'");


    return view('edit_formula_managementlist',['formulalist'=>$formulalist]);
    }

    function EditFormulaDetail(Request $req)
    {

        $detail_id=$req->input("detail_id");
        $is_default=$req->input("is_default");
        $formula_id=$req->input("formula_id");

        if($is_default == "1")
        {
          
        DB::table('ipl_formula_detail')
        ->where([
        ['formula_id', '=', $formula_id],
        
        ])
        ->update([
            'is_default' => 0
        ]);

        }

        DB::table('ipl_formula_detail')
        ->where([
        ['detail_id', '=', $detail_id],
        ])
        ->update([
            'is_default' => $is_default
        ]);
         
        

        return redirect('/formulamanagementlist');


    }

    // function GetFormulaDescription(Request $req)
    // {

    //      $formula_id=$req->formula_id;
    //      $formulaname="";
    //      $user=DB::select("SELECT formula_description FROM `ipl_formula_master` where formula_id='$formula_id'");
    //      foreach($user as $us)
    //      {
    //         $formulaname=$us->formula_description;
    //      }

    //    return response()->json($formulaname);

        
    // }

     
    

    

    

    function GetFormulaDescription(Request $req)
    {

         $formula_id=$req->formula_id;
         $formulaname="";
         $user=DB::select("SELECT formula_description FROM `ipl_formula_master` where formula_id='$formula_id'");
         foreach($user as $us)
         {
            $formulaname=$us->formula_description;
         }

       return response()->json($formulaname);

        
    }

    function AddFormulaDetail(Request $req)
    {

        $formula_id=$req->input("formula_id");
        $formula_detail=$req->input("formula_detail");
        $multiply_by=$req->input("multiply_by");
        $is_default=$req->input("is_default");
        $formula_detail=str_replace("$$",$multiply_by,$formula_detail);

        $adminid=session('adminid');
        date_default_timezone_set("Asia/Karachi");
        $date = date('Y-m-d H:i:s');
       

        $count=DB::table('ipl_formula_detail')
        ->where([
        ['formula_id','=',$formula_id],
        ['multiply_by','=',$multiply_by],
        ])
        ->count();
    
        // return view('index',['data'=>$count]);
        if($count == 0)
        {
         
         if($is_default == "1")
         {
        
        DB::table('ipl_formula_detail')
        ->where([
        ['formula_id', '=', $formula_id],
        
        ])
        ->update([
            'is_default' => 0
        ]);
        
         }



        DB::table('ipl_formula_detail')
        ->insert([
            'formula_id' => $formula_id,
            'multiply_by' => $multiply_by,
            'formula_description'=>$formula_detail,
            'post_datetime'=>$date,
            'post_by'=>$adminid,
            'is_default'=>$is_default,
        ]);
         
          $formuladata=DB::table('ipl_formula_master')
        ->get();

            return view('add_formuladetail',['formuladata'=>$formuladata,'data'=>'Formula Successfully Added']);
        }
        else
        {

        $formuladata=DB::table('ipl_formula_master')
        ->get();

        return view('add_formuladetail',['formuladata'=>$formuladata,'data'=>'Formula Already Exist']);

        }


        //session set krny ke lye
    

    }
    

    function CreateGroupMapping()
    {
    

    session()->put("titlename","Group Management - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $formuladata=DB::table('ipl_formula_master')
    ->get();

    $companydata=DB::table('ipl_company_master')
    ->orderBy('company_title', 'asc')
    ->get();
 

    return view('create_group_mapping',['formuladata'=>$formuladata,'data'=>'','companydata'=>$companydata]);
    }

    function GetFormulaDetail(Request $req)
    {

       $formula_id=$req->formula_id;
        
       $user=DB::select("SELECT detail_id,formula_description FROM `ipl_formula_detail` where formula_id='$formula_id' order by is_default desc");
         
       return response()->json($user);
 
    }

    function GetProductDetail(Request $req)
    {

         $company_id=$req->company_id;
         $permanent_temporary=$req->permanent_temporary;
        
        if($permanent_temporary !="")
        {

         $companydata=DB::select("SELECT p.product_code, p.product_name, p.packing_size, p.status, CASE WHEN gm.product_code IS NOT NULL AND gm.permanent_temporary = $permanent_temporary THEN 1 ELSE 0 END AS is_mapped, CASE WHEN gm.product_code IS NOT NULL AND gm.permanent_temporary = $permanent_temporary THEN gm.group_id ELSE 0 END AS group_id, CASE WHEN gm.product_code IS NOT NULL AND gm.permanent_temporary = $permanent_temporary THEN gmaster.group_name ELSE NULL END AS group_name FROM ipl_products p LEFT JOIN ipl_group_mapping gm ON p.product_code = gm.product_code LEFT JOIN ipl_group_master gmaster ON gm.group_id = gmaster.group_id WHERE p.company_code = '$company_id' AND p.status = 1 GROUP BY p.product_code ORDER BY p.product_name ASC");
        }
        else{
            $companydata=DB::select("SELECT p.product_code, p.product_name, p.packing_size, p.status, CASE WHEN gm.product_code IS NOT NULL THEN 1 ELSE 0 END AS is_mapped, CASE WHEN gm.product_code IS NOT NULL  THEN gm.group_id ELSE 0 END AS group_id, CASE WHEN gm.product_code IS NOT NULL  THEN gmaster.group_name ELSE NULL END AS group_name FROM ipl_products p LEFT JOIN ipl_group_mapping gm ON p.product_code = gm.product_code LEFT JOIN ipl_group_master gmaster ON gm.group_id = gmaster.group_id WHERE p.company_code = '$company_id' AND p.status = 1 GROUP BY p.product_code ORDER BY p.product_name ASC");
        } 

       return response()->json($companydata);

        
    }
    
    function ProductStatusChange(Request $req)
    {
        DB::table('ipl_products')
        ->where([
        ['product_code', '=', $req->id],
        
        ])
        ->update([
            'status' => $req->status
        ]);
        
        return response()->json(['success' => true]);
    }


    function CreateGroupMapping2(Request $req)
    {

        $formula_id=$req->input("formula_id");
        $formula_det_id=$req->input("formula_det_id");
        $group_name=$req->input("group_name");
        $company_id=$req->input("company_id");
        $permanent_temporary=$req->input("permanent_temporary");
        $checkedEntries = $req->input('entry', []);



        $adminid=session('adminid');
        date_default_timezone_set("Asia/Karachi");
        $date = date('Y-m-d H:i:s');
       

        $count=DB::table('ipl_group_master')
        ->where([
        ['group_name','=',$group_name],
        ])
        ->count();
    
        // return view('index',['data'=>$count]);
        if($count == 0)
        {
        

        DB::table('ipl_group_master')
        ->insert([
            'formula_id' => $formula_id,
            'formula_detail_id' => $formula_det_id,
            'group_name'=>$group_name,
            'company_id'=>$company_id,
            'post_user'=>$adminid,
            'post_datetime'=>$date,
            'permanent_temporary'=>$permanent_temporary
        ]);
        
        $groupid=0;
        $getgroupid=DB::SELECT("SELECT group_id FROM `ipl_group_master` order by group_id desc limit 1");
        foreach($getgroupid as $grp)
        {
         $groupid=$grp->group_id;
        }

        // Check if any checkboxes were selected
        if (!empty($checkedEntries)) {
            // Loop through each selected entry and process/store it
            foreach ($checkedEntries as $productCode) {
                DB::table('ipl_group_mapping')
            ->insert([
            'group_id' => $groupid,
            'product_code' => $productCode,
            'permanent_temporary'=>$permanent_temporary
            ]);

            }
        }


         
    $formuladata=DB::table('ipl_formula_master')
    ->get();

    $companydata=DB::table('ipl_company_master')
    ->orderBy('company_title', 'asc')
    ->get();
 

    return view('create_group_mapping',['formuladata'=>$formuladata,'data'=>'Group Successfully Created','companydata'=>$companydata]);
        }
        else
        {

        $formuladata=DB::table('ipl_formula_master')
    ->get();

    $companydata=DB::table('ipl_company_master')
    ->orderBy('company_title', 'asc')
    ->get();
 

    return view('create_group_mapping',['formuladata'=>$formuladata,'data'=>'Group Name Already Exist','companydata'=>$companydata]);

        }


        //session set krny ke lye
    

    }

    function GroupMappingList()
    {
        
    session()->put("titlename","Group Mapping List - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $groupdata=DB::SELECT('SELECT group_id,formula_id,formula_detail_id,group_name,company_id,permanent_temporary FROM `ipl_group_master` order by group_id desc');

    $companymaster=DB::SELECT("SELECT * FROM `ipl_company_master` order by company_title asc");


   

    return view('group_mapping_list',['companymaster'=>$companymaster,'data'=>'','groupdata'=>$groupdata,'companymaster'=>$companymaster]);
    }

     function GroupMappingList2(Request $req)
    {
        
    $pro_company=$req->pro_company; 
    $permanent_temporary=$req->permanent_temporary;

    session()->put("titlename","Group Mapping List - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);
   
   if($permanent_temporary == "")
   {
$groupdata=DB::SELECT("SELECT group_id,formula_id,formula_detail_id,group_name,company_id,permanent_temporary FROM `ipl_group_master` where company_id='$pro_company' order by group_id desc");
   }
   else{
    $groupdata=DB::SELECT("SELECT group_id,formula_id,formula_detail_id,group_name,company_id,permanent_temporary FROM `ipl_group_master` where company_id='$pro_company' AND permanent_temporary='$permanent_temporary' order by group_id desc");
   }
    

    $companymaster=DB::SELECT("SELECT * FROM `ipl_company_master` order by company_title asc");


   

    return view('group_mapping_list',['companymaster'=>$companymaster,'data'=>'','groupdata'=>$groupdata,'companymaster'=>$companymaster]);
    }
   
   function AddProductInGroup(Request $req)
    {
        
    session()->put("titlename","View Group Mapping  - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $groupid=$req->groupid;
    $prod_code=$req->prod_code;
    $per_temp=$req->per_temp;

    DB::table('ipl_group_mapping')
        ->insert([
            'group_id' => $groupid,
            'product_code' => $prod_code,
            'permanent_temporary'=>$per_temp
        ]);


    $groupdata=DB::SELECT("SELECT * FROM `ipl_group_master` where group_id='$groupid'");
    

    $productdata=DB::SELECT("SELECT prod.product_code,prod.product_name FROM `ipl_products` prod inner join ipl_group_master grp on prod.company_code = grp.company_id where grp.group_id='$groupid' AND prod.status=1 AND prod.product_code not in (SELECT product_code FROM `ipl_group_mapping`)");



    return view('view_group_mapping',['groupdata'=>$groupdata,'data'=>'','groupid'=>$groupid,'productdata'=>$productdata]);
    }
     

     function ViewGroupMapping($groupid,$type)
    {
        
    session()->put("titlename","View Group Mapping  - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $groupdata=DB::SELECT("SELECT * FROM `ipl_group_master` where group_id='$groupid'");
    

    $productdata=DB::SELECT("SELECT prod.product_code,prod.product_name FROM `ipl_products` prod inner join ipl_group_master grp on prod.company_code = grp.company_id where grp.group_id='$groupid' AND prod.status=1 AND prod.product_code not in (SELECT product_code FROM `ipl_group_mapping` where permanent_temporary=$type)");



    return view('view_group_mapping',['groupdata'=>$groupdata,'data'=>'','groupid'=>$groupid,'productdata'=>$productdata]);
    }

    function RemoveGroupProduct(Request $req)
    {

    $mappingid=$req->mappingid;
    DB::table('ipl_group_mapping')
   ->where([
    ['mapping_id', '=', $mappingid],
    ])
   ->delete();

         

       return response()->json(['success' => true]);

        
    }

    

    

    function ProductPackingSize()
    {
        
    session()->put("titlename","Product Packing Size - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $companydata=DB::table('ipl_company_master')
    ->get();

   

    return view('product_packing_size',['companydata'=>$companydata,'data'=>'']);
    }


    function ProductPackingSize2(Request $req)
    {
        $con=mysqli_connect("localhost","root","","db_ipl");

        $company_id=$req->input("company_id");

        $productcount=0;
        $productdata=DB::select("SELECT count(product_id) as totalproduct FROM ipl_products where company_code='$company_id'");
        foreach($productdata as $key) {
            $productcount=$key->totalproduct;
        }
        $updates = [];
        for($i=0;$i <= $productcount;$i++)
        {
            $productcode=$req->input("productcode{$i}");
            $packingqty=$req->input("packingqty{$i}"); 

            $updates[] = ['product_code' => $productcode, 'packing_size' => $packingqty];

            // $update_packing="update `ipl_products` set packing_size='$packingqty' where product_code='$productcode'";
            // $run_updatepacking=mysqli_query($con,$update_packing);

        }
     foreach ($updates as $update) {
       DB::table('ipl_products')
        ->where('product_code', $update['product_code'])
        ->update(['packing_size' => $update['packing_size']]);
     }

    $companydata=DB::table('ipl_company_master')
    ->get();

   

    return redirect('productpackingsize2');


    }

    function NotSetPackingSizeProduct()
    {
        
    session()->put("titlename","Product Packing Size - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $companydata=DB::table('ipl_company_master')
    ->get();

    $productdata=DB::table('ipl_products')
    ->where([
    ['packing_size', '=', 0],
    ])
    ->orderBy('company_code', 'asc')
    ->orderBy('product_name', 'asc')
    ->paginate(50);


    return view('notset_product_packing_size',['companydata'=>$companydata,'data'=>'','productdata'=>$productdata]);
    }

    function NotSetPackingSizeProduct2(Request $req)
    {
        
    session()->put("titlename","Product Packing Size - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);
    $pro_company=$req->pro_company;

    $companydata=DB::table('ipl_company_master')
    ->get();

    $productdata=DB::table('ipl_products')
    ->where([
    ['packing_size', '=', 0],
    ['company_code', '=', $pro_company],
    ])
    ->orderBy('company_code', 'asc')
    ->orderBy('product_name', 'asc')
    ->paginate(50);


    return view('notset_product_packing_size',['companydata'=>$companydata,'data'=>'','productdata'=>$productdata]);
    }


    function ProductActiveInActive()
    {
        
    session()->put("titlename","Product Active InActive Management - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $companydata=DB::table('ipl_company_master')
    ->get();

    

    return view('product_active_inactive',['companydata'=>$companydata,'data'=>'']);
    }

    
    
    
    function CheckListQuestion()
    {
        
    session()->put("titlename","CheckList Questions - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    

    return view('checklist_question',['data'=>'']);
    }


    function CheckListQuestion2(Request $req)
    {
        
        $checklist_title=$req->input("checklist_title");
        $every_time=$req->input("every_time");

        $adminid=session('adminid');
        date_default_timezone_set("Asia/Karachi");
        $date = date('Y-m-d H:i:s');
       

        $count=DB::table('ipl_checklist_question')
        ->where([
        ['question_title','=',$checklist_title],

        ])
        ->count();
    
        // return view('index',['data'=>$count]);
        if($count == 0)
        {
        

        DB::table('ipl_checklist_question')
        ->insert([
            'question_title' => $checklist_title,
            'every_time' => $every_time,
        ]);
         
     

            return view('checklist_question',['data'=>'Question Successfully Added']);
        }
        else
        {


        return view('checklist_question',['data'=>'Question Already Exist']);

        }


    
    }

    function CheckListQuestionList()
    {
        
    session()->put("titlename","CheckList Questions List - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $questionlist=DB::table("ipl_checklist_question")->get();
    

    return view('checklist_question_list',['data'=>'','questionlist'=>$questionlist]);
    }    

    function QuestionStatusChange(Request $req)
    {
        DB::table('ipl_checklist_question')
        ->where([
        ['question_id', '=', $req->id],
        
        ])
        ->update([
            'status' => $req->status
        ]);
        
        return response()->json(['success' => true]);
    }

    function QuestionVersionChange(Request $req)
    {
        DB::table('ipl_checklist_question')
        ->where([
        ['question_id', '=', $req->id],
        
        ])
        ->update([
            'every_time' => $req->status
        ]);
        
        return response()->json(['success' => true]);
    }
   

    
    

    


    function BranchList()
    {
        
    session()->put("titlename","Branch List - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);
    $totalbranch=DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->count();
 

    $branchdata = DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_region', 'asc')
    ->orderBy('branch_zone', 'asc')
    ->paginate(100);

$regiondata=DB::select("SELECT branch_region FROM `sb_branch_master` group by branch_region");

return view('branchlist',['totalbranch'=>$totalbranch,'branchdata'=>$branchdata,'regiondata'=>$regiondata,'pro_region'=>'','pro_zone'=>'','pro_city'=>'']);
    }

    function GetBranchRegionZone(Request $req)
    {

         $regionname=$req->regionname;
         // $dsfbrick=$req->dsf_brick;
         $user=DB::select("SELECT branch_zone FROM `sb_branch_master` where branch_region='$regionname' group by branch_zone");
       return response()->json($user);

        
    }

    function GetBranchZoneCity(Request $req)
    {

         $zonename=$req->zonename;
         $regionname=$req->regionname;
         // $dsfbrick=$req->dsf_brick;
         $user=DB::select("SELECT branch_city FROM `sb_branch_master` where branch_region='$regionname' AND branch_zone='$zonename' group by branch_city");
       return response()->json($user);

        
    }

    function BranchList2(Request $req)
    {
    
    $pro_region=$req->input("pro_region");
    $pro_zone=$req->input("pro_zone");
    $pro_city=$req->input("pro_city");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid); 
    session()->put("titlename","Branch List - Sales Booster Admin Panel - Premier Group Of Companies");


    if($pro_region !="" && $pro_zone !="" && $pro_city !="")
    {
    $totalbranch=DB::table('sb_branch_master')
    ->where([
    ['branch_region', '=', $pro_region],
    ['branch_zone', '=', $pro_zone],
    ['branch_city', '=', $pro_city],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();


    $branchdata = DB::table('sb_branch_master')
    ->where([
    ['branch_region', '=', $pro_region],
    ['branch_zone', '=', $pro_zone],
    ['branch_city', '=', $pro_city],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_region', 'asc')
    ->orderBy('branch_zone', 'asc')
    ->paginate(100);

    $branchdata->appends(['pro_region' => $pro_region,'pro_zone' => $pro_zone,'pro_city' => $pro_city]);

    }


    if($pro_region !="" && $pro_zone =="" && $pro_city =="")
    {
    $totalbranch=DB::table('sb_branch_master')
    ->where([
    ['branch_region', '=', $pro_region],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();


    $branchdata = DB::table('sb_branch_master')
    ->where([
    ['branch_region', '=', $pro_region]
    
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_region', 'asc')
    ->orderBy('branch_zone', 'asc')
    ->paginate(100);

    $branchdata->appends(['pro_region' => $pro_region]);

    }

    if($pro_region !="" && $pro_zone !="" && $pro_city =="")
    {
    $totalbranch=DB::table('sb_branch_master')
    ->where([
    ['branch_region', '=', $pro_region],
    ['branch_zone', '=', $pro_zone],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();


    $branchdata = DB::table('sb_branch_master')
    ->where([
    ['branch_region', '=', $pro_region],
    ['branch_zone', '=', $pro_zone],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_region', 'asc')
    ->orderBy('branch_zone', 'asc')
    ->paginate(100);

    $branchdata->appends(['pro_region' => $pro_region,'pro_zone' => $pro_zone]);

    }


$regiondata=DB::select("SELECT branch_region FROM `sb_branch_master` group by branch_region");



return view('branchlist',['totalbranch'=>$totalbranch,'branchdata'=>$branchdata,'regiondata'=>$regiondata,'pro_region'=>$pro_region,'pro_zone'=>$pro_zone,'pro_city'=>$pro_city]);
    }
    


    function CompanyList()
    {
        
    session()->put("titlename","Company List - Sales Booster Admin Panel - Premier Group Of Companies");
    $totalcompany=DB::table('sb_company_master')
    ->count();


    $companydata = DB::table('sb_company_master')
    ->orderBy('company_title', 'asc')
    ->paginate(250);

    return view('companylist',['totalcompany'=>$totalcompany,'companydata'=>$companydata]);
    }


    function BusinessLineList()
    {
        
    session()->put("titlename","Business Line List - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid2=session('branchid');
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $totalbusinessline=DB::table('sb_business_line')
    ->join('sb_branch_master', 'sb_business_line.branch_code', '=', 'sb_branch_master.branch_code')
    ->whereIn('sb_business_line.branch_code',$branchid)
    ->count();


    $businesslinedata = DB::table('sb_business_line')
    ->join('sb_branch_master', 'sb_business_line.branch_code', '=', 'sb_branch_master.branch_code')
    ->whereIn('sb_business_line.branch_code',$branchid)
    ->orderBy('sb_business_line.branch_code', 'asc')
    ->orderBy('sb_business_line.supply_groupid', 'asc')
    ->paginate(100);

    $branchmaster=DB::table('sb_branch_master')
    ->whereIn('branch_code', $branchid)
    ->get();

    //count ke lye
    $branchcount=0;
    $countbranch=DB::SELECT("SELECT count(DISTINCT bline.branch_code) as totalbranch FROM sb_business_line bline,sb_branch_master mst where bline.branch_code=mst.branch_code AND bline.branch_code in ($branchid2)");
    foreach($countbranch as $cbr)
    {
        $branchcount=$cbr->totalbranch;
    }
    $groupcount=0;
    $countgroup=DB::SELECT("SELECT count( DISTINCT bline.supply_groupid)as totalgroup FROM sb_business_line bline,sb_branch_master mst where bline.branch_code=mst.branch_code AND bline.branch_code in ($branchid2)");
    foreach($countgroup as $cbr)
    {
        $groupcount=$cbr->totalgroup;
    }

    

    return view('businesslinelist',['totalbusinessline'=>$totalbusinessline,'businesslinedata'=>$businesslinedata,'branchmaster'=>$branchmaster,'pro_branch'=>'','pro_group'=>'','branchcount'=>$branchcount,'groupcount'=>$groupcount]);
    }

   

    


    function BusinessLineList2(Request $req)
    {
      
   session()->put("titlename","Business Line List - Sales Booster Admin Panel - Premier Group Of Companies");
   $branchid2=session('branchid');
    $branchid=session('branchid');
    $branchid=explode(',', $branchid); 
   $pro_branch=$req->input("pro_branch");
    $pro_group=$req->input("pro_group");


    

    if($pro_branch !="" && $pro_group =="")
    {

     $businesslinedata = DB::table('sb_business_line')
    ->join('sb_branch_master', 'sb_business_line.branch_code', '=', 'sb_branch_master.branch_code')
    ->where([
    ['sb_business_line.branch_code', '=', $pro_branch],
    ])
    ->whereIn('sb_business_line.branch_code',$branchid)
    ->orderBy('sb_business_line.branch_code', 'asc')
    ->orderBy('sb_business_line.supply_groupid', 'asc')
    ->paginate(100);
    

    

    $totalbusinessline = DB::table('sb_business_line')
    ->join('sb_branch_master', 'sb_business_line.branch_code', '=', 'sb_branch_master.branch_code')
    ->where([
    ['sb_business_line.branch_code', '=', $pro_branch],
    ])
    ->whereIn('sb_business_line.branch_code',$branchid)
    ->count();
    
     $businesslinedata->appends(['pro_branch' => $pro_branch]);


     //count ke lye
    $branchcount=0;
    $countbranch=DB::SELECT("SELECT count(DISTINCT bline.branch_code) as totalbranch FROM sb_business_line bline,sb_branch_master mst where bline.branch_code=mst.branch_code AND bline.branch_code='$pro_branch'");
    foreach($countbranch as $cbr)
    {
        $branchcount=$cbr->totalbranch;
    }
    $groupcount=0;
    $countgroup=DB::SELECT("SELECT count( DISTINCT bline.supply_groupid)as totalgroup FROM sb_business_line bline,sb_branch_master mst where bline.branch_code=mst.branch_code  AND bline.branch_code='$pro_branch'");
    foreach($countgroup as $cbr)
    {
        $groupcount=$cbr->totalgroup;
    }


    
    }
    if($pro_branch =="" && $pro_group !="")
    {
    
    

    $businesslinedata = DB::table('sb_business_line')
    ->join('sb_branch_master', 'sb_business_line.branch_code', '=', 'sb_branch_master.branch_code')
    ->where([
    ['sb_business_line.supply_groupid', '=', $pro_group],
    ])
    ->whereIn('sb_business_line.branch_code',$branchid)
    ->orderBy('sb_business_line.branch_code', 'asc')
    ->orderBy('sb_business_line.supply_groupid', 'asc')
    ->paginate(100);

    $totalbusinessline = DB::table('sb_business_line')
    ->join('sb_branch_master', 'sb_business_line.branch_code', '=', 'sb_branch_master.branch_code')
    ->where([
    ['sb_business_line.supply_groupid', '=', $pro_group],
    ])
    ->whereIn('sb_business_line.branch_code',$branchid)
    ->count();
    
     $businesslinedata->appends(['pro_group'=>$pro_group]);

      //count ke lye
    $branchcount=0;
    $countbranch=DB::SELECT("SELECT count(DISTINCT bline.branch_code) as totalbranch FROM sb_business_line bline,sb_branch_master mst where bline.branch_code=mst.branch_code AND bline.supply_groupid='$pro_group' AND bline.branch_code in ($branchid2)");
    foreach($countbranch as $cbr)
    {
        $branchcount=$cbr->totalbranch;
    }
    $groupcount=0;
    $countgroup=DB::SELECT("SELECT count( DISTINCT bline.supply_groupid)as totalgroup FROM sb_business_line bline,sb_branch_master mst where bline.branch_code=mst.branch_code  AND bline.supply_groupid='$pro_group' AND bline.branch_code in ($branchid2)");
    foreach($countgroup as $cbr)
    {
        $groupcount=$cbr->totalgroup;
    }


    
    }
    
    if($pro_branch !="" && $pro_group !="")
    {
    $businesslinedata = DB::table('sb_business_line')
    ->join('sb_branch_master', 'sb_business_line.branch_code', '=', 'sb_branch_master.branch_code')
    ->where([
    ['sb_business_line.branch_code', '=', $pro_branch],
    ['sb_business_line.supply_groupid', '=', $pro_group],
    ])
    
    ->orderBy('sb_business_line.branch_code', 'asc')
    ->orderBy('sb_business_line.supply_groupid', 'asc')
    ->paginate(100);


    

    $totalbusinessline = DB::table('sb_business_line')
    ->join('sb_branch_master', 'sb_business_line.branch_code', '=', 'sb_branch_master.branch_code')
    ->where([
    ['sb_business_line.branch_code', '=', $pro_branch],
    ['sb_business_line.supply_groupid', '=', $pro_group],
    ])
    ->count();
    
     $businesslinedata->appends(['pro_branch' => $pro_branch,'pro_group'=>$pro_group]);
     
       //count ke lye
    $branchcount=0;
    $countbranch=DB::SELECT("SELECT count(DISTINCT bline.branch_code) as totalbranch FROM sb_business_line bline,sb_branch_master mst where bline.branch_code=mst.branch_code AND bline.supply_groupid='$pro_group' AND bline.branch_code='$pro_branch'");
    foreach($countbranch as $cbr)
    {
        $branchcount=$cbr->totalbranch;
    }
    $groupcount=0;
    $countgroup=DB::SELECT("SELECT count( DISTINCT bline.supply_groupid)as totalgroup FROM sb_business_line bline,sb_branch_master mst where bline.branch_code=mst.branch_code  AND bline.supply_groupid='$pro_group' AND bline.branch_code='$pro_branch'");
    foreach($countgroup as $cbr)
    {
        $groupcount=$cbr->totalgroup;
    }
    
    }
  
    


    $branchmaster=DB::table('sb_branch_master')
    ->whereIn('branch_code', $branchid)
    ->get();

    $bname=DB::SELECT("SELECT branch_name FROM `sb_branch_master` where branch_code='$pro_branch'");
    foreach($bname as $bb)
    {
        $branchname=$bb->branch_name;
    }
    $grpname="";
    if($pro_group !="")
    {
     $gname=DB::SELECT("SELECT supply_groupname FROM `sb_business_line` where supply_groupid='$pro_group' limit 1");
     foreach($gname as $bb)
    {
        $grpname=$bb->supply_groupname;
    }

    }

    
    

    return view('businesslinelist',['totalbusinessline'=>$totalbusinessline,'businesslinedata'=>$businesslinedata,'branchmaster'=>$branchmaster,'pro_branch'=>$pro_branch,'pro_group'=>$pro_group,'branchname'=>$branchname,'grpname'=>$grpname,'branchcount'=>$branchcount,'groupcount'=>$groupcount]);


    }

    function BusinessWiseProducts($branchcode,$groupid,$companycode,$branchname,$groupname,$companyname)
    {
        
    session()->put("titlename","Business Line Wise Product - Sales Booster Admin Panel - Premier Group Of Companies");

    $businessproductdata = DB::table('sb_businessline_product')
    ->join('sb_products', 'sb_businessline_product.product_code', '=', 'sb_products.product_code')
    ->where([
    ['sb_businessline_product.branch_code', '=', $branchcode],
    ['sb_businessline_product.supply_groupid', '=', $groupid],
    ['sb_products.company_code', '=', $companycode],
    ['sb_businessline_product.status', '=', 1],
    ])
    ->orderBy('sb_products.product_name', 'asc')
    ->paginate(100);

    $businessproductdatacount = DB::table('sb_businessline_product')
    ->join('sb_products', 'sb_businessline_product.product_code', '=', 'sb_products.product_code')
    ->where([
    ['sb_businessline_product.branch_code', '=', $branchcode],
    ['sb_businessline_product.supply_groupid', '=', $groupid],
    ['sb_products.company_code', '=', $companycode],
    ['sb_businessline_product.status', '=', 1],
    ])
    ->count();


       


    return view('businesswiseproductlist',['businessproductdata'=>$businessproductdata,'businessproductdatacount'=>$businessproductdatacount,'branchname'=>$branchname,'groupname'=>$groupname,'companyname'=>$companyname]);
    }

    function BusinessLineProductList()
    {
        
    session()->put("titlename","Business Line Product List - Sales Booster Admin Panel - Premier Group Of Companies");

    $totalbusinesslineproduct=DB::table('sb_businessline_product')
    ->count();


    $businesslinedataproduct = DB::table('sb_businessline_product')
    ->orderBy('id', 'asc')
    ->paginate(50);

    $branchmaster=DB::table('sb_branch_master')
    ->get();

    

    return view('businesslineproductlist',['totalbusinesslineproduct'=>$totalbusinesslineproduct,'businesslinedataproduct'=>$businesslinedataproduct,'branchmaster'=>$branchmaster]);
    }

    function BusinessLineProductList2(Request $req)
    {
      
   session()->put("titlename","Business Line Product List - Sales Booster Admin Panel - Premier Group Of Companies");
    $pro_branch=$req->input("pro_branch");
    $pro_group=$req->input("pro_group");


    

    if($pro_branch !="" && $pro_group =="")
    {
     $businesslinedataproduct = DB::table('sb_businessline_product')
    ->where([
    ['branch_code', '=', $pro_branch],
    ])
    ->paginate(50);

    $totalbusinesslineproduct = DB::table('sb_businessline_product')
    ->where([
    ['branch_code', '=', $pro_branch],
    ])
    ->count();
    
     $businesslinedataproduct->appends(['pro_branch' => $pro_branch]);

    
    }
    if($pro_branch =="" && $pro_group !="")
    {
    
    $businesslinedataproduct = DB::table('sb_businessline_product')
    ->where([
    ['supply_groupid', '=', $pro_group],
    ])
    ->paginate(50);

    $totalbusinesslineproduct = DB::table('sb_businessline_product')
    ->where([
    ['supply_groupid', '=', $pro_group],
    ])
    ->count();
    
     $businesslinedataproduct->appends(['pro_group'=>$pro_group]);

    
    }
    
    if($pro_branch !="" && $pro_group !="")
    {
     $businesslinedataproduct = DB::table('sb_businessline_product')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    ])
    ->paginate(50);

    $totalbusinesslineproduct = DB::table('sb_businessline_product')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    ])
    ->count();
    
     $businesslinedataproduct->appends(['pro_branch' => $pro_branch,'pro_group'=>$pro_group]);

    
    }
  
    


    $branchmaster=DB::table('sb_branch_master')
    ->get();

  
    

    return view('businesslineproductlist',['totalbusinesslineproduct'=>$totalbusinesslineproduct,'businesslinedataproduct'=>$businesslinedataproduct,'branchmaster'=>$branchmaster]);


    }

    

    
    function ProductList()
    {
        
    session()->put("titlename","Product List - Sales Booster Admin Panel - Premier Group Of Companies");

    $totalproduct=DB::table('sb_products')
    ->count();


    $productdata = DB::table('sb_products')
    ->orderBy('company_code', 'asc')
    ->paginate(100);

    $companymaster=DB::table('sb_company_master')
    ->get();

    

    return view('productlist',['totalproduct'=>$totalproduct,'productdata'=>$productdata,'companymaster'=>$companymaster,'c_code'=>'','c_name'=>'Select Company','pro_code'=>'']);
    }


    function ProductList2(Request $req)
    {
      
  session()->put("titlename","Product List - Sales Booster Admin Panel - Premier Group Of Companies");
    $pro_company=$req->input("pro_company");
    $pro_code=$req->input("pro_code");
   
    if($pro_code !="" && $pro_company !="")
    {
    $totalproduct=DB::table('sb_products')
    ->where([
    ['company_code', '=', $pro_company],
    ['product_code', 'LIKE', '%'.$pro_code.'%'],
    ])
    ->count();


    $productdata = DB::table('sb_products')
    ->where([
    ['company_code', '=', $pro_company],
    ['product_code', 'LIKE', '%'.$pro_code.'%'],
    ])
    ->orderBy('product_id', 'asc')
    ->paginate(100);

    $productdata->appends(['pro_company' => $pro_company,'pro_code' => $pro_code]); 

    }
    else if($pro_code !="" && $pro_company =="")
    {

    $totalproduct=DB::table('sb_products')
    ->where([
    ['product_code', 'LIKE', '%'.$pro_code.'%'],
    ])
    ->count();


    $productdata = DB::table('sb_products')
    ->where([
    ['product_code', 'LIKE', '%'.$pro_code.'%'],
    ])
    ->orderBy('product_id', 'asc')
    ->paginate(100);

    $productdata->appends(['pro_code' => $pro_code]); 

        
    }
    else if($pro_code =="" && $pro_company !="")
    {
    $totalproduct=DB::table('sb_products')
    ->where([
    ['company_code', '=', $pro_company],
    ])
    ->count();


    $productdata = DB::table('sb_products')
    ->where([
    ['company_code', '=', $pro_company],
    ])
    ->orderBy('product_id', 'asc')
    ->paginate(100);

    $productdata->appends(['pro_company' => $pro_company]); 
        
    }
    else if($pro_code =="" && $pro_company =="")
    {
    $totalproduct=DB::table('sb_products')
    
    ->count();


    $productdata = DB::table('sb_products')
    
    ->orderBy('product_id', 'asc')
    ->paginate(100);

    // $productdata->appends(['pro_company' => $pro_company]); 
        
    }
   

    $companymaster=DB::table('sb_company_master')
    ->get();
    $c_name="";
    $getdata=DB::SELECT("SELECT company_title FROM `sb_company_master` where company_code='$pro_company'");
    foreach($getdata as $dat)
    {
        $c_name=$dat->company_title;
    }
     
  
    

      return view('productlist',['totalproduct'=>$totalproduct,'productdata'=>$productdata,'companymaster'=>$companymaster,'c_code'=>$pro_company,'c_name'=>$c_name,'pro_code'=>$pro_code]);


    }

    
    function BranchWiseProduct($pcode,$pname)
    {
        
    session()->put("titlename","Branch Wise Product - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid2=session('branchid');
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);


    $totalbranch=DB::table('sb_businessline_product')
    ->where([
    ['product_code', '=', $pcode],
    ])
    ->whereIn('branch_code',$branchid)
    ->groupBy('branch_code')
    ->get();

    $totalactivebranch=DB::table('sb_businessline_product')
    ->where([
    ['product_code', '=', $pcode],
    ['status', '=', 1],
    ])
    ->whereIn('branch_code',$branchid)
    ->groupBy('branch_code')
    ->get();

    $totalinactivebranch=DB::table('sb_businessline_product')
    ->where([
    ['product_code', '=', $pcode],
    ['status', '=', 0],
    ])
    ->whereIn('branch_code',$branchid)
    ->groupBy('branch_code')
    ->get();

    $productbranchdata=DB::table('sb_businessline_product')
    ->where([
    ['product_code', '=', $pcode],
       ])
    ->whereIn('branch_code',$branchid)
   ->groupBy('branch_code')
    ->get();
    

    return view('branchwise_product',['totalbranch'=>$totalbranch,'totalactivebranch'=>$totalactivebranch,'totalinactivebranch'=>$totalinactivebranch,'productbranchdata'=>$productbranchdata,'pcode'=>$pcode,'pname'=>$pname]);
    }

     


    function CustomerList()
    {
        
    session()->put("titlename","Customer List - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid2=session('branchid');
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $totalcustomer=DB::table('sb_customers')
    ->whereIn('branch_code',$branchid)
    ->count();


    $customerdata = DB::table('sb_customers')
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->paginate(100);

    $branchmaster=DB::table('sb_branch_master')
    ->whereIn('branch_code', $branchid)
    ->get();

    

    return view('customerlist',['totalcustomer'=>$totalcustomer,'customerdata'=>$customerdata,'branchmaster'=>$branchmaster,'b_code'=>'','b_name'=>'Select Branch']);
    }

   function CustomerList2(Request $req)
    {

    $pro_branch=$req->input("pro_branch");
    $branchid2=session('branchid');
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);
   
    session()->put("titlename","Company List - Sales Booster Admin Panel - Premier Group Of Companies");

    $totalcustomer=DB::table('sb_customers')
    ->where([
    ['branch_code', '=', $pro_branch],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();


    $customerdata = DB::table('sb_customers')
    ->where([
    ['branch_code', '=', $pro_branch],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->paginate(100);

    $branchmaster=DB::table('sb_branch_master')
    ->whereIn('branch_code', $branchid)
    ->get();

    $b_name="";
    $getdata=DB::SELECT("SELECT branch_name FROM `sb_branch_master` where branch_code='$pro_branch'");
    foreach($getdata as $dat)
    {
        $b_name=$dat->branch_name;
    }
     
   $customerdata->appends(['pro_branch' => $pro_branch]); 
    

      return view('customerlist',['totalcustomer'=>$totalcustomer,'customerdata'=>$customerdata,'branchmaster'=>$branchmaster,'b_code'=>$pro_branch,'b_name'=>$b_name]);


    }


     
    function DsfList()
    {
        
    session()->put("titlename","DSF List - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid2=session('branchid');
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $totaldsf=DB::table('sb_dsf_master')
    ->whereIn('branch_code',$branchid)
    ->count();


    $dsfdata = DB::table('sb_dsf_master')
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->paginate(100);

    $branchmaster=DB::table('sb_branch_master')
    ->whereIn('branch_code', $branchid)
    ->get();

    

    return view('dsflist',['totaldsf'=>$totaldsf,'dsfdata'=>$dsfdata,'branchmaster'=>$branchmaster,'b_code'=>'','b_name'=>'Select Branch']);
    }

   function DsfList2(Request $req)
    {

    $pro_branch=$req->input("pro_branch");
    $pro_group=$req->input("pro_group");
    $branchid2=session('branchid');
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);
   
   
    session()->put("titlename","DSF List - Sales Booster Admin Panel - Premier Group Of Companies");
     
     if($pro_group== "")
     {
    $totaldsf=DB::table('sb_dsf_master')
    ->where([
    ['branch_code', '=', $pro_branch],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();


    $dsfdata = DB::table('sb_dsf_master')
    ->where([
    ['branch_code', '=', $pro_branch],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('dsf_id', 'asc')
    ->paginate(100);
     }
     else {
       
    $totaldsf=DB::table('sb_dsf_master')
    ->join('sb_dsf_businessline', 'sb_dsf_master.dsf_code', '=', 'sb_dsf_businessline.dsf_code')
    ->where([
    ['sb_dsf_master.branch_code', '=', $pro_branch],
    ['sb_dsf_businessline.supply_groupid', '=', $pro_group],
    ['sb_dsf_businessline.branch_code', '=', $pro_branch],
    ])
    ->whereIn('sb_dsf_master.branch_code',$branchid)
    ->count();


    $dsfdata = DB::table('sb_dsf_master')
    ->join('sb_dsf_businessline', 'sb_dsf_master.dsf_code', '=', 'sb_dsf_businessline.dsf_code')
    ->where([
    ['sb_dsf_master.branch_code', '=', $pro_branch],
    ['sb_dsf_businessline.supply_groupid', '=', $pro_group],
    ['sb_dsf_businessline.branch_code', '=', $pro_branch],
    ])
    ->whereIn('sb_dsf_master.branch_code',$branchid)
    ->orderBy('sb_dsf_master.branch_code', 'asc')
    ->paginate(100);
     
     }
    



    $branchmaster=DB::table('sb_branch_master')
    ->whereIn('branch_code', $branchid)
    ->get();

    $b_name="";
    $getdata=DB::SELECT("SELECT branch_name FROM `sb_branch_master` where branch_code='$pro_branch'");
    foreach($getdata as $dat)
    {
        $b_name=$dat->branch_name;
    }
     
    $dsfdata->appends(['pro_branch' => $pro_branch]); 
  
    

    return view('dsflist',['totaldsf'=>$totaldsf,'dsfdata'=>$dsfdata,'branchmaster'=>$branchmaster,'b_code'=>$pro_branch,'b_name'=>$b_name]);


    }
    function GetBranchGroup(Request $req)
    {

         $branchcode=$req->branchcode;
         $user=DB::select("SELECT supply_groupid,supply_groupname FROM `sb_business_line` where branch_code='$branchcode' group by supply_groupname");
       return response()->json($user);

        
    }

     function DsfStatusChange(Request $req)
    {
        DB::table('sb_dsf_master')
        ->where('dsf_id', $req->id)
        ->update([
            'dsfstatus' => $req->status
        ]);
        
        return response()->json(['success' => true]);
    }

    function DsfChangePassword($id,$name)
    {
        
    session()->put("titlename","DSF Change Password - Sales Booster Admin Panel - Premier Group Of Companies");

   
    

    $dsfdata = DB::table('sb_dsf_master')
    ->where('dsf_id', $id)
    ->get();

    return view('dsfchangepassword',['dsfdata'=>$dsfdata,'dsfid'=>$id,'dsfname'=>$name]);
    }

    function UpdateDsfPassword(Request $req)
    {
        
    session()->put("titlename","DSF Change Password - Sales Booster Admin Panel - Premier Group Of Companies");
    $did=$req->input("did");
    $u_password=$req->input("u_password");
   
    

    DB::table('sb_dsf_master')
        ->where('dsf_id', $did)
        ->update([
            'loginpass' => md5($u_password)
        ]);

    return redirect('/dsflist');
    }
    
    function DsfBusinessLine()
    {
        
    session()->put("titlename","DSF Business Line List - Sales Booster Admin Panel - Premier Group Of Companies");

    $totaldsfbline=DB::table('sb_dsf_businessline')
    ->count();


    $dsfblinedata = DB::table('sb_dsf_businessline')
    ->orderBy('id', 'asc')
    ->paginate(50);

    $branchmaster=DB::table('sb_branch_master')
    ->get();

    

    return view('dsf_businessline',['totaldsfbline'=>$totaldsfbline,'dsfblinedata'=>$dsfblinedata,'branchmaster'=>$branchmaster]);
    }


    function DsfBusinessLine2(Request $req)
    {
      
   session()->put("titlename","DSF Business Line List - Sales Booster Admin Panel - Premier Group Of Companies");
    $pro_branch=$req->input("pro_branch");
    $pro_group=$req->input("pro_group");


    

    if($pro_branch !="" && $pro_group =="")
    {
     $dsfblinedata = DB::table('sb_dsf_businessline')
    ->where([
    ['branch_code', '=', $pro_branch],
    ])
    ->paginate(50);

    $totaldsfbline = DB::table('sb_dsf_businessline')
    ->where([
    ['branch_code', '=', $pro_branch],
    ])
    ->count();
    
     $dsfblinedata->appends(['pro_branch' => $pro_branch]);

    
    }
    if($pro_branch =="" && $pro_group !="")
    {
    
    $dsfblinedata = DB::table('sb_dsf_businessline')
    ->where([
    ['supply_groupid', '=', $pro_group],
    ])
    ->paginate(50);

    $totaldsfbline = DB::table('sb_dsf_businessline')
    ->where([
    ['supply_groupid', '=', $pro_group],
    ])
    ->count();
    
     $dsfblinedata->appends(['pro_group'=>$pro_group]);

    
    }
    
    if($pro_branch !="" && $pro_group !="")
    {
     $dsfblinedata = DB::table('sb_dsf_businessline')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    ])
    ->paginate(50);

    $totaldsfbline = DB::table('sb_dsf_businessline')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    ])
    ->count();
    
     $dsfblinedata->appends(['pro_branch' => $pro_branch,'pro_group'=>$pro_group]);

    
    }
  
    


    $branchmaster=DB::table('sb_branch_master')
    ->get();

  
    

    return view('dsf_businessline',['totaldsfbline'=>$totaldsfbline,'dsfblinedata'=>$dsfblinedata,'branchmaster'=>$branchmaster]);


    }


    function DsfSafDetail()
    {
         
    session()->put("titlename","DSF SAF Detail List - Sales Booster Admin Panel - Premier Group Of Companies");
    $con=mysqli_connect("127.0.0.1","ytwpdncbtd","9DKdWEz2sr","ytwpdncbtd");

    $fg_not="SELECT branch_code FROM `sb_branch_master` where branch_code not in (SELECT branch_code FROM `sb_dsf_saf_detail_new` group by branch_code)";
    $run_not=mysqli_query($con,$fg_not);
    if(mysqli_num_rows($run_not) !=0)
    {
        while($row_not=mysqli_fetch_array($run_not))
        {
            $branch_codes=$row_not['branch_code'];
            $ins_insert="INSERT INTO `sb_dsf_saf_detail`(branch_code) VALUES ('$branch_codes')";
            $run_insert=mysqli_query($con,$ins_insert);
        }
    }
    $branchid2=session('branchid');
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);




    $dsfsafdata = DB::table('sb_dsf_saf_detail_new')
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(50);

    $branchmaster=DB::table('sb_branch_master')
    ->whereIn('branch_code', $branchid)
    ->get();
    $dsfmaster=DB::table('sb_dsf_master')
    ->get();
   

    

    return view('dsf_safdetail',['dsfsafdata'=>$dsfsafdata,'branchmaster'=>$branchmaster,'dsfmaster'=>$dsfmaster,'dayname'=>'','pro_dsf'=>'','pro_group'=>'','pro_branch'=>'']);
    }

    function GetBranchDsf(Request $req)
    {

         $branchcode=$req->branchcode;
         $pro_group=$req->pro_group;
         // $dsfbrick=$req->dsf_brick;
         if($pro_group !="")
         {
$user=DB::select("SELECT mst.dsf_code,mst.dsf_name FROM `sb_dsf_master` mst,sb_dsf_businessline bus where mst.dsf_code=bus.dsf_code AND mst.branch_code='$branchcode' AND bus.supply_groupid='$pro_group' group by mst.dsf_name");
         }
         else{
           $user=DB::select("SELECT dsf_code,dsf_name,dsf_id FROM `sb_dsf_master` where branch_code='$branchcode'"); 
         }
         
       return response()->json($user);

        
    }

     function DsfSafDetail2(Request $req)
    {
        
    session()->put("titlename","DSF SAF Detail List - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid2=session('branchid');
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $pro_branch=$req->input("pro_branch");
    $pro_group=$req->input("pro_group");
    $pro_dsf=$req->input("pro_dsf");
    $pro_day=$req->input("pro_day");
    
    
    if($pro_branch !="" && $pro_group !="" && $pro_dsf !="" && $pro_day !="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    ['dsf_code', '=', $pro_dsf],
    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    ['dsf_code', '=', $pro_dsf],
    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_branch' => $pro_branch,'pro_group'=>$pro_group,'pro_dsf'=>$pro_dsf,'pro_day'=>$pro_day]);  
    
    }

    if($pro_branch !="" && $pro_group !="" && $pro_dsf !="" && $pro_day =="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    ['dsf_code', '=', $pro_dsf],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    ['dsf_code', '=', $pro_dsf],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_branch' => $pro_branch,'pro_group'=>$pro_group,'pro_dsf'=>$pro_dsf]);  
    
    }

    if($pro_branch !="" && $pro_group =="" && $pro_dsf =="" && $pro_day =="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_branch' => $pro_branch]);  
    
    }

    if($pro_branch =="" && $pro_group !="" && $pro_dsf =="" && $pro_day =="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['supply_groupid', '=', $pro_group],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['supply_groupid', '=', $pro_group],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_group'=>$pro_group]);  
    
    }
 
    if($pro_branch =="" && $pro_group =="" && $pro_dsf !="" && $pro_day =="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['dsf_code', '=', $pro_dsf],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['dsf_code', '=', $pro_dsf],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_dsf'=>$pro_dsf]);  
    
    }

    if($pro_branch !="" && $pro_group !="" && $pro_dsf =="" && $pro_day =="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_branch' => $pro_branch,'pro_group'=>$pro_group]);  
    
    }
    if($pro_branch !="" && $pro_group =="" && $pro_dsf !="" && $pro_day =="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['dsf_code', '=', $pro_dsf],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['dsf_code', '=', $pro_dsf],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_branch' => $pro_branch,'pro_dsf'=>$pro_dsf]);  
    
    }

    if($pro_branch =="" && $pro_group =="" && $pro_dsf !="" && $pro_day !="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['dsf_code', '=', $pro_dsf],
    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['dsf_code', '=', $pro_dsf],
    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_dsf'=>$pro_dsf,'pro_day'=>$pro_day]);  
    
    }

    if($pro_branch =="" && $pro_group =="" && $pro_dsf =="" && $pro_day !="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_day'=>$pro_day]);  
    
    }


    if($pro_branch !="" && $pro_group =="" && $pro_dsf !="" && $pro_day !="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['dsf_code', '=', $pro_dsf],
    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['dsf_code', '=', $pro_dsf],
    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_branch' => $pro_branch,'pro_dsf'=>$pro_dsf,'pro_day'=>$pro_day]);  
    
    }

    if($pro_branch !="" && $pro_group !="" && $pro_dsf =="" && $pro_day !="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    
    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['supply_groupid', '=', $pro_group],
    
    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_branch' => $pro_branch,'pro_group'=>$pro_group,'pro_day'=>$pro_day]);  
    
    }
    if($pro_branch !="" && $pro_group =="" && $pro_dsf =="" && $pro_day !="")
    {

    $totaldsfsaf=DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],

    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_saf_detail')
    ->where([
    ['branch_code', '=', $pro_branch],

    ['day_name', '=', $pro_day],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->orderBy('supply_groupid', 'asc')
    ->orderBy('dsf_code', 'asc')
    ->orderBy('day_name', 'asc')
    ->paginate(150);

    $dsfsafdata->appends(['pro_branch' => $pro_branch,'pro_day'=>$pro_day]);  
    
    }



    

    $branchmaster=DB::table('sb_branch_master')
    ->whereIn('branch_code', $branchid)
    ->get();
    $dsfmaster=DB::table('sb_dsf_master')
    ->get();
    $customermaster=DB::table('sb_customers')
    ->get();

    //get dsf name
    $pro_dsf_name="";
    $dsfdetail=DB::SELECT("SELECT dsf_name FROM `sb_dsf_master` where dsf_code='$pro_dsf' AND branch_code='$pro_branch'");
    foreach($dsfdetail as $dsfm)
    {
        $pro_dsf_name=$dsfm->dsf_name;
    }

    //get group name
    $pro_group_name="";
    $groupdetail=DB::SELECT("SELECT supply_groupname FROM `sb_business_line` where branch_code='$pro_branch' AND supply_groupid='$pro_group'");
    foreach($groupdetail as $gpfm)
    {
        $pro_group_name=$gpfm->supply_groupname;
    }

    //get branch name
    $pro_branch_name="";
    $branchdetail=DB::SELECT("SELECT branch_name FROM `sb_branch_master` where branch_code='$pro_branch'");
    foreach($branchdetail as $brfm)
    {
        $pro_branch_name=$brfm->branch_name;
    }

    

    return view('dsf_safdetail',['totaldsfsaf'=>$totaldsfsaf,'dsfsafdata'=>$dsfsafdata,'branchmaster'=>$branchmaster,'dsfmaster'=>$dsfmaster,'customermaster'=>$customermaster,'dayname'=>$pro_day,'pro_dsf'=>$pro_dsf,'pro_group'=>$pro_group,'pro_branch'=>$pro_branch,'pro_dsf_name'=>$pro_dsf_name,'pro_group_name'=>$pro_group_name,'pro_branch_name'=>$pro_branch_name]);
    }
    
       
    function DsfSafReportSummarize()
    {
         
    session()->put("titlename","DSF SAF Report Summarize - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid2=session('branchid');
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $totaldsfsaf=DB::table('sb_dsf_master')
    ->whereIn('branch_code',$branchid)
    ->count();


    $dsfsafdata = DB::table('sb_dsf_master')
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->paginate(50);

    $branchmaster=DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->get();
    
    

    return view('dsf_safreport_summarize',['totaldsfsaf'=>$totaldsfsaf,'dsfsafdata'=>$dsfsafdata,'branchmaster'=>$branchmaster,'pro_branch'=>'','pro_dsf'=>'']);
    }


    function DsfSafReportSummarize2(Request $req)
    {
         
    session()->put("titlename","DSF SAF Report Summarize - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid2=session('branchid');
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);

    $pro_branch=$req->pro_branch;
    $pro_dsf=$req->pro_dsf;


    if($pro_branch !="" && $pro_dsf !="")
    {
    $totaldsfsaf=DB::table('sb_dsf_master')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['dsf_code', '=', $pro_dsf],
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_master')
    ->where([
    ['branch_code', '=', $pro_branch],
    ['dsf_code', '=', $pro_dsf],
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->paginate(50);

    $dsfsafdata->appends(['pro_branch' => $pro_branch,'pro_dsf' => $pro_dsf]);

    }

    else if($pro_branch !="" && $pro_dsf =="")
    {
    $totaldsfsaf=DB::table('sb_dsf_master')
    ->where([
    ['branch_code', '=', $pro_branch],
    
    ])
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_master')
    ->where([
    ['branch_code', '=', $pro_branch],
    
    ])
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->paginate(50);

    $dsfsafdata->appends(['pro_branch' => $pro_branch]);

    }

    else if($pro_branch =="" && $pro_dsf =="")
    {
    
    $totaldsfsaf=DB::table('sb_dsf_master')
    ->whereIn('branch_code',$branchid)
    ->count();

    $dsfsafdata = DB::table('sb_dsf_master')
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_code', 'asc')
    ->paginate(50);
    }
    

    $branchmaster=DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->get();

    //get dsf name
    $pro_dsf_name="";
    $dsfdetail=DB::SELECT("SELECT dsf_name FROM `sb_dsf_master` where dsf_code='$pro_dsf' AND branch_code='$pro_branch'");
    foreach($dsfdetail as $dsfm)
    {
        $pro_dsf_name=$dsfm->dsf_name;
    }

    //get branch name 
    $pro_branch_name="";
    $branchdetail=DB::SELECT("SELECT branch_name FROM `sb_branch_master` where branch_code='$pro_branch'");
    foreach($branchdetail as $brfm)
    {
        $pro_branch_name=$brfm->branch_name;
    }
    
    

    return view('dsf_safreport_summarize',['totaldsfsaf'=>$totaldsfsaf,'dsfsafdata'=>$dsfsafdata,'branchmaster'=>$branchmaster,'pro_branch'=>$pro_branch,'pro_dsf'=>$pro_dsf,'pro_branch_name'=>$pro_branch_name,'pro_dsf_name'=>$pro_dsf_name]);
    }
    

    function DsfSafBranchwiseSync(Request $req)
    {

if($req->bcode !="")
{
$bcode=$req->bcode;
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => '202.143.120.51:8255/api/SalesBooster/SalesBooster_SafDetails_Select?Brcode='.$bcode,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Pr3mKEY: W74=Jse==ZU1JWR158TjJuUjlVN@t3Zz09',
    'Authorization: Basic UHJFbSFlci5Hcm91cCQkJCsrOkNyRThpVmUmKl4xMjM0NTYrKw=='
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$response_data = json_decode($response);
$totalcount = $response_data->TotalCount;
$user_data = $response_data->Response;
$apiscode = $response_data->Status_Code;

if($apiscode == 200){ 
//delete first
$con=mysqli_connect("127.0.0.1","ytwpdncbtd","9DKdWEz2sr","ytwpdncbtd");
$delete_data="delete FROM `sb_dsf_saf_detail_new` where branch_code='$bcode'";
$run_delete=mysqli_query($con,$delete_data);
$stcode=200;
foreach ($user_data as $user) {

    $brcode=$user->brcode;
    $suppgroupid=$user->suppgroupid;
    $dsfcode=$user->dsfcode;
    $custcode=$user->custcode;
    $dayname=$user->dayname;

    //insert data
    $insert_data="INSERT INTO `sb_dsf_saf_detail_new`(branch_code,supply_groupid,dsf_code,customer_code,day_name) VALUES ('$brcode','$suppgroupid','$dsfcode','$custcode','$dayname')";
    $run_insert=mysqli_query($con,$insert_data);

 }  
  date_default_timezone_set("Asia/Karachi");
  $curr_date = date('y-m-d h:i:s');

  $fg_getdata="SELECT * FROM `sb_dsf_saf_detail_new` where branch_code='$bcode'";
  $run_getdata=mysqli_query($con,$fg_getdata);
  $row_getdata=mysqli_num_rows($run_getdata);
  if($row_getdata == $totalcount)
  {
   
  $ins_log="INSERT INTO `sb_dsf_saf_detail_log` (branch_code,row_count,posted_date,api_name,status_code) VALUES ('$bcode','$totalcount','$curr_date','manualinsertSafDetails',200)";
  $run_log=mysqli_query($con,$ins_log);

    return response()->json(['success' => true]);

  }
  else{
  $ins_log="INSERT INTO `sb_dsf_saf_detail_log` (branch_code,row_count,posted_date,api_name,status_code) VALUES ('$bcode','$totalcount','$curr_date','manualinsertSafDetails',404)";
  $run_log=mysqli_query($con,$ins_log);
   return response()->json(['success' => false]);
   
  }

 }

 else
  {
    
  $ins_log="INSERT INTO `sb_dsf_saf_detail_log` (branch_code,row_count,posted_date,api_name,status_code) VALUES ('$bcode','$totalcount','$curr_date','manualinsertSafDetails',404)";
  $run_log=mysqli_query($con,$ins_log);
   return response()->json(['success' => false]);
  }
  
 
 
  

  }
  else{
  
  $ins_log="INSERT INTO `sb_dsf_saf_detail_log` (branch_code,row_count,posted_date,api_name,status_code) VALUES ('$bcode','$totalcount','$curr_date','manualinsertSafDetails',404)";
  $run_log=mysqli_query($con,$ins_log);
   return response()->json(['success' => false]);     
   }
  
  }
    

  function SupervisorProducts()
    {
        
    session()->put("titlename","Product List - Sales Booster Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
    $admintype=session('admintype');
    $usercode=session('usercode');


    $productdata=DB::SELECT("SELECT prod.product_id,prod.product_code,prod.product_name,sb.is_productive,attr.company_code,comp.company_title,attr.branch_code,attr.active_status,attr.price,attr.attribute_id FROM `sb_products` prod,sb_products_attribute attr,sb_businessline_product sb,sb_supervisor_businessgroup sp,sb_company_master comp where prod.product_code=attr.product_code AND sb.product_code=prod.product_code  AND sb.branch_code=attr.branch_code  AND prod.company_code=comp.company_code AND attr.branch_code =$branchid2 AND sp.group_id=sb.supply_groupid AND sp.supervisor_code='$usercode' AND sp.branch_id='$branchid2' group by sb.product_code order by sb.is_productive,comp.company_title desc");

    $companymaster=DB::SELECT("SELECT bline.company_code,comp.company_title FROM `sb_supervisor_businessgroup` sb,sb_business_line bline,sb_company_master comp where sb.group_id=bline.supply_groupid AND bline.branch_code='$branchid2' AND bline.company_code=comp.company_code AND sb.supervisor_code='$usercode' group by bline.company_code");

    

    return view('supervisor_productlist',['productdata'=>$productdata,'companymaster'=>$companymaster,'prodcode'=>'','activestatus'=>'All','activecode'=>'','c_code'=>'','c_name'=>'All Company']);
    }

    function SupervisorProducts2(Request $req)
    {
        
    session()->put("titlename","Product List - Sales Booster Admin Panel - Premier Group Of Companies");
    $pro_code=$req->pro_code;
    $pro_company=$req->pro_company;
    $pro_active=$req->pro_active;

    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
    $admintype=session('admintype');
    $usercode=session('usercode');

    if($pro_code !="" && $pro_company !="" && $pro_active !="")
    {
        $productdata=DB::SELECT("SELECT prod.product_id,prod.product_code,prod.product_name,sb.is_productive,attr.company_code,comp.company_title,attr.branch_code,attr.active_status,attr.price,attr.attribute_id FROM `sb_products` prod,sb_products_attribute attr,sb_businessline_product sb,sb_supervisor_businessgroup sp,sb_company_master comp where prod.product_code=attr.product_code AND sb.product_code=prod.product_code  AND sb.branch_code=attr.branch_code  AND prod.company_code=comp.company_code AND attr.branch_code =$branchid2 AND sp.group_id=sb.supply_groupid AND sp.supervisor_code='$usercode' AND sp.branch_id='$branchid2' AND prod.product_code='$pro_code' AND attr.company_code='$pro_company'  AND attr.active_status='$pro_active' group by sb.product_code order by sb.is_productive,comp.company_title desc");

    }
    if($pro_code !="" && $pro_company =="" && $pro_active =="")
    {
        $productdata=DB::SELECT("SELECT prod.product_id,prod.product_code,prod.product_name,sb.is_productive,attr.company_code,comp.company_title,attr.branch_code,attr.active_status,attr.price,attr.attribute_id FROM `sb_products` prod,sb_products_attribute attr,sb_businessline_product sb,sb_supervisor_businessgroup sp,sb_company_master comp where prod.product_code=attr.product_code AND sb.product_code=prod.product_code  AND sb.branch_code=attr.branch_code  AND prod.company_code=comp.company_code AND attr.branch_code =$branchid2 AND sp.group_id=sb.supply_groupid AND sp.supervisor_code='$usercode' AND sp.branch_id='$branchid2' AND prod.product_code='$pro_code' group by sb.product_code order by sb.is_productive,comp.company_title desc");

    } 
    if($pro_code =="" && $pro_company !="" && $pro_active =="")
    {
        $productdata=DB::SELECT("SELECT prod.product_id,prod.product_code,prod.product_name,sb.is_productive,attr.company_code,comp.company_title,attr.branch_code,attr.active_status,attr.price,attr.attribute_id FROM `sb_products` prod,sb_products_attribute attr,sb_businessline_product sb,sb_supervisor_businessgroup sp,sb_company_master comp where prod.product_code=attr.product_code AND sb.product_code=prod.product_code  AND sb.branch_code=attr.branch_code  AND prod.company_code=comp.company_code AND attr.branch_code =$branchid2 AND sp.group_id=sb.supply_groupid AND sp.supervisor_code='$usercode' AND sp.branch_id='$branchid2' AND attr.company_code='$pro_company' group by sb.product_code order by sb.is_productive,comp.company_title desc");

    }   
    if($pro_code =="" && $pro_company =="" && $pro_active !="")
    {
        $productdata=DB::SELECT("SELECT prod.product_id,prod.product_code,prod.product_name,sb.is_productive,attr.company_code,comp.company_title,attr.branch_code,attr.active_status,attr.price,attr.attribute_id FROM `sb_products` prod,sb_products_attribute attr,sb_businessline_product sb,sb_supervisor_businessgroup sp,sb_company_master comp where prod.product_code=attr.product_code AND sb.product_code=prod.product_code  AND sb.branch_code=attr.branch_code  AND prod.company_code=comp.company_code AND attr.branch_code =$branchid2 AND sp.group_id=sb.supply_groupid AND sp.supervisor_code='$usercode' AND sp.branch_id='$branchid2'  AND attr.active_status='$pro_active' group by sb.product_code order by sb.is_productive,comp.company_title desc");

    }
    if($pro_code !="" && $pro_company !="" && $pro_active =="")
    {
        $productdata=DB::SELECT("SELECT prod.product_id,prod.product_code,prod.product_name,sb.is_productive,attr.company_code,comp.company_title,attr.branch_code,attr.active_status,attr.price,attr.attribute_id FROM `sb_products` prod,sb_products_attribute attr,sb_businessline_product sb,sb_supervisor_businessgroup sp,sb_company_master comp where prod.product_code=attr.product_code AND sb.product_code=prod.product_code  AND sb.branch_code=attr.branch_code  AND prod.company_code=comp.company_code AND attr.branch_code =$branchid2 AND sp.group_id=sb.supply_groupid AND sp.supervisor_code='$usercode' AND sp.branch_id='$branchid2' AND prod.product_code='$pro_code' AND attr.company_code='$pro_company' group by sb.product_code order by sb.is_productive,comp.company_title desc");

    }
    if($pro_code =="" && $pro_company !="" && $pro_active !="")
    {
        $productdata=DB::SELECT("SELECT prod.product_id,prod.product_code,prod.product_name,sb.is_productive,attr.company_code,comp.company_title,attr.branch_code,attr.active_status,attr.price,attr.attribute_id FROM `sb_products` prod,sb_products_attribute attr,sb_businessline_product sb,sb_supervisor_businessgroup sp,sb_company_master comp where prod.product_code=attr.product_code AND sb.product_code=prod.product_code  AND sb.branch_code=attr.branch_code  AND prod.company_code=comp.company_code AND attr.branch_code =$branchid2 AND sp.group_id=sb.supply_groupid AND sp.supervisor_code='$usercode' AND sp.branch_id='$branchid2' AND attr.company_code='$pro_company'  AND attr.active_status='$pro_active' group by sb.product_code order by sb.is_productive,comp.company_title desc");

    }
    if($pro_code !="" && $pro_company =="" && $pro_active !="")
    {
        $productdata=DB::SELECT("SELECT prod.product_id,prod.product_code,prod.product_name,sb.is_productive,attr.company_code,comp.company_title,attr.branch_code,attr.active_status,attr.price,attr.attribute_id FROM `sb_products` prod,sb_products_attribute attr,sb_businessline_product sb,sb_supervisor_businessgroup sp,sb_company_master comp where prod.product_code=attr.product_code AND sb.product_code=prod.product_code  AND sb.branch_code=attr.branch_code  AND prod.company_code=comp.company_code AND attr.branch_code =$branchid2 AND sp.group_id=sb.supply_groupid AND sp.supervisor_code='$usercode' AND sp.branch_id='$branchid2' AND prod.product_code='$pro_code' AND attr.active_status='$pro_active' group by sb.product_code order by sb.is_productive,comp.company_title desc");

    }
    
    $companymaster=DB::SELECT("SELECT bline.company_code,comp.company_title FROM `sb_supervisor_businessgroup` sb,sb_business_line bline,sb_company_master comp where sb.group_id=bline.supply_groupid AND bline.branch_code='$branchid2' AND bline.company_code=comp.company_code AND sb.supervisor_code='$usercode' group by bline.company_code");
    
    //getting company name
    $compname="";
    if($pro_company !="")
    {
        $companydetail=DB::SELECT("SELECT company_title FROM sb_company_master where company_code='$pro_company'");
        foreach($companydetail as $detail)
        {
           $compname=$detail->company_title;
        }
    }
    $activettile="All";
    if($pro_active == "1"){$activettile="Active";}
    else if($pro_active == "0"){$activettile="In Active";}
    

    return view('supervisor_productlist',['productdata'=>$productdata,'companymaster'=>$companymaster,'prodcode'=>$pro_code,'activestatus'=>$activettile,'activecode'=>$pro_active,'c_code'=>$pro_company,'c_name'=>$compname]);
    }

    function ProductStatusChange2(Request $req)
    {
    	DB::table('sb_products_attribute')
        ->where('attribute_id', $req->id)
        ->update([
            'active_status' => $req->status
        ]);
        
        return response()->json(['success' => true]);
    }

    function ExcelControll()
    {
        
    session()->put("titlename","Excel Controll Access - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid=explode(',', $branchid);
    $totalbranch=DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->count();
 

    $branchdata = DB::table('sb_branch_master')
    ->whereIn('branch_code',$branchid)
    ->orderBy('branch_region', 'asc')
    ->orderBy('branch_zone', 'asc')
    ->paginate(100);


return view('branchwise_excelcontroll',['totalbranch'=>$totalbranch,'branchdata'=>$branchdata]);
    }

    function BranchExcelStatusChange(Request $req)
    {
        DB::table('sb_branch_master')
        ->where('branch_id', $req->id)
        ->update([
            'is_excel_allow' => $req->status
        ]);

        $myuserid=session("adminname");
        date_default_timezone_set("Asia/Karachi");
        $currentdate=date('Y-m-d');

 
        DB::table('branch_excelchange_log')
           ->insert([
            'branch_id' => $req->id,
            'status' => $req->status,
            'change_date' => $currentdate,
            'change_user' => $myuserid,
          
            
           ]);
        
        return response()->json(['success' => true]);
    }

    
    function ProductLockUnlock()
    {
        
    session()->put("titlename","Product Lock/UnLock Management - Sales Booster Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');$branchname=session('branchname');
    $usercode=session('usercode');
   // $branchid=explode(',', $branchid);

   $productdata=DB::table('sb_businessline_product')
   ->join('sb_products', 'sb_businessline_product.product_code', '=', 'sb_products.product_code')
   ->select('sb_businessline_product.id','sb_businessline_product.branch_code','sb_businessline_product.supply_groupid','sb_businessline_product.product_code','sb_businessline_product.status','sb_products.company_code','sb_products.product_name')
   ->where([
    ['sb_businessline_product.branch_code','=',$branchid],
    ['sb_businessline_product.supply_groupid','=',$usercode],
    ])
    ->orderBy('sb_products.company_code', 'asc')
   ->paginate(200);


    //$productdata=DB::SELECT("SELECT sp.id,sp.branch_code,sp.supply_groupid,sp.product_code,sp.status,prod.company_code,prod.product_name FROM `sb_businessline_product` sp,sb_products prod where  sp.product_code=prod.product_code AND  sp.branch_code='$branchid' AND  sp.supply_groupid='$usercode' order by prod.company_code asc");

    $companymaster=DB::SELECT("SELECT comp.company_code,comp.company_title FROM `sb_business_line` bl,sb_company_master comp where bl.company_code=comp.company_code AND bl.branch_code='$branchid' AND bl.supply_groupid='$usercode'");

    

    return view('product_lockunlock',['productdata'=>$productdata,'companymaster'=>$companymaster,'prodcode'=>'','activestatus'=>'All','activecode'=>'','c_code'=>'','c_name'=>'All Company','branchid'=>$branchid,'usercode'=>$usercode]);
    }


    function ProductLockUnlock2(Request $req)
    {
        
    session()->put("titlename","Product Lock/UnLock Management - Sales Booster Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');$branchname=session('branchname');
    $usercode=session('usercode');
   // $branchid=explode(',', $branchid);
   $pro_code=$req->pro_code;
   $pro_company=$req->pro_company;
   $pro_active=$req->pro_active;

   $productdata=DB::table('sb_businessline_product')
   ->join('sb_products', 'sb_businessline_product.product_code', '=', 'sb_products.product_code')
   ->select('sb_businessline_product.id','sb_businessline_product.branch_code','sb_businessline_product.supply_groupid','sb_businessline_product.product_code','sb_businessline_product.status','sb_products.company_code','sb_products.product_name')
   ->where([
    ['sb_businessline_product.branch_code','=',$branchid],
    ['sb_businessline_product.supply_groupid','=',$usercode],
   ]);
   if($pro_code !="")
   {
         $productdata=$productdata->where('sb_businessline_product.product_code', $pro_code);
   }
   if($pro_company !="")
   {
         $productdata=$productdata->where('sb_products.company_code', $pro_company);
   }
   if($pro_active !="")
   {
         $productdata=$productdata->where('sb_businessline_product.status', $pro_active);
   }
   
   $productdata=$productdata->orderBy('sb_products.company_code', 'asc');
   $productdata=$productdata->paginate(200);


    //$productdata=DB::SELECT("SELECT sp.id,sp.branch_code,sp.supply_groupid,sp.product_code,sp.status,prod.company_code,prod.product_name FROM `sb_businessline_product` sp,sb_products prod where  sp.product_code=prod.product_code AND  sp.branch_code='$branchid' AND  sp.supply_groupid='$usercode' order by prod.company_code asc");

    $companymaster=DB::SELECT("SELECT comp.company_code,comp.company_title FROM `sb_business_line` bl,sb_company_master comp where bl.company_code=comp.company_code AND bl.branch_code='$branchid' AND bl.supply_groupid='$usercode'");

    $productdata->appends(['pro_code' => $pro_code,'pro_company' => $pro_company,'pro_active' => $pro_active]);

    return view('product_lockunlock',['productdata'=>$productdata,'companymaster'=>$companymaster,'prodcode'=>'','activestatus'=>'All','activecode'=>'','c_code'=>'','c_name'=>'All Company','branchid'=>$branchid,'usercode'=>$usercode]);
    }

    function ProductStatusChangeForAll(Request $req)
    {
        DB::table('sb_businessline_product')
        ->where([
        ['id', '=', $req->id],
        ])
        ->update([
            'status' => $req->status
        ]);

    DB::table('sb_dsf_product_lock')
   ->where([
    ['brcode', '=', $req->branchid],
    ['business_line', '=', $req->usercode],
    ['product_code', '=', $req->product_code],
    ])
   ->delete();
        
        return response()->json(['success' => true]);
    }

    function GetBusinessLineDsf(Request $req)
    {
        $saleval=DB::SELECT("SELECT dsf_code,dsf_name,branch_code FROM `sb_dsf_businessline` where branch_code='$req->branchid' AND supply_groupid='$req->usercode'");
        
        return response()->json($saleval);
    }

    function LockParticularDsf($branchid,$usercode,$id,$product_code,$product_name)
    {
    
        
    session()->put("titlename","Product Lock/UnLock Management - Sales Booster Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');$branchname=session('branchname');
    $usercode=session('usercode');
   // $branchid=explode(',', $branchid);

    $dsfdata=DB::SELECT("SELECT dsf_code,dsf_name,branch_code FROM `sb_dsf_businessline` where branch_code='$branchid' AND supply_groupid='$usercode'");
    

    return view('product_dsflock',['dsfdata'=>$dsfdata,'branchid'=>$branchid,'usercode'=>$usercode,'id'=>$id,'product_code'=>$product_code,'product_name'=>$product_name]);
    }


    function LockParticularDsf2(Request $req)
    {
    
        
    session()->put("titlename","Product Lock/UnLock Management - Sales Booster Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');$branchname=session('branchname');
    $usercode=session('usercode');
   // $branchid=explode(',', $branchid);
   $branchid=$req->branchid;
   $usercode=$req->usercode;
   $product_code=$req->product_code;
   date_default_timezone_set("Asia/Karachi");
   $curr_date = date('Y-m-d h:i:s');

   //first delete
   DB::table('sb_dsf_product_lock')
   ->where([
    ['brcode', '=', $branchid],
    ['business_line', '=', $usercode],
    ['product_code', '=', $product_code],
    ])
   ->delete();

   foreach($req->entry as $menuid)
       {

        

       	 DB::table('sb_dsf_product_lock')
        ->insert([
            'business_line' => $usercode,
            'brcode' => $branchid,
            'dsfcode'=>$menuid,
            'product_code'=>$product_code,
            'insert_datetime'=>$curr_date,
            
        ]);

       } 


     return redirect('/productlockunlock');
    }
    
    

   
}