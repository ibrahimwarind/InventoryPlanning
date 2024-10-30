<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryForecastingController extends Controller
{  
    //
    function CreateInventoryForecasting()
    {
      if(session()->has("adminname"))
        {
    	session()->put("titlename","Inventory Forecasting - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
     $adminid=session('adminid');
        date_default_timezone_set("Asia/Karachi");
        $date = date('Y-m-d H:i:s');
   
    $formulamasterdata=DB::SELECT("SELECT * FROM `ipl_formula_master`");
    $branchdata=DB::SELECT("SELECT * FROM `ipl_branch_master` where branch_code in ($branchid2) order by branch_code asc");

    $companydata=DB::SELECT("SELECT * FROM `ipl_company_master` order by company_title asc");

    $questiondata=DB::SELECT("SELECT question_title,question_id FROM `ipl_checklist_question` where status=1");

    $insertedId = DB::table('ipl_forecasting_id')
    ->insertGetId([
        'company_id' => 0,
        'post_datetime' => $date,
        'post_user' => $adminid,
    ]);


    return view('create_inventory_forecasting',['formulamasterdata'=>$formulamasterdata,'data'=>'','branchdata'=>$branchdata,'companydata'=>$companydata,'questiondata'=>$questiondata,'direct'=>0,'branchiddata'=>'','insertedId'=>$insertedId]);
     }

     else
     {
            session()->put("titlename","Inventory Planning - Admin Login - Premier Group Of Companies");
            return view('login',['data'=>'']);
     }

    }

     function CreateInventoryForecastingthrough($formulaid,$formuladetailid,$branchid,$companyid,$shelflife)
    {
    
    session()->put("titlename","Inventory Forecasting - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid2=session('branchid');
  
    $formulamasterdata=DB::SELECT("SELECT * FROM `ipl_formula_master`");
    
    $branchdata=DB::SELECT("SELECT * FROM `ipl_branch_master` where branch_code in ($branchid2) order by branch_code asc");

    $companydata=DB::SELECT("SELECT * FROM `ipl_company_master` order by company_title asc");

    $questiondata=DB::SELECT("SELECT question_title,question_id FROM `ipl_checklist_question` where status=1");

      $adminid=session('adminid');
        date_default_timezone_set("Asia/Karachi");
        $date = date('Y-m-d H:i:s');

     $insertedId = DB::table('ipl_forecasting_id')
    ->insertGetId([
        'company_id' => 0,
        'post_datetime' => $date,
        'post_user' => $adminid,
    ]);


    return view('create_inventory_forecasting',['formulamasterdata'=>$formulamasterdata,'data'=>'','branchdata'=>$branchdata,'companydata'=>$companydata,'questiondata'=>$questiondata,'direct'=>1,'formulaid'=>$formulaid,'formuladetailid'=>$formuladetailid,'branchiddata'=>$branchid,'companyid'=>$companyid,'shelflife'=>$shelflife,'insertedId'=>$insertedId]);
    
    }
 

    function GetFormulaGroup(Request $req)
    {

         $formula_id=$req->formula_id;
        
         $user=DB::select("SELECT * FROM `ipl_group_master` where formula_id='$formula_id' AND permanent_temporary=2");
         
        return response()->json($user);

        
    }

    function GetCompanyBranch(Request $req)
    {

         $company_id=$req->company_id;
         $branchid2=session('branchid');
        
         $user=DB::select("SELECT bline.branch_code,br.branch_name FROM `ipl_business_line` bline inner join ipl_branch_master br on bline.branch_code=br.branch_code where bline.company_code='$company_id' AND bline.branch_code in ($branchid2) group by bline.branch_code order by bline.branch_code asc");
         
        return response()->json($user);

        
    }
    

    function CheckFirstVersionOrNot(Request $req)
    {

         $company_id=$req->company_id;
         $branch_id=$req->branch_id;
         $forecasting_month=$req->forecasting_month;

         $branch_array = explode(',', $branch_id);
         $branch_count = count($branch_array);

         $con=mysqli_connect('127.0.0.1','root','','db_ipl');
         
         $fg_getchecks="SELECT * FROM `ipl_forecasting_branch_data` where company_id=$company_id AND branch_id in ($branch_id) AND forecasting_month='$forecasting_month' AND is_closed=1";
         $run_getchecks=mysqli_query($con,$fg_getchecks);
         if(mysqli_num_rows($run_getchecks) != 0)
         {
             $fg_notinlistpresent="SELECT CASE WHEN COUNT(DISTINCT branch_id) = $branch_count THEN 'True' ELSE 'False' END AS all_branches_present FROM ipl_forecasting_branch_data WHERE company_id = '$company_id' AND forecasting_month = '$forecasting_month' AND branch_id IN ($branch_id) AND is_closed=1";
             $run_notinlistpresent=mysqli_query($con,$fg_notinlistpresent);
             $row_notinlistpresent=mysqli_fetch_array($run_notinlistpresent);
             $all_branches_present=$row_notinlistpresent['all_branches_present'];
             if($all_branches_present == "True")
             {

               $fg_checkmultipleversion="SELECT version_no FROM `ipl_forecasting_branch_data` WHERE company_id = '$company_id' AND forecasting_month = '$forecasting_month' AND branch_id IN ($branch_id) AND is_closed=1 group by version_no";
               $run_checkmultipleversion=mysqli_query($con,$fg_checkmultipleversion);
               if(mysqli_num_rows($run_checkmultipleversion) == 1)
               { 
                 $row_checkmultipleversion=mysqli_fetch_array($run_checkmultipleversion);
                 $status='True';
                 $oldversionno=$row_checkmultipleversion['version_no'];

               }
               else if(mysqli_num_rows($run_checkmultipleversion) != 1 && $branch_count == 1)
               { 
                 $row_checkmultipleversion=mysqli_fetch_array($run_checkmultipleversion);
                 $status='True';
                 $oldversionno=$row_checkmultipleversion['version_no'];

               }else{
                 

               // //check new
               $fg_checknew="SELECT version_no FROM `ipl_forecasting_branch_data` where branch_id in ($branch_id) AND forecasting_month='$forecasting_month' AND company_id='$company_id' AND is_closed=1 order by version_no desc limit 1";
               $run_checknew=mysqli_query($con,$fg_checknew);
               $row_checknew=mysqli_fetch_array($run_checknew);
               $getversion_no=$row_checknew['version_no'];

               

               $fg_lastcheck="SELECT CASE WHEN COUNT(DISTINCT branch_id) = $branch_count THEN 'True' ELSE 'False' END AS all_branches_presentnew FROM ipl_forecasting_branch_data WHERE company_id = '$company_id' AND forecasting_month = '$forecasting_month' AND version_no='$getversion_no' AND branch_id IN ($branch_id) AND is_closed=1";
               $run_lastcheck=mysqli_query($con,$fg_lastcheck);
               $row_lastcheck=mysqli_fetch_array($run_lastcheck);
               $all_branches_presentnew=$row_lastcheck['all_branches_presentnew'];
               if($all_branches_presentnew == "True")
               {
                 $status='True';
                $oldversionno=$getversion_no;
               }
               else{
                 $status='False';
                $oldversionno=0;
               }

               }

              

             }
             else{
              $status='False';
              $oldversionno=0;
             }
         }
         else{
          $status='True';
          $oldversionno=0;
         }

         

        
         
         
     return response()->json([
    'status' => $status,
    'oldversionno' => $oldversionno
     ]);

        
    }

    function GetEveryTimeQuestion(Request $req)
    {
        $version_no=$req->version_no;
        if($version_no == 0)
        {
$user=DB::SELECT("SELECT question_id,question_title FROM `ipl_checklist_question` where status=1");  
        }
        else{
          $user=DB::SELECT("SELECT question_id,question_title FROM `ipl_checklist_question` where status=1 AND every_time=0");  
        }
        

        return response()->json($user);

        
    }
    function GetFirstVersionQuestion(Request $req)
    {

        $user=DB::SELECT("SELECT question_id,question_title FROM `ipl_checklist_question` where status=1");  

        return response()->json($user);

        
    }
    

    

    function GetGroupCompany(Request $req)
    {

         $group_id=$req->group_id;
         if($group_id !="")
         {
          $user=DB::select("select comp.company_code,comp.company_title from ipl_group_master grp inner join ipl_company_master comp on grp.company_id=comp.company_code where grp.group_id='$group_id'");
         }
         else{
          $user=DB::select("SELECT company_code,company_title FROM `ipl_company_master` order by company_title asc");
         }
         
         
        return response()->json($user);

        
    }


    function CreateInventoryForecastingList(Request $req)
    { 
    session()->put("titlename","Inventory Forecasting - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
     $adminid=session('adminid');
        date_default_timezone_set("Asia/Karachi");
        $date = date('Y-m-d H:i:s');

    $formula_id=$req->formula_id;
    $formula_det_id=$req->formula_det_id;
    $group_id=$req->group_id;
    $company_id=$req->company_id;

    $shelf_life=$req->shelf_life;
    $forecasting_month=$req->forecasting_month;
    $stockshowing=$req->stockshowing;
    $versionprevious=$req->versionprevious;
    $sort_by=$req->sort_by;
    $insertedId=$req->insertedId;
    $checkedEntries = $req->input('entry', []);
    $questiondata = $req->input('selection', []);

    $branch_id="";$questionids="";
    if (!empty($checkedEntries)) {
            $indexcount=1;
            foreach ($checkedEntries as $productCode) {
                  if($indexcount == 1)
                  {
                    $branch_id.=$productCode;
                  }else{
                    $branch_id.=",".$productCode;
                  }
                  $indexcount=$indexcount + 1;
            }
    }
    if (!empty($questiondata)) {
            $indexcount=1;
            foreach ($questiondata as $productCode) {
                  if($indexcount == 1)
                  {
                    $questionids.=$productCode;
                  }else{
                    $questionids.=",".$productCode;
                  }
                  $indexcount=$indexcount + 1;
            }
    }

    if($sort_by == "Branch Wise")
    {
     $forecastingdata=DB::SELECT("select * from ipl_branch_master where branch_code in ($branch_id)");
    }
    else{
      if($group_id !="")
      {
       $forecastingdata=DB::SELECT("SELECT prod.product_code,prod.product_name,prod.packing_size FROM `ipl_group_mapping` grp inner join ipl_products prod on grp.product_code=prod.product_code where grp.group_id='$group_id' and prod.status=1 order by prod.product_name asc");
      }
      else{
       $forecastingdata=DB::SELECT("SELECT product_code,product_name,packing_size FROM `ipl_products` where company_code='$company_id' AND status=1 order by product_name asc");
      }

    }


   



    $totalgroupproductcount=0;
    if($versionprevious == 0)
    {
    $totalgroupproduct=DB::SELECT("SELECT ifnull(count(mapping_id),0) as totalgroupproduct FROM `ipl_group_mapping` where product_code in (select product_code from ipl_products where company_code='$company_id' and status=1) AND permanent_temporary=1");
    }
    else{
$totalgroupproduct=DB::SELECT("SELECT ifnull(count(mapping_id),0) as totalgroupproduct FROM `ipl_group_mapping` where product_code in (select product_code from ipl_products where company_code='$company_id' and status=1) AND permanent_temporary=2");
    }
    
    foreach($totalgroupproduct as $grp)
    {
      $totalgroupproductcount=$grp->totalgroupproduct;
    }

    $totalcompanyproductcount=0;
    $totalcompanyproduct=DB::SELECT("select ifnull(count(product_id),0) as totalcompanyproduct from ipl_products where company_code='$company_id' and status=1");
    foreach($totalcompanyproduct as $prd)
    {
      $totalcompanyproductcount=$prd->totalcompanyproduct;
    }

    
    $modalshowstatus='No';
    if($group_id ==""){
    if($totalgroupproductcount != $totalcompanyproductcount)
    {
      $modalshowstatus="Yes";
    }
    }


    $notsetpackingproduct=0;
    $notsetpacking=DB::SELECT("select ifnull(count(product_id),0) as totalnotsetpackingsize from ipl_products where company_code='$company_id' and status=1 AND packing_size=0");
    foreach($notsetpacking as $setpacking)
    {
     $notsetpackingproduct=$setpacking->totalnotsetpackingsize;
    }


  





                 

    return view('create_inventory_forecastinglist',['formula_id'=>$formula_id,'formula_det_id'=>$formula_det_id,'group_id'=>$group_id,'company_id'=>$company_id,'shelf_life'=>$shelf_life,'forecasting_month'=>$forecasting_month,'stockshowing'=>$stockshowing,'versionprevious'=>$versionprevious,'sort_by'=>$sort_by,'questionids'=>$questionids,'branch_id'=>$branch_id,'forecastingdata'=>$forecastingdata,'modalshowstatus'=>$modalshowstatus,'notsetpackingproduct'=>$notsetpackingproduct,'insertedId'=>$insertedId]);
    }




    function CreateInventoryForecasting2($formula_id,$formula_det_id,$group_id,$company_id,$shelf_life,$forecasting_month,$stockshowing,$versionprevious,$sort_by,$questionids,$branch_id,$product_code,$insertedId)
    {
    session()->put("titlename","Inventory Forecasting - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);

    
    $formulamasterdata=DB::SELECT("SELECT * FROM `ipl_formula_master`");
    $branchdata=DB::SELECT("SELECT * FROM `ipl_branch_master` order by branch_code asc");

    $companydata=DB::SELECT("SELECT * FROM `ipl_company_master` order by company_title asc");

    $multiplydata=DB::SELECT("SELECT multiply_by,detail_id FROM `ipl_formula_detail` where formula_id='$formula_id'");

    if($versionprevious == 0)
    {
$groupdata=DB::SELECT("select group_id,group_name from ipl_group_master where formula_id='$formula_id' AND permanent_temporary=1");
    }
    else{
      $groupdata=DB::SELECT("select group_id,group_name from ipl_group_master where formula_id='$formula_id' AND permanent_temporary=2");
    }
    

    if($product_code == "0")
    {


    if($group_id !=0)
    {
      $productdata=DB::SELECT("SELECT prod.product_code,prod.product_name,prod.packing_size FROM `ipl_group_mapping` grp inner join ipl_products prod on grp.product_code=prod.product_code where grp.group_id='$group_id' order by prod.product_name asc");
    }
    else{
      $productdata=DB::SELECT("SELECT product_code,product_name,packing_size FROM `ipl_products` where company_code='$company_id' AND status=1 order by product_name asc");
    }

    
    }else{
      $productdata=DB::SELECT("SELECT product_code,product_name,packing_size FROM `ipl_products` where company_code='$company_id' AND product_code='$product_code' AND status=1 order by product_name asc");
    }

//     $totalgroupproductcount=0;
//     if($versionprevious == 0)
//     {
//     $totalgroupproduct=DB::SELECT("SELECT ifnull(count(mapping_id),0) as totalgroupproduct FROM `ipl_group_mapping` where product_code in (select product_code from ipl_products where company_code='$company_id' and status=1) AND permanent_temporary=1");
//     }
//     else{
// $totalgroupproduct=DB::SELECT("SELECT ifnull(count(mapping_id),0) as totalgroupproduct FROM `ipl_group_mapping` where product_code in (select product_code from ipl_products where company_code='$company_id' and status=1) AND permanent_temporary=2");
//     }
    
//     foreach($totalgroupproduct as $grp)
//     {
//       $totalgroupproductcount=$grp->totalgroupproduct;
//     }

//     $totalcompanyproductcount=0;
//     $totalcompanyproduct=DB::SELECT("select ifnull(count(product_id),0) as totalcompanyproduct from ipl_products where company_code='$company_id' and status=1");
//     foreach($totalcompanyproduct as $prd)
//     {
//       $totalcompanyproductcount=$prd->totalcompanyproduct;
//     }

    
//     $modalshowstatus='No';
//     if($group_id ==""){
//     if($totalgroupproductcount != $totalcompanyproductcount)
//     {
//       $modalshowstatus="Yes";
//     }
//     }


//     $notsetpackingproduct=0;
//     $notsetpacking=DB::SELECT("select ifnull(count(product_id),0) as totalnotsetpackingsize from ipl_products where company_code='$company_id' and status=1 AND packing_size=0");
//     foreach($notsetpacking as $setpacking)
//     {
//      $notsetpackingproduct=$setpacking->totalnotsetpackingsize;
//     }







    return view('create_inventory_forecasting2',['formulamasterdata'=>$formulamasterdata,'data'=>'','branchdata'=>$branchdata,'companydata'=>$companydata,'formula_id'=>$formula_id,'formula_det_id'=>$formula_det_id,'group_id'=>$group_id,'company_id'=>$company_id,'branch_ids'=>$branch_id,'shelf_life'=>$shelf_life,'productdata'=>$productdata,'groupdata'=>$groupdata,'multiplydata'=>$multiplydata,'forecasting_month'=>$forecasting_month,'stockshowing'=>$stockshowing,'versionprevious'=>$versionprevious,'sort_by'=>$sort_by,'questionids'=>$questionids,'insertedId'=>$insertedId]);
    }
    



//     function CreateInventoryForecasting2(Request $req)
//     {
//     session()->put("titlename","Inventory Forecasting - Inventory Planning Admin Panel - Premier Group Of Companies");
//     $branchid=session('branchid');
//     $branchid2=session('branchid');
//     $branchid=explode(',', $branchid);

//     $formula_id=$req->formula_id;
//     $formula_det_id=$req->formula_det_id;
//     $group_id=$req->group_id;
//     $company_id=$req->company_id;

//     $shelf_life=$req->shelf_life;
//     $forecasting_month=$req->forecasting_month;
//     $stockshowing=$req->stockshowing;
//     $versionprevious=$req->versionprevious;
//     $sort_by=$req->sort_by;
//     $checkedEntries = $req->input('entry', []);
//     $questiondata = $req->input('selection', []);

//     $branch_id="";$questionids="";
//     if (!empty($checkedEntries)) {
//             $indexcount=1;
//             foreach ($checkedEntries as $productCode) {
//                   if($indexcount == 1)
//                   {
//                     $branch_id.=$productCode;
//                   }else{
//                     $branch_id.=",".$productCode;
//                   }
//                   $indexcount=$indexcount + 1;
//             }
//     }
//     if (!empty($questiondata)) {
//             $indexcount=1;
//             foreach ($questiondata as $productCode) {
//                   if($indexcount == 1)
//                   {
//                     $questionids.=$productCode;
//                   }else{
//                     $questionids.=",".$productCode;
//                   }
//                   $indexcount=$indexcount + 1;
//             }
//     }



//     $formulamasterdata=DB::SELECT("SELECT * FROM `ipl_formula_master`");
//     $branchdata=DB::SELECT("SELECT * FROM `ipl_branch_master` order by branch_code asc");

//     $companydata=DB::SELECT("SELECT * FROM `ipl_company_master` order by company_title asc");

//     $multiplydata=DB::SELECT("SELECT multiply_by,detail_id FROM `ipl_formula_detail` where formula_id='$formula_id'");

//     if($versionprevious == 0)
//     {
// $groupdata=DB::SELECT("select group_id,group_name from ipl_group_master where formula_id='$formula_id' AND permanent_temporary=1");
//     }
//     else{
//       $groupdata=DB::SELECT("select group_id,group_name from ipl_group_master where formula_id='$formula_id' AND permanent_temporary=2");
//     }
    

//     if($group_id !="")
//     {
//       $productdata=DB::SELECT("SELECT prod.product_code,prod.product_name,prod.packing_size FROM `ipl_group_mapping` grp inner join ipl_products prod on grp.product_code=prod.product_code where grp.group_id='$group_id' order by prod.product_name asc");
//     }
//     else{
//       $productdata=DB::SELECT("SELECT product_code,product_name,packing_size FROM `ipl_products` where company_code='$company_id' AND status=1 order by product_name asc");
//     }

//     $totalgroupproductcount=0;
//     if($versionprevious == 0)
//     {
//     $totalgroupproduct=DB::SELECT("SELECT ifnull(count(mapping_id),0) as totalgroupproduct FROM `ipl_group_mapping` where product_code in (select product_code from ipl_products where company_code='$company_id' and status=1) AND permanent_temporary=1");
//     }
//     else{
// $totalgroupproduct=DB::SELECT("SELECT ifnull(count(mapping_id),0) as totalgroupproduct FROM `ipl_group_mapping` where product_code in (select product_code from ipl_products where company_code='$company_id' and status=1) AND permanent_temporary=2");
//     }
    
//     foreach($totalgroupproduct as $grp)
//     {
//       $totalgroupproductcount=$grp->totalgroupproduct;
//     }

//     $totalcompanyproductcount=0;
//     $totalcompanyproduct=DB::SELECT("select ifnull(count(product_id),0) as totalcompanyproduct from ipl_products where company_code='$company_id' and status=1");
//     foreach($totalcompanyproduct as $prd)
//     {
//       $totalcompanyproductcount=$prd->totalcompanyproduct;
//     }

    
//     $modalshowstatus='No';
//     if($group_id ==""){
//     if($totalgroupproductcount != $totalcompanyproductcount)
//     {
//       $modalshowstatus="Yes";
//     }
//     }


//     $notsetpackingproduct=0;
//     $notsetpacking=DB::SELECT("select ifnull(count(product_id),0) as totalnotsetpackingsize from ipl_products where company_code='$company_id' and status=1 AND packing_size=0");
//     foreach($notsetpacking as $setpacking)
//     {
//      $notsetpackingproduct=$setpacking->totalnotsetpackingsize;
//     }







//     return view('create_inventory_forecasting2',['formulamasterdata'=>$formulamasterdata,'data'=>'','branchdata'=>$branchdata,'companydata'=>$companydata,'formula_id'=>$formula_id,'formula_det_id'=>$formula_det_id,'group_id'=>$group_id,'company_id'=>$company_id,'branch_ids'=>$branch_id,'shelf_life'=>$shelf_life,'productdata'=>$productdata,'groupdata'=>$groupdata,'multiplydata'=>$multiplydata,'modalshowstatus'=>$modalshowstatus,'notsetpackingproduct'=>$notsetpackingproduct,'forecasting_month'=>$forecasting_month,'stockshowing'=>$stockshowing,'versionprevious'=>$versionprevious,'sort_by'=>$sort_by,'questionids'=>$questionids]);
//     }


    function GetCompanyProduct(Request $req)
    {

         $new_company_id=$req->new_company_id;
         
         $user=DB::SELECT("SELECT * FROM `ipl_products` where company_code='$new_company_id'");
         
         
        return response()->json($user);

        
    }

    function GetFormulaMultiply(Request $req)
    {

         $formula_id=$req->formula_id;
         
         $user=DB::SELECT("SELECT multiply_by FROM `ipl_formula_detail` where formula_id='$formula_id'");
         
         
        return response()->json($user);

        
    }

    function GetMultiplyGroupId(Request $req)
    {

         $new_multiply_by=$req->new_multiply_by;
         $formula_id=$req->formula_id;
         $group_id=0;
         $user=DB::SELECT("SELECT detail_id as group_id FROM `ipl_formula_detail` where formula_id='$formula_id' AND multiply_by='$new_multiply_by'");
         foreach($user as $us)
         {
          $group_id=$us->group_id;
         }
        return response()->json($group_id);
    }
    

    function GetProductInventoryCosting(Request $req)
    {

         $new_product_id=$req->new_product_id;
         $branch_id=$req->branch_id;

         $productname="";
         $productdata=DB::SELECT("SELECT * FROM `ipl_products` where product_code='$new_product_id'");
         foreach($productdata as $prd)
         {
          $productname=$prd->product_name;
         }

         $sales_qty1=0;$month_year1="";$sales_qty2=0;$month_year2="";$sales_qty3=0;$month_year3="";$in_stock=0;$in_stock_monthyear="";$in_transitstock=0;

         $invdata=DB::SELECT("SELECT sales_qty1,month_year1,sales_qty2,month_year2,sales_qty3,month_year3,in_stock,in_stock_monthyear,in_transitstock FROM `ipl_branchwise_product_stock` where branch_code=$branch_id AND product_code=$new_product_id");
         foreach($invdata as $inv)
         {
          $sales_qty1=$inv->sales_qty1;
          $sales_qty2=$inv->sales_qty2;
          $sales_qty3=$inv->sales_qty3;
          $in_stock=$inv->in_stock;
          $in_transitstock=$inv->in_transitstock;


         }

         $responseData = [
        'product_name' => $productname,
        'sales_qty1' => $sales_qty1,
        'sales_qty2' => $sales_qty2,
        'sales_qty3' => $sales_qty3,
        'in_stock' => $in_stock,
        'in_transitstock' => $in_transitstock,
       ];
         
        return response()->json($responseData);
        
    }

 
    function SaveForecasting(Request $req)
    {
    
    session()->put("titlename","Inventory Forecasting - Inventory Planning Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
    $con=mysqli_connect('127.0.0.1','root','','db_ipl');

    $formula_id=$req->formula_id;
    $formula_det_id=$req->formula_det_id;
    $group_id=$req->group_id;
    $company_id=$req->company_id;
    $branch_id=$req->branch_id;
    $shelf_life=$req->shelf_life;
    $multiply_by=$req->multiply_by;
    $formulaname=$req->formulaname;
    $forecasting_month=$req->forecasting_month;
    $indexcount=$req->indexcount;
    $branchrowcount=$req->branchrowcount;
    $stockshowing=$req->stockshowing;
    $versionprevious=$req->versionprevious;
    $sort_by=$req->sort_by;
    $questionids=$req->questionids;
    $insertedId=$req->insertedId;

    if($versionprevious == 0)
    {
    $formno=0;
    $data_formno=DB::SELECT("SELECT COALESCE(MAX(forcasting_formno), 0) + 1 AS newforcasting FROM tbl_forecasting_master where is_closed=1");
    foreach($data_formno as $fmno)
    {
      $formno=$fmno->newforcasting;
    }

    $unique_key="PLAN_".date('Ym')."0".$formno;
    }
    else{

      $fg_gettingkey=DB::SELECT("SELECT forcasting_unique_key,forcasting_formno FROM `tbl_forecasting_master` where company_id='$company_id' AND branch_id in ($branch_id) AND forecasting_month='$forecasting_month'");
      foreach($fg_gettingkey as $fkey)
      {
        $unique_key=$fkey->forcasting_unique_key;
        $formno=$fkey->forcasting_formno;
      }

    }
    
    $version_no=$versionprevious + 1;
    $myuserid=session("adminname");
    date_default_timezone_set("Asia/Karachi");
    $currentdate=date('Y-m-d h:i:s');

    $fg_checkinsertedid="SELECT * FROM tbl_forecasting_master where forecasting_id='$insertedId'";
    $run_checkinsertedid=mysqli_query($con,$fg_checkinsertedid);
    if(mysqli_num_rows($run_checkinsertedid) == 0)
    {
       DB::table('tbl_forecasting_master')
          ->insert([
            'forecasting_id'=>$insertedId,
            'forcasting_unique_key' => $unique_key,
            'forcasting_formno' => $formno,
            'version_no'=>$version_no,
            'formula_id'=>$formula_id,
            'formula_detail_id'=>$formula_det_id,
            'group_id'=>$group_id,
            'company_id'=>$company_id,
            'branch_id'=>$branch_id,
            'shelf_life'=>$shelf_life,
            'multiply_by'=>$multiply_by,
            'post_user'=>$myuserid,
            'post_datetime'=>$currentdate,
            'forecasting_month'=>$forecasting_month,
            'sort_by'=>$sort_by,
            'question_ids'=>$questionids,
          ]);

   $forecasting_id=0;
    $dbdataforcast=DB::SELECT("SELECT forecasting_id FROM `tbl_forecasting_master` order by forecasting_id desc limit 1");
    foreach($dbdataforcast as $fcr)
    {
     $forecasting_id=$fcr->forecasting_id;
    }
 
    }
    else{
        $forecasting_id=$insertedId;
    }
   
    


for($j=1;$j <= $branchrowcount;$j++)
    {
    for($i=1;$i <= $indexcount;$i++)
    {
      $productcode=$req->input('productcode'.$i.'_'.$j);
      $saleqty1_=$req->input('saleqty1_'.$i.'_'.$j);
      $saleqty2_=$req->input('saleqty2_'.$i.'_'.$j);
      $saleqty3_=$req->input('saleqty3_'.$i.'_'.$j);
      $salemonth_1_=$req->input('salemonth_1_'.$i.'_'.$j);
      $salemonth_2_=$req->input('salemonth_2_'.$i.'_'.$j);
      $salemonth_3_=$req->input('salemonth_3_'.$i.'_'.$j);
      $avgsales=$req->input('avgsales'.$i.'_'.$j);
      $maxsales=$req->input('maxsales'.$i.'_'.$j);
      $instock=$req->input('instock'.$i.'_'.$j);
      $in_transitstock=$req->input('in_transitstock'.$i.'_'.$j);
      $netstock=$req->input('netstock'.$i.'_'.$j);
      $formulaoutput=$req->input('formulaoutput'.$i.'_'.$j);
      $demand=$req->input('demand'.$i.'_'.$j);
      $finaloutput=$req->input('finaloutput'.$i.'_'.$j);
      $rowformulaid=$req->input('rowformulaid'.$i.'_'.$j);
      $new_multiply_by=$req->input('new_multiply_by'.$i.'_'.$j);
      $instockmonth=$req->input('instockmonth'.$i.'_'.$j);
      $groupid=$req->input('groupid'.$i.'_'.$j);
      $manualstock=$req->input('manualstock'.$i.'_'.$j);
      $branchidrow=$req->input('branchidrow'.$i.'_'.$j);
      $remarks=$req->input('remarks'.$i.'_'.$j);
      $packingsize=$req->input('packingsize'.$i.'_'.$j);
      $price=$req->input('price'.$i.'_'.$j);
      $carryforward=$req->input('carryforward'.$i.'_'.$j);

      $netstock=$instock + $in_transitstock + $manualstock;
      $formulaoutput=($avgsales * $new_multiply_by) - $netstock;
      if($formulaoutput < 1){$formulaoutput=0;}
      if($demand !=0 && $demand !='')
      {
        $finaloutput=$demand - abs($formulaoutput);
        $finaloutput2=$demand;
        $finaloutputvalue=$demand * $price;
        $finaloutput3=$demand / $packingsize; 
      }else{
$finaloutput2=$formulaoutput;
$finaloutputvalue=$formulaoutput * $price;
$finaloutput3=$formulaoutput / $packingsize;
      }
      

      DB::table('ipl_forecasting_detail')
          ->insert([
            'forecasting_id' => $forecasting_id,
            'product_code' => $productcode,
            'sale_qty1'=>$saleqty1_,
            'sale_qty2'=>$saleqty2_,
            'sale_qty3'=>$saleqty3_,
            'sale_month1'=>$salemonth_1_,
            'sale_month2'=>$salemonth_2_,
            'sale_month3'=>$salemonth_3_,
            'avg_sales'=>$avgsales,
            'max_sales'=>$maxsales,
            'current_stock'=>$instock,
            'current_stockmonth'=>$instockmonth,
            'in_transit_stock'=>$in_transitstock,
            'net_stock'=>$netstock,
            'pr_diff'=>$formulaoutput,
            'demand'=>$demand,
            'pspl_diff'=>$finaloutput,
            'formula_id'=>$rowformulaid,
            'formula_detail_id'=>$formula_det_id,
            'multiply_by'=>$new_multiply_by,
            'group_detail_id'=>$groupid,
            'manual_stock'=>$manualstock,
            'remarks'=>$remarks,
            'branch_row_id'=>$branchidrow,
            'final_planoutput'=>$finaloutput2,
            'final_output_packingsize'=>$finaloutput3,
            'packing_size'=>$packingsize,
            'price'=>$price,
            'final_output_value'=>$finaloutputvalue,
            'carryforward'=>$carryforward
          ]);

      

    }



  }

//end branch wise

  //insert branchwise data
  $branch_array = explode(",", $branch_id);
  foreach($branch_array as $branch)
  {
    $fg_checkbranchversion="SELECT * FROM `ipl_forecasting_branch_data` where forecasting_master_id='$insertedId' AND branch_id='$branch' AND company_id='$company_id' and forecasting_month='$forecasting_month'";
    $run_checkbranchversion=mysqli_query($con,$fg_checkbranchversion);
    if(mysqli_num_rows($run_checkbranchversion) == 0)
    {

    $fg_getbranchversion="SELECT COALESCE(MAX(version_no), 0) + 1 as newversion FROM `ipl_forecasting_branch_data` where branch_id='$branch' AND company_id=$company_id AND forecasting_month='$forecasting_month' AND is_closed=1";
    $run_getbranchversion=mysqli_query($con,$fg_getbranchversion);
    $row_getbranchversion=mysqli_fetch_array($run_getbranchversion);
    
      $newversion=$row_getbranchversion['newversion'];
      $branchkey='PL_'.$branch.''.$company_id.'_'.$forecasting_month.'';

      $insert_branchdata="INSERT INTO `ipl_forecasting_branch_data`(forecasting_master_id,forecasting_branchkey,version_no,branch_id,company_id,forecasting_month) VALUES ('$forecasting_id','$branchkey','$newversion','$branch','$company_id','$forecasting_month')";
      $run_insertbranchdata=mysqli_query($con,$insert_branchdata);
    }

    
    
  }

return response()->json([
    'status' => 'success',
    'message' => 'Data saved successfully!',
    'script' => "<script>
                    localStorage.setItem('reloadCreate', 'true');
                    window.close();
                </script>"
]);
   
    // return view('create_inventory_forecasting3',['forecasting_id'=>$forecasting_id,'unique_key'=>$unique_key,'version_no'=>$version_no,'branch_id'=>$branch_id,'company_id'=>$company_id]);
    }


   function SaveFinalForecasting(Request $req)
   {
    
    $company_id=$req->company_id;
    $insertedId=$req->insertedId;
    $versionprevious=$req->versionprevious;
    $company_id=$req->company_id;
    $branch_id=$req->branch_id;
    $forecasting_month=$req->forecasting_month;
    $formula_id=$req->formula_id;
    $formula_det_id=$req->formula_det_id;
    $group_id=$req->group_id;
    $shelf_life=$req->shelf_life;
    $multiply_by=0;
    $sort_by=$req->sort_by;
    $questionids=$req->questionids;
 
 
     $version_no=$versionprevious + 1;
     $unique_key="";
     $getunique=DB::SELECT("SELECT forcasting_unique_key FROM `tbl_forecasting_master` where forecasting_id='$insertedId'");
     $uniquekeycount=0;
     foreach($getunique as $key)
     {
        $unique_key=$key->forcasting_unique_key;
        $uniquekeycount=1;
     }

     //if agr master me data nahi heto insert
     if($uniquekeycount == 0)
     {

        $myuserid=session("adminname");
    date_default_timezone_set("Asia/Karachi");
    $currentdate=date('Y-m-d h:i:s');

    if($versionprevious == 0)
    {
    $formno=0;
    $data_formno=DB::SELECT("SELECT COALESCE(MAX(forcasting_formno), 0) + 1 AS newforcasting FROM tbl_forecasting_master");
    foreach($data_formno as $fmno)
    {
      $formno=$fmno->newforcasting;
    }

    $unique_key="PLAN_".date('Ym')."0".$formno;
    }
    else{

      $fg_gettingkey=DB::SELECT("SELECT forcasting_unique_key,forcasting_formno FROM `tbl_forecasting_master` where company_id='$company_id' AND branch_id='$branch_id' AND forecasting_month='$forecasting_month'");
      foreach($fg_gettingkey as $fkey)
      {
        $unique_key=$fkey->forcasting_unique_key;
        $formno=$fkey->forcasting_formno;
      }

    }

        DB::table('tbl_forecasting_master')
          ->insert([
            'forecasting_id'=>$insertedId,
            'forcasting_unique_key' => $unique_key,
            'forcasting_formno' => $formno,
            'version_no'=>$version_no,
            'formula_id'=>$formula_id,
            'formula_detail_id'=>$formula_det_id,
            'group_id'=>$group_id,
            'company_id'=>$company_id,
            'branch_id'=>$branch_id,
            'shelf_life'=>$shelf_life,
            'multiply_by'=>$multiply_by,
            'post_user'=>$myuserid,
            'post_datetime'=>$currentdate,
            'forecasting_month'=>$forecasting_month,
            'sort_by'=>$sort_by,
            'question_ids'=>$questionids,
          ]);
     }

     $branch_id="";
     $getbranchs=DB::SELECT("SELECT GROUP_CONCAT(branch_id) as branchs FROM `ipl_forecasting_branch_data` where forecasting_master_id='$insertedId'");
     foreach($getbranchs as $br)
     {
        $branch_id=$br->branchs;
     }

     DB::table('tbl_forecasting_master')
        ->where([
        ['forecasting_id', '=', $insertedId],
        ])
        ->update([
            'is_closed' => 1
        ]);

     DB::table('ipl_forecasting_branch_data')
        ->where([
        ['forecasting_master_id', '=', $insertedId],
        ])
        ->update([
            'is_closed' => 1
        ]);

    DB::table('ipl_forecasting_detail')
        ->where([
        ['forecasting_id', '=', $insertedId],
        ])
        ->update([
            'is_closed' => 1
        ]);       


    return view('create_inventory_forecasting3',['forecasting_id'=>$insertedId,'unique_key'=>$unique_key,'version_no'=>$version_no,'branch_id'=>$branch_id,'company_id'=>$company_id]);

   }

     function ForecastingList()
    {
        
    session()->put("titlename","Forecasting List - Inventory Planning Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);

    
    $forecastinglist=DB::SELECT("SELECT * FROM `tbl_forecasting_master` where branch_id in ($branchid2) AND is_closed=1 group by forcasting_unique_key order by forecasting_id desc");


    $branchdata=DB::SELECT("SELECT * FROM `ipl_branch_master` where branch_code in ($branchid2)");

    $companydata=DB::SELECT("SELECT * FROM `ipl_company_master` order by company_title asc");
   

    return view('forecasting_list',['forecastinglist'=>$forecastinglist,'branchdata'=>$branchdata,'companydata'=>$companydata,'branchid2'=>$branchid2]);
    }


    function SearchForecastingList(Request $req)
    {
        
    session()->put("titlename","Forecasting List - Inventory Planning Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);

    $unique_key=$req->unique_key;
    $branch_name=$req->branch_name;
    $branch_name=explode(',', $branch_name);
    $company_name=$req->company_name;
    $for_month=$req->for_month;

    $forecastinglist=DB::table('tbl_forecasting_master');
    $forecastinglist=$forecastinglist->where(function ($q) use ($branch_name) {
    foreach ($branch_name as $branch_id) {
        $q->orWhere('branch_id', 'LIKE', "%$branch_id%");
    }
});
 if($unique_key !="")
    {
      $forecastinglist=$forecastinglist->where([
        ['forcasting_unique_key', '=', $unique_key],
        ]);
    }
    if($company_name !="")
    {
      $forecastinglist=$forecastinglist->where([
        ['company_id', '=', $company_name],
        ]);
    }
    if($for_month !="")
    {
      $forecastinglist=$forecastinglist->where([
        ['forecasting_month', '=', $for_month],
        ]);
    }
    $forecastinglist=$forecastinglist->groupBy('forcasting_unique_key');
    $forecastinglist=$forecastinglist->orderBy('forecasting_id', 'desc');
    $forecastinglist=$forecastinglist->get();
        


  


    $branchdata=DB::SELECT("SELECT * FROM `ipl_branch_master` where branch_code in ($branchid2)");

    $companydata=DB::SELECT("SELECT * FROM `ipl_company_master` order by company_title asc");
   

    return view('forecasting_list',['forecastinglist'=>$forecastinglist,'branchdata'=>$branchdata,'companydata'=>$companydata,'branchid2'=>$branchid2]);
    }


    function ViewForeCasting($fid,$key,$sort)
    {
        
    session()->put("titlename","Forecasting View - Inventory Planning Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);

    if($sort == "branchwise")
    {
    $forecastingmaster=DB::SELECT("SELECT det.branch_row_id,br.branch_name FROM `ipl_forecasting_detail` det inner join ipl_branch_master br on det.branch_row_id=br.branch_code where det.forecasting_id='$fid' group by det.branch_row_id");
    }
    else{
    $forecastingmaster=DB::SELECT("SELECT det.product_code,prod.product_name FROM `ipl_forecasting_detail` det inner join ipl_products prod on det.product_code=prod.product_code where det.forecasting_id='$fid' group by det.product_code");
  
    }

     $forecastingdetail=DB::SELECT("SELECT fm.*,comp.company_title,br.branch_name,formu.formula_name FROM `tbl_forecasting_master` fm inner join ipl_company_master comp on fm.company_id=comp.company_code inner join ipl_branch_master br on fm.branch_id=br.branch_code inner join ipl_formula_master formu on fm.formula_id=formu.formula_id where fm.forecasting_id=$fid");
    



    return view('view_forecasting',['forecastingdetail'=>$forecastingdetail,'fid'=>$fid,'key'=>$key,'sort'=>$sort,'forecastingmaster'=>$forecastingmaster]);
    }

    function ExportForeCasting($fid,$Key,$sort)
    {
      $forecastingdetail=DB::SELECT("SELECT fm.*,comp.company_title,br.branch_name,formu.formula_name FROM `tbl_forecasting_master` fm inner join ipl_company_master comp on fm.company_id=comp.company_code inner join ipl_branch_master br on fm.branch_id=br.branch_code inner join ipl_formula_master formu on fm.formula_id=formu.formula_id where fm.forecasting_id=$fid");

      return view('export_forecasting',['forecastingdetail'=>$forecastingdetail,'fid'=>$fid,'key'=>$Key,'brcode'=>0]);
    }

    function ExportForeCasting2($fid,$Key,$branchid)
    {
      $forecastingdetail=DB::SELECT("SELECT fm.*,comp.company_title,br.branch_name,formu.formula_name FROM `tbl_forecasting_master` fm inner join ipl_company_master comp on fm.company_id=comp.company_code inner join ipl_branch_master br on fm.branch_id=br.branch_code inner join ipl_formula_master formu on fm.formula_id=formu.formula_id where fm.forecasting_id=$fid");

      return view('export_forecasting',['forecastingdetail'=>$forecastingdetail,'fid'=>$fid,'key'=>$Key,'brcode'=>$branchid]);
    }

    function PrintForeCasting($fid,$Key,$sort)
    {
      
    if($sort == "branchwise")
    {
    $forecastingmaster=DB::SELECT("SELECT det.branch_row_id,br.branch_name FROM `ipl_forecasting_detail` det inner join ipl_branch_master br on det.branch_row_id=br.branch_code where det.forecasting_id='$fid' group by det.branch_row_id");
    }
    else{
    $forecastingmaster=DB::SELECT("SELECT det.product_code,prod.product_name FROM `ipl_forecasting_detail` det inner join ipl_products prod on det.product_code=prod.product_code where det.forecasting_id='$fid' group by det.product_code");
  
    }

      $forecastingdetail=DB::SELECT("SELECT fm.*,comp.company_title,br.branch_name,formu.formula_name FROM `tbl_forecasting_master` fm inner join ipl_company_master comp on fm.company_id=comp.company_code inner join ipl_branch_master br on fm.branch_id=br.branch_code inner join ipl_formula_master formu on fm.formula_id=formu.formula_id where fm.forecasting_id=$fid");

      return view('print_forecasting',['forecastingdetail'=>$forecastingdetail,'fid'=>$fid,'key'=>$Key,'sort'=>$sort,'forecastingmaster'=>$forecastingmaster]);
    }


   function BranchWiseForecastingList()
   {
      session()->put("titlename","Branch Wise Forecasting List - Inventory Planning Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);

    $companydata=DB::SELECT("SELECT brdata.company_id,brdata.forecasting_month,comp.company_title FROM `ipl_forecasting_branch_data` brdata inner join ipl_company_master comp on brdata.company_id=comp.company_code where brdata.is_closed=1 AND brdata.branch_id in ($branchid2) group by brdata.company_id,brdata.forecasting_month order by brdata.company_id,brdata.forecasting_month asc");

    $companymaster=DB::SELECT("SELECT * FROM `ipl_company_master` order by company_title asc");

   return view('branchwise_forecasting_list',['companydata'=>$companydata,'companymaster'=>$companymaster]);

   }

   function SearchBranchWiseForecastinglist(Request $req)
   {
      session()->put("titlename","Branch Wise Forecasting List - Inventory Planning Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);
    $company_name=$req->company_name;
    $for_month=$req->for_month;
    
    if($company_name !="" && $for_month !="")
    {
        $companydata=DB::SELECT("SELECT brdata.company_id,brdata.forecasting_month,comp.company_title FROM `ipl_forecasting_branch_data` brdata inner join ipl_company_master comp on brdata.company_id=comp.company_code where brdata.company_id='$company_name' AND brdata.forecasting_month='$for_month' group by brdata.company_id,brdata.forecasting_month order by brdata.company_id,brdata.forecasting_month asc");
    }
    else if($company_name !="" && $for_month =="")
    {
        $companydata=DB::SELECT("SELECT brdata.company_id,brdata.forecasting_month,comp.company_title FROM `ipl_forecasting_branch_data` brdata inner join ipl_company_master comp on brdata.company_id=comp.company_code where brdata.company_id='$company_name' group by brdata.company_id,brdata.forecasting_month order by brdata.company_id,brdata.forecasting_month asc");
    }
    else if($company_name =="" && $for_month !="")
    {
        $companydata=DB::SELECT("SELECT brdata.company_id,brdata.forecasting_month,comp.company_title FROM `ipl_forecasting_branch_data` brdata inner join ipl_company_master comp on brdata.company_id=comp.company_code where brdata.forecasting_month='$for_month' group by brdata.company_id,brdata.forecasting_month order by brdata.company_id,brdata.forecasting_month asc");
    }

      $companymaster=DB::SELECT("SELECT * FROM `ipl_company_master` order by company_title asc");
   

   return view('branchwise_forecasting_list',['companydata'=>$companydata,'companymaster'=>$companymaster]);

   }

   function View_BranchForecasting($fid,$branchid,$Key,$version)
   {
         session()->put("titlename","Forecasting View - Inventory Planning Admin Panel - Premier Group Of Companies");


     $forecastingmaster=DB::SELECT("SELECT det.branch_row_id,br.branch_name FROM `ipl_forecasting_detail` det inner join ipl_branch_master br on det.branch_row_id=br.branch_code where det.forecasting_id='$fid' AND det.branch_row_id=$branchid group by det.branch_row_id");

     $forecastingdetail=DB::SELECT("SELECT fm.*,comp.company_title,br.branch_name,formu.formula_name FROM `tbl_forecasting_master` fm inner join ipl_company_master comp on fm.company_id=comp.company_code inner join ipl_branch_master br on fm.branch_id=br.branch_code inner join ipl_formula_master formu on fm.formula_id=formu.formula_id where fm.forecasting_id=$fid");

        return view('view_branch_forecasting',['forecastingdetail'=>$forecastingdetail,'fid'=>$fid,'key'=>$Key,'version'=>$version,'forecastingmaster'=>$forecastingmaster,'branchid'=>$branchid]);
    
   }


   function PendingClosingForecasting()
   {
      session()->put("titlename","Pending Closing Forecasting List - Inventory Planning Admin Panel - Premier Group Of Companies");

    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);

    $companydata=DB::SELECT("SELECT brdata.company_id,brdata.forecasting_month,comp.company_title FROM `ipl_forecasting_branch_data` brdata inner join ipl_company_master comp on brdata.company_id=comp.company_code group by brdata.company_id,brdata.forecasting_month order by brdata.company_id,brdata.forecasting_month asc");

    $companymaster=DB::SELECT("SELECT * FROM `ipl_company_master` order by company_title asc");

    $branchmaster=DB::SELECT("SELECT * FROM `ipl_branch_master` where branch_code in ($branchid2) order by branch_code asc");

   return view('pending_closing_forecastinglist',['companydata'=>$companydata,'companymaster'=>$companymaster]);

   }
   

   function GetBranchForecastingCheck(Request $req)
    {

    $forecastingid = $req->insertedId;
    $branchCodes = $req->branchCodes;
    $versionprevious=$req->versionprevious;
    $version_no=$versionprevious + 1;
    $company_id=$req->company_id;
    $forecasting_month=$req->forecasting_month;
    $group_id=$req->group_id;
    $existingBranches = [];
     
    // // Loop through the branch codes and check if they already exist in the database
    foreach ($branchCodes as $brcode) {
        $index=0;
        $user=DB::SELECT("SELECT * FROM `ipl_forecasting_branch_data` where forecasting_master_id='$forecastingid' AND branch_id='$brcode'");
        foreach($user as $us)
        {
            $index=1;
        }



        //previous me dekhna he ke bana hua to nahi 
        
        //check already hen to kitny product isky 
        $totalproducts=0;
        if($group_id !="")
        {
         $productdata=DB::SELECT("SELECT ifnull(count(prod.product_code),0) as totalproducts FROM `ipl_group_mapping` grp inner join ipl_products prod on grp.product_code=prod.product_code where grp.group_id='$group_id' and prod.status=1 order by prod.product_name asc");
        }
        else{
         $productdata=DB::SELECT("SELECT ifnull(count(product_code),0) as totalproducts FROM `ipl_products` where company_code='$company_id' AND status=1 order by product_name asc");
        }
        foreach($productdata as $prd)
        {
         $totalproducts=$prd->totalproducts;
        }

        $user2=DB::SELECT("SELECT ifnull(count(det.product_code),0) as alreadyproduct,brdata.forecasting_master_id,brdata.forecasting_branch_id FROM `ipl_forecasting_branch_data` brdata inner join ipl_forecasting_detail det on brdata.forecasting_master_id=det.forecasting_id AND brdata.branch_id=det.branch_row_id where brdata.company_id='$company_id' AND brdata.branch_id='$brcode' AND brdata.forecasting_month='$forecasting_month' AND brdata.is_closed=0 AND brdata.version_no='$version_no' order by brdata.forecasting_branch_id desc limit 1");
        foreach($user2 as $us2)
        {
            
            $alreadyproduct=$us2->alreadyproduct;
            if($alreadyproduct == $totalproducts){
            $index=1;
            $forecasting_master_id=$us2->forecasting_master_id;
            $forecasting_branch_id=$us2->forecasting_branch_id;

            //branch wala table update krni forecastingid 
        DB::table('ipl_forecasting_branch_data')
        ->where([
        ['forecasting_branch_id', '=', $forecasting_branch_id],
        
        ])
        ->update([
            'forecasting_master_id' => $forecastingid
        ]);

        //detail waly table ki entry update krni he
        DB::table('ipl_forecasting_detail')
        ->where([
        ['forecasting_id', '=', $forecasting_master_id],
        ['is_closed', '=', 0],
        ['branch_row_id', '=', $brcode],
        ])
        ->update([
            'forecasting_id' => $forecastingid
        ]);

        }

         

        }

        // If the record exists, add the branch code to the result array
        if ($index !=0) {
            $existingBranches[] = $brcode;
        }
    }

    // Return the array of branch codes that already have records
    return response()->json($existingBranches);

        
    }


     function GetBranchForecastingCheck2(Request $req)
    {

    $forecastingid = $req->insertedId;
    $productCodes = $req->productCodes;
    $versionprevious=$req->versionprevious;
    $version_no=$versionprevious + 1;
    $company_id=$req->company_id;
    $forecasting_month=$req->forecasting_month;
    $branch_id=$req->branch_id;
    $count=$req->count;
    $existingBranches = [];

    
    
    $branch_array = explode(',', $branch_id);
    $branch_count = count($branch_array);
    $totalproductscount=0;
    // // Loop through the branch codes and check if they already exist in the database
    foreach ($productCodes as $prdcode) {
        $index=0;$totalproductscount=$totalproductscount + 1;
        $user=DB::SELECT("SELECT * FROM `ipl_forecasting_detail` where forecasting_id='$forecastingid' AND product_code='$prdcode'");
        foreach($user as $us)
        {
            $index=1;
        }

        //previous agr bna hua he to 
        
        $total_records=0;$old_forecasting_id=0;
        $user2=DB::SELECT("SELECT det.forecasting_id, ifnull(COUNT(*),0) AS total_records FROM ipl_forecasting_detail det INNER JOIN ipl_forecasting_branch_data br ON det.forecasting_id = br.forecasting_master_id AND det.branch_row_id = br.branch_id WHERE det.product_code = '$prdcode' AND det.is_closed = 0 AND det.branch_row_id IN ($branch_id) AND br.forecasting_month = '$forecasting_month' AND br.version_no = '$version_no' GROUP BY det.forecasting_id ORDER BY det.forecasting_id DESC LIMIT 1");
        foreach($user2 as $us2)
        {
            $total_records=$us2->total_records;
            $old_forecasting_id=$us2->forecasting_id;
        }

        if($branch_count == $total_records)
        {
            $index=1;
        //branch wala table update krni forecastingid 
        // DB::table('ipl_forecasting_branch_data')
        // ->where([
        // ['version_no', '=', $version_no],
        // ['is_closed', '=', 0],
        // ['forecasting_master_id', '=', $old_forecasting_id],
        
        // ])
        // ->whereIn('branch_id', explode(',', $branch_id))
        // ->update([
        //     'forecasting_master_id' => $forecastingid
        // ]);

        //detail waly table ki entry update krni he
       
        DB::table('ipl_forecasting_detail')
        ->where([
        ['forecasting_id', '=', $old_forecasting_id],
        ['is_closed', '=', 0],
        ['product_code', '=', $prdcode],
        ])
        ->whereIn('branch_row_id', explode(',', $branch_id))
        ->update([
            'forecasting_id' => $forecastingid
        ]);



        }

        // If the record exists, add the branch code to the result array
        if ($index !=0) {
            $existingBranches[] = $prdcode;
        }
    }

    $existingbranchcount = count($existingBranches);
 
    if($existingbranchcount == $totalproductscount)
    {
             if($count ==1){
        DB::table('ipl_forecasting_branch_data')
        ->where([
        ['version_no', '=', $version_no],
        ['is_closed', '=', 0],
        ['company_id', '=', $company_id],
        
        ])
        ->whereIn('branch_id', explode(',', $branch_id))
        ->update([
            'forecasting_master_id' => $forecastingid
        ]);
       }
    }
    // Return the array of branch codes that already have records
    return response()->json($existingBranches);

        
    }

   

   


   
    
    

    



    

    

    













    

}    