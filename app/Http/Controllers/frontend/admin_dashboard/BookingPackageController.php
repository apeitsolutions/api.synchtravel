<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\booking_users;
use App\Models\Tour;
use App\Models\country;
use DB;
use Auth;

class BookingPackageController extends Controller
{

public function book_package()
{
   $umrah_packages=UmrahPackage::orderBy('id','DESC')->get();
   $tours=Tour::orderBy('id','DESC')->get();
   $countries=DB::table('countries')->get();
//   print_r($umrah_packages);die();


   return view('template/frontend/userdashboard/pages/offline_bookings/book_package',compact('umrah_packages','tours','countries'));
}
public function get_booking_package(Request $request,$id)
{
   $umrah_packages=UmrahPackage::find($id);
 
//   print_r($umrah_packages);die();
  
   return response()->json(['umrah_packages'=>$umrah_packages]);


 
}

public function get_visa_type(Request $request)
{
    $visa_data =DB::table('visa_types')->where('id',$request->id)->get();
  
 
//   print_r($umrah_packages);die();
  
   return response()->json(['visa_data'=>$visa_data]);


 
}
public function get_booking_package_tour(Request $request,$title)
{
 
   $tours_packages=Tour::where('title',$title)->first();
//   print_r($umrah_packages);die();
  
   return response()->json(['tours_packages'=>$tours_packages]);


 
}
public function get_cites_book(Request $request,$country_id)
{
 
   $all_cities=DB::table('cities')->where('country_id',$country_id)->get();
//   print_r($umrah_packages);die();
  
   return response()->json(['all_cities'=>$all_cities]);


 
}
public function submit_book_package(Request $request)
{
 
  $booking_users=new booking_users();
  $booking_users->fname=$request->fname;
  $booking_users->lname=$request->lname;
  $booking_users->email=$request->email;
  $booking_users->phone_no=$request->phone_no;
  $booking_users->gender=$request->gender;
  $booking_users->passport_no=$request->passport_no;
  $booking_users->passport_expire=$request->passport_expire;
  $booking_users->address=$request->address;
  $booking_users->country=$request->country;
  $booking_users->city=$request->city;
  
//   $booking_users->parent_token=$request->parent_token;
  $booking_users->save();
  return redirect()->back()->with('message','Booking Created SuccessFul');
}


}
