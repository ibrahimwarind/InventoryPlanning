<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    //
    function AddUser()
    {
    	session()->put("titlename","Add User - Sales Booster Admin Panel - Premier Group Of Companies");
    	$branchdata=DB::table('ipl_branch_master')
        
        ->get();

    	return view('add_user',['branchdata'=>$branchdata,'data'=>'']);


    }
    

      function SaveUser(Request $req)
    {

    	$u_name=$req->input("u_name");
    	$u_password=$req->input("u_password");
    	$branchcode=$req->input('branch_name',[]);
        $branchcode = implode(',', array_map('intval', $branchcode));
    	

    	$count=DB::table('admin')
        ->where([
        ['admin_name','=',$u_name],
        ])
        ->count();
    
        if($count == 0)
        {
        	date_default_timezone_set("Asia/Karachi");
        $date = date('Y-m-d H:i:s');
 
        	
      
        DB::table('admin')
        ->insert([
            'admin_name' => $u_name,
            'admin_password' => $u_password,
            'admin_email'=>' ',
            'admin_type'=>'user',
            'group_id'=>1,
            'status'=>'Yes',
            'phone'=>' ',
            'branch_id'=>$branchcode,
            'admin_last_login'=>$date,
        ]);
        


            return redirect('adduser');
        }
        else
        {
        $branchdata=DB::table('ipl_branch_master')
        ->get();
        
        	return view('add_user',['data'=>'Login Name Already Exist','branchdata'=>$branchdata]);

        }


    	//session set krny ke lye
    

    }

      function UserList()
    {
        
    session()->put("titlename","User List - Uraan B2B Online Store Admin Panel - Premier Group Of Companies");
    $totaluser=DB::table('admin')
    // ->where([
    //     ['admin_type','!=',"DsfSupervisor"],
    //     ])
    ->count();


    $userdata = DB::table('admin')
    // ->where([
    //     ['admin_type','!=',"DsfSupervisor"],
    //     ])
    ->paginate(500);

    return view('userlist',['totaluser'=>$totaluser,'userdata'=>$userdata]);
    }

     function AssignRole($uid)
    {
        
    session()->put("titlename","Assign Role - Uraan B2B Online Store Admin Panel - Premier Group Of Companies");
    $submenu=DB::table('sb_sub_menu')
    ->get();

    return view('assign_role',['submenu'=>$submenu,'uid'=>$uid]);
    }

     function AssignRole2(Request $req)
    {
       $uid=$req->uid;
        DB::table('sb_user_rights')
        ->where('user_id', $uid)
        ->delete();
       foreach($req->menuid as $menuid)
       {

        $con=mysqli_connect("127.0.0.1","root","","db_ipl");
       	 $fg_subid="select * from sb_sub_menu where sub_id='$menuid'";
       	 $run_subid=mysqli_query($con,$fg_subid);
       	 $row_subid=mysqli_fetch_array($run_subid);
       	 $menu_id=$row_subid['menu_id'];

       	 DB::table('sb_user_rights')
        ->insert([
            'user_id' => $uid,
            'sub_menu_id' => $menuid,
            'main_menu_id'=>$menu_id,
            
        ]);

       } 

  

    return redirect('userlist');
    }

    function DeleteUser($id)
    {
       DB::table('admin')
        ->where('admin_id', $id)
        ->delete();
    	return redirect('userlist');
    }

     function EditUser($id)
    {
        session()->put("titlename","Edit User - Uraan B2B Online Store Admin Panel - Premier Group Of Companies");

    $userdetail = DB::table('admin')
    ->where([
    ['admin_id', '=', $id],
    ])
    ->get();

   $branchdata=DB::table('ipl_branch_master')
   ->get();

   return view('edituser',['userdetail'=>$userdetail,'branchdata'=>$branchdata]);
   
    }

     function UpdateUser(Request $req)
    {
        $admin_id=$req->input("admin_id");
        $u_name=$req->input("u_name");
        $u_password=$req->input("u_password");
        $branchcode=$req->input('branch_name',[]);
        $branchcode = implode(',', array_map('intval', $branchcode));
        

        DB::table('admin')
        ->where('admin_id', $admin_id)
        ->update([
            'admin_name' => $u_name,
            'admin_password' => $u_password,
            'branch_id'=>$branchcode,
            ]);
      



        return redirect('/userlist');


    }

   
    function AddSupervisor()
    {
    	session()->put("titlename","Add Supervisor - Sales Booster Admin Panel - Premier Group Of Companies");
        if(session()->has("adminname"))
        {
        $branchid=session('branchid');
        $branchid2=session('branchid');
        $branchid=explode(',', $branchid);

        $brcount=count($branchid);
        $branchdata = DB::table('sb_branch_master')
        ->whereIn('branch_code',$branchid)
        ->get();
    	

    	return view('add_supervisor',['branchdata'=>$branchdata,'data'=>'']);

    }
    else
    {
        session()->put("titlename","SalesBooster - Admin Login - Premier Group Of Companies");
        return view('login',['data'=>'']);
    }


    }

    
    function GetBranchBusinessLine(Request $req)
    {

    
         $busdata=DB::SELECT("SELECT supply_groupid,supply_groupname FROM `sb_business_line` where branch_code='$req->id' group by supply_groupid");

         return response()->json($busdata);

    	
    }
    function SupervisorAddGroup(Request $req)
    {
        $u_code=$req->u_code;
        $u_branch=$req->u_branch;
        $bus_line=$req->bus_line;
        date_default_timezone_set("Asia/Karachi");
      $curr_date = date('y-m-d h:i:s');

        $count=DB::table('sb_supervisor_businessgroup')
        ->where([
        ['supervisor_code','=',$u_code],
        ['branch_id','=',$u_branch],
        ['group_id','=',$bus_line],
        ])
        ->count();

        if($count == 0)
        {
        
        DB::table('sb_supervisor_businessgroup')
        ->insert([
            'supervisor_code' => $u_code,
            'branch_id' => $u_branch,
            'group_id'=>$bus_line,
            'add_datetime'=>$curr_date,
        ]);

        return response()->json("Business Line Added");
           
        }
        else{
            return response()->json("Business Line Already Added");
        }
    
         $busdata=DB::SELECT("SELECT supply_groupid,supply_groupname FROM `sb_business_line` where branch_code='$req->id' group by supply_groupid");

        

    	
    }

    function GetSupervisorGroup(Request $req)
    {

    
         $busdata=DB::SELECT("SELECT sp.auto_id,sb.supply_groupname FROM `sb_supervisor_businessgroup` sp,sb_business_line sb where sp.group_id=sb.supply_groupid AND sp.supervisor_code='$req->u_code' AND sp.branch_id='$req->u_branch'  AND sb.branch_code='$req->u_branch' group by sb.supply_groupid");

         return response()->json($busdata);

    	
    }
    

    function SaveSupervisor(Request $req)
    {

    	$u_code=$req->input("u_code");
    	$u_name=$req->input("u_name");
    	$l_name=$req->input("l_name");
    	$u_password=$req->input("u_password");
    	$u_branch=$req->input("u_branch");
    	

    	$count=DB::table('admin')
        ->where([
        ['admin_name','=',$l_name],
        ['branch_id','=',$u_branch],
        ['admin_type','=',"DsfSupervisor"],
        ])
        ->count();
    
        // return view('index',['data'=>$count]);
        if($count == 0)
        {
        date_default_timezone_set("Asia/Karachi");
        $date = date('Y-m-d H:i:s');

        DB::table('admin')
        ->insert([
            'admin_name' => $l_name,
            'admin_password' => $u_password,
            'admin_email'=>"",
            'admin_type'=>"DsfSupervisor",
            'group_id'=>1,
            'status'=>'Yes',
            'phone'=>"",
            'branch_id'=>$u_branch,
            'admin_last_login'=>$date,
            'user_name'=>$u_name,
            'user_code'=>$u_code,
        ]);
        
        $get_id=DB::SELECT("SELECT admin_id FROM `admin` where branch_id='$u_branch' AND admin_type='DsfSupervisor' AND user_code='$u_code'");
        foreach($get_id as $get)
        {
            $admin_id=$get->admin_id;

            DB::table('sb_user_rights')
            ->insert([
            'user_id' => $admin_id,
            'sub_menu_id' =>76,
            'main_menu_id'=>22,
            ]);

            DB::table('sb_user_rights')
            ->insert([
            'user_id' => $admin_id,
            'sub_menu_id' =>77,
            'main_menu_id'=>22,
            ]);

            DB::table('sb_user_rights')
            ->insert([
            'user_id' => $admin_id,
            'sub_menu_id' =>81,
            'main_menu_id'=>23,
            ]);

        }

            return redirect('addsupervisor');
        }
        else
        {
            $branchid=session('branchid');
            $branchid2=session('branchid');
            $branchid=explode(',', $branchid);
    
            $brcount=count($branchid);
            $branchdata = DB::table('sb_branch_master')
            ->whereIn('branch_code',$branchid)
            ->get();
            
    
            return view('add_supervisor',['branchdata'=>$branchdata,'data'=>'Login Name of This Branch Is Already Exist']);

        }

  

    }
    
    function SupervisorList()
    {
        
    session()->put("titlename","Supervisor List - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');
    $branchid2=session('branchid');
    $branchid=explode(',', $branchid);

    $totaluser=DB::table('admin')
    ->whereIn('branch_id',$branchid)
    ->where('admin_type', "DsfSupervisor")
    ->count();


    $userdata = DB::table('admin')
    ->whereIn('branch_id',$branchid)
    ->where('admin_type', "DsfSupervisor")
    ->paginate(500);

    return view('supervisorlist',['totaluser'=>$totaluser,'userdata'=>$userdata]);
    }

    function EditSupervisor($id)
    {
    session()->put("titlename","Edit Supervisor - Sales Booster Admin Panel - Premier Group Of Companies");

    $userdetail = DB::table('admin')
    ->where([
    ['admin_id', '=', $id],
    ])
    ->get();

   $branchdata=DB::table('sb_branch_master')
   ->get();

   return view('editsupervisor',['userdetail'=>$userdetail,'branchdata'=>$branchdata,'data'=>'']);
   
    }
    

    function UpdateSupervisor(Request $req)
    {
        $u_id=$req->input("u_id");
        $u_code=$req->input("u_code");
    	$u_name=$req->input("u_name");
    	$l_name=$req->input("l_name");
    	$u_password=$req->input("u_password");
    	$u_branch=$req->input("u_branch");

    	
        DB::table('admin')
        ->where('admin_id', $u_id)
        ->update([
            'admin_name' => $l_name,
            'admin_password' => $u_password,
            'branch_id'=>$u_branch,
            'user_name'=>$u_name,
            'user_code'=>$u_code,
            ]);
      


        return redirect('/supervisorlist');


    }

    
    
        
    function SafOpenRequest()
    {
        if(session()->has("adminname"))
    	{
     //get updated saf from api in over dsf_api_data

    session()->put("titlename","Saf Open Request - Sales Booster Admin Panel - Premier Group Of Companies");
    $branchid=session('branchid');$branchname=session('branchname');
    $usercode=session('usercode');
    
    $approvaldata=DB::SELECT("SELECT * FROM `sb_safopen_request`  where approved_status=0 AND branch_code='$branchid' AND dsf_code in (SELECT dsf_code FROM `sb_dsf_businessline` where branch_code='$branchid' AND supply_groupid='$usercode')  order by request_id desc");

    
    return view('safopen_request',['approvaldata'=>$approvaldata,'usercode'=>$usercode,'branchid'=>$branchid]);
        }    
    else {
        session()->put("titlename","Admin Login - Sales Booster Admin Panel - Premier Group Of Companies");
        return view('login',['data'=>'']);
    }

  }

  function SafOpenStatusChange(Request $req)
  {
      $id=$req->id;
      $status=$req->status;
     
      $myuserid=session("adminname");
      date_default_timezone_set("Asia/Karachi");
      $currentdate=date('Y-m-d h:i:s a');

     
      DB::table('sb_safopen_request')
      ->where('request_id', $id)
      ->update([
          'approved_status' => $status,
          'approved_userid' => $myuserid,
          'approved_datetime' => $currentdate
      ]);

      return response()->json(true);
  }


  function ApprovedSafOpen()
  {
      if(session()->has("adminname"))
      {
   //get updated saf from api in over dsf_api_data

  session()->put("titlename","Approved Saf Open Request - Sales Booster Admin Panel - Premier Group Of Companies");
  $branchid=session('branchid');$branchname=session('branchname');
  $usercode=session('usercode');
  
  $approvaldata=DB::SELECT("SELECT * FROM `sb_safopen_request`  where approved_status=1 AND branch_code='$branchid' AND dsf_code in (SELECT dsf_code FROM `sb_dsf_businessline` where branch_code='$branchid' AND supply_groupid='$usercode')  order by request_id desc");

  
  return view('approved_safopen_request',['approvaldata'=>$approvaldata]);
      }    
  else {
      session()->put("titlename","Admin Login - Sales Booster Admin Panel - Premier Group Of Companies");
      return view('login',['data'=>'']);
  }

}

  
  
    
    
}
