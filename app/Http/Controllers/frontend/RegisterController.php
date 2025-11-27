<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;

use App\User;

class RegisterController extends Controller
{




	public function signup(){




		return view('template/frontend/pages/signup');







	}

	public function register(Request $request)
		{  
			
		  $this->validate($request,
		 	['name'=>'required',
             'email'=>'required',
             'phone'=>'required',
             'password'=>'required',
             'cpassword'=>'required',
			 
		 	]);


		 $user=new User();
		 $user->name=$request->input('name');
		 $user->email=$request->input('email');
		  $user->phone=$request->input('phone');

		 if($request->input('password')==$request->input('cpassword'))
		 {
		 $user->password=Hash::make ($request->input('password'));
		 }
		 else
		 {
			return redirect()->back()->with('message','password does not match!!');
		 }
		 $user->save();

		 return view('template/frontend/index');
     
		 
		// $login= $request->only('email','password');
		//   if(Auth::attempt($login))
		//   {
		// 	  return view('template/frontend/index');
		//   }
		}	
    




































}
