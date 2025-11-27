<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Admin;
use DB;

class LoginController extends Controller
{


	public function login(){



		return view('template/frontend/userdashboard/pages/login/login');

	}

	public function login_submit(Request $request)
	{
    	//print_r($request->email);
		$this->validate($request,
			[
				'email'=>'required',
				'password'=>'required',

			]);

        $credentials = $request->only('email', 'password');


        $login=Auth::guard('web')->attempt($credentials);

		if($login)
		{

            return redirect()->intended('super_admin');

		}

		else
		{
			return redirect()->back()->with('message','Email or password is not correct!!');
		}

	}

	public function change_password(Request $request)
	{

		$this->validate($request,
			[
				'current_password'=>'required',
				'new_password'=>'required',
				'cnew_password'=>'required',

			]);


		if($request->input('new_password')==$request->input('cnew_password'))
		{
			if (Hash::check($request->input('current_password'), Auth::user()->password) == false)
			{
				return redirect()->back()->with('message','invalid current password');
			}

			$user = Auth::user();
			$user->password = Hash::make($request->input('new_password'));
			$user->save();
			return redirect()->back()->with('message','password updated successfully');

		}
		else
		{
			return redirect()->back()->with('message','confirm password password does not match!!');
		}


	}

	public function logout()
	{
		Auth::guard('web')->logout();
		return redirect('login_admin');


	}
	public function signup(){
		return view('template/frontend/pages/signup');

	}


}
