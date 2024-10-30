<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    //

    function Login()
    {
    	if(session()->has("adminname"))
    	{
          return redirect('index');
    	}
    	else
    	{
            session()->put("titlename","Inventory Planning - Admin Login - Premier Group Of Companies");
    		return view('login',['data'=>'']);
    	}
    	
    }

    function LoginData(Request $req)
    {
    	$req ->validate([
    		'uname'=>'required',
    		'upass'=>'required',

    	]);
    	$usernamedata=$req->input("uname");
    	$userpassdata=$req->input("upass");

    	$count=DB::table('admin')
        ->where([
        ['admin_name','=',$usernamedata],
        ['admin_password','=',$userpassdata],
        ])
        ->count();
    
        // return view('index',['data'=>$count]);
        if($count != 0)
        {
        
        $data=DB::table('admin')
        ->where([
        ['admin_name','=',$usernamedata],
        ['admin_password','=',$userpassdata],
        ])
        ->get();

            $useremail=$data[0]->admin_email;
            $branchid=$data[0]->branch_id;
            $phoneno=$data[0]->phone;
            $admintype=$data[0]->admin_type;
            $adminid=$data[0]->admin_id;

            $brdata=DB::table('ipl_branch_master')
            ->where([
            ['branch_code','=',$branchid],
            ])
            ->get();
            $branchname="";
            foreach($brdata as $br)
            {
                $branchname=$br->branch_name;
            }


 
             $req->session()->put("adminname",$usernamedata);
             $req->session()->put("adminemail",$useremail); 
             $req->session()->put("branchid",$branchid);
             $req->session()->put("branchname",$branchname); 
             $req->session()->put("phoneno",$phoneno); 
             $req->session()->put("admintype",$admintype); 
             $req->session()->put("adminid",$adminid); 
      
        date_default_timezone_set("Asia/Karachi");
        $date = date('Y-m-d H:i:s');
        DB::table('admin')
        ->where('admin_name', $usernamedata)
        ->update([
            'admin_last_login' => $date
        ]);

            return redirect('index');
            
        }
        else
        {
        	return view('login',['data'=>'Login Name or Password is Incorrect']);

        }


    	//session set krny ke lye
    

    }

   function ChangePassword()
    {
    	if(session()->has("adminname"))
    	{
             session()->put("titlename","Change Password - Uraan B2B Online Store Admin Panel - Premier Group Of Companies");
           return view('password');
    	}
    	else
    	{
             session()->put("titlename","Admin Login - Uraan B2B Online Store Admin Panel - Premier Group Of Companies");
    		return view('login',['data'=>'']);
    	}

    	
    }

   function UpdatePassword(Request $req)
   {
   	if(session()->has("adminname"))
    	{
         $req ->validate([
    		'upass'=>'required | min:8',
    		

    	]);

        DB::table('admin')
        ->where('admin_name', $req->uname)
        ->update([
            'admin_password' => $req->upass
        ]);

        session()->put("titlename","Dashboard - Inventory Planning Admin Panel - Premier Group Of Companies");
        return redirect('index');


    	}
    	else
    	{
            session()->put("titlename","Admin Login - Inventory Planning Admin Panel - Premier Group Of Companies");
    		return view('login',['data'=>'']);
    	}
   }





    function Logout()
    {
    	if(session()->has("adminname"))
    	{
    		session()->pull("adminname",null);
    		session()->pull("adminemail",null);
            session()->pull("branchid",null);
            session()->pull("phoneno",null);
            session()->pull("admintype",null);

        session()->put("titlename","Admin Login - Uraan B2B Online Store Admin Panel - Premier Group Of Companies");
    		return redirect('login');
    	}
    	else
    	{
      session()->put("titlename","Admin Login - Uraan B2B Online Store Admin Panel - Premier Group Of Companies");
    	return redirect('login');	
    	}
    }

   


  
}
