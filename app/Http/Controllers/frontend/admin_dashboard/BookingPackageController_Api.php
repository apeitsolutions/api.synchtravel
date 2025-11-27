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

class BookingPackageController_Api extends Controller
{

public function book_package(Request $request)
{
    $customer_id=$request->customer_id;
   $umrah_packages=UmrahPackage::where('customer_id',$customer_id)->get();
   $tours=Tour::where('customer_id',$customer_id)->get();
   $countries=DB::table('countries')->get();
//   print_r($umrah_packages);die();


   return response()->json(['umrah_packages'=>$umrah_packages,'tours'=>$tours,'countries'=>$countries]);
}
public function get_booking_package(Request $request)
{
    
    $id=$request->id;
   $umrah_packages=UmrahPackage::find($id);

   return response()->json(['umrah_packages'=>$umrah_packages]);


 
}
public function get_booking_package_tour(Request $request)
{
  $title=$request->title;
   $tours_packages=Tour::where('title',$title)->first();
//   print_r($umrah_packages);die();
  
   return response()->json(['tours_packages'=>$tours_packages]);


 
}
public function get_cites_book(Request $request)
{
  $country_id=$request->country_id;
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
  
  $booking_users->parent_token=$request->customer_id;
  $booking_users->save();
  return response()->json(['booking_users'=>$booking_users]);
}


}
