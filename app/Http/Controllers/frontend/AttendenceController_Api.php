<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Booking;
use App\Mail\SendMailPassword;
use App\Models\Employee;
use App\Models\Task;
use App\Models\TaskDetail;
use App\Models\LeadPassenger;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Stevebauman\Location\Facades\Location;
use Auth;

class AttendenceController_Api extends Controller
{
    public function attendence(Request $request){
        DB::beginTransaction();
        try {
            $customer_id    = $request->customer_id;
            $attendance     = DB::table('attendances')->where('customer_id',$customer_id)->get();
            $admin_employee = DB::table('admin_employee')->where('customer_id',$customer_id)->get();
            return response()->json(['message'=>'success','attendance'=>$attendance,'admin_employee'=>$admin_employee]);
        } catch (\Exception $e) {
            DB::rollback();
            echo $e;
        }
    }
    
    public function store(Request $request){
        $attendance = new Attendance();
        $attendance->customer_id = $request->id;
        $attendance->employee_id = $request->employee_id;
        $attendance->employee_name = $request->employee_name;
        $attendance->employee_date = $request->employee_date;
        $attendance->time_in = $request->time_in;
        $attendance->time_out = $request->time_out;
        $attendance->save();
        return response()->json(['message'=>'success','attendance'=>$attendance]);
    }
    
    public function edit(Request $request){
        $customer_id=$request->id;
        $id=$request->ids;
        $attendance= \DB::table('attendances')->where('id',$id)->first();
        $admin_employee=\DB::table('admin_employee')->where('customer_id',$customer_id)->get();
        return response()->json(['message'=>'success','attendance'=>$attendance,'admin_employee'=>$admin_employee]);
    }
    
    public function update(Request $request){
        $id=$request->ids;
        $attendance=Attendance::find($id);
        if($attendance)
        {
            $attendance->customer_id = $request->id;
            $attendance->employee_id = $request->employee_id;
            $attendance->employee_name = $request->employee_name;
            $attendance->employee_date = $request->employee_date;
            $attendance->time_in = $request->time_in;
            $attendance->time_out = $request->time_out;
            $attendance->save();
           
           return response()->json(['message'=>'success','attendance'=>$attendance]);
        }

    }
    
    public function delete(Request $request){
        $id=$request->ids;
        $attendance=Attendance::find($id);
        $attendance->delete();
        return response()->json(['message'=>'success','attendance'=>$attendance]);
    }
    
    public function assign_mandob(Request $request,$id){
        $date=date('Y-m-d');
$year = date('Y') . '-12-31';
$b2c_visa_not_applied = \DB::table('dow_b2c_laravel.bookings')->
        whereDate('checkin', [$date,$year])->where('visa_applied','!=','1')->get();
        $data_b2b = \DB::table('dow_new_b2b_laravel.bookings')->where('hotel_makkah_booking_status','=','cancelled')->orwhere('hotel_madina_booking_status','=','cancelled')->orwhere('transfer_booking_status','=','cancelled')->where('id','DESC')->limit('5')->get();

$data_b2c = \DB::table('dow_b2c_laravel.bookings')->where('hotel_makkah_booking_status','=','cancelled')->orwhere('hotel_madina_booking_status','=','cancelled')->orwhere('transfer_booking_status','=','cancelled')->orwhere('ground_service_booking_status','=','cancelled')->where('id','DESC')->limit('5')->get();

        $employees= Employee::all();
        $bookings=Employee::find($id);
        $employees_1= Employee::all();
          $book=Booking::find($id);
        $employees_2= Employee::all();
        $employees_3= Employee::all();
        $employees1= Employee::all();
        $get_address = \DB::table('dow_new_b2b_laravel.mondoob__save__locations')->get();
       
         $clientIP = request()->ip();
        $position = Location::get($clientIP);

        $b2c_visa_applied = \DB::table('dow_b2c_laravel.bookings')->where('visa_applied','=','1')->orderBy('id', 'DESC')->limit('5')->get();
        $b2c_notification = \DB::table('dow_b2c_laravel.bookings')->orderBy('id', 'DESC')->limit('5')->get();
        $b2b_notification = \DB::table('dow_new_b2b_laravel.bookings')->orderBy('id', 'DESC')->limit('5')->get();
        return view('template/frontend/userdashboard/pages/assign_task/mandob_list',compact('book','bookings','position','get_address','employees_1','employees_2','employees_3','employees1','employees','b2c_visa_applied','b2c_notification','b2b_notification','data_b2b','data_b2c','b2c_visa_not_applied'));
    }
    
    public function employees_task(Request $request){
       $id=$request->id;
        $task =\DB::table('tasks')->where('customer_id',$id)->get();
         $admin_employee =\DB::table('admin_employee')->where('customer_id',$id)->get();
         
         return response()->json(['message'=>'success','task'=>$task,'admin_employee'=>$admin_employee]);

        
       
    }
    
    public function submit_task(Request $request){

            $tasks=new Task();
            $tasks->customer_id=$request->id;
            $tasks->employee_id=$request->employee_id;
            $tasks->task_title=$request->task_title;
            $tasks->task_discription=$request->task_discription;
            $tasks->task_comments=$request->task_comments;
            $tasks->task_assigned_date=$request->task_assigned_date;
            $tasks->expected_completion_date=$request->expected_completion_date;
            $tasks->completion_date=$request->completion_date;
           
            $tasks->save();
        

return response()->json(['message'=>'success','tasks'=>$tasks]);


 
}

    public function employees_task_delete(Request $request){
        $id=$request->id;
        $task =\DB::table('tasks')->where('id',$id)->delete();
        return response()->json(['message'=>'success','task'=>$task]);
        
    }

    public function employees_task_edit(Request $request){
       $id=$request->ids;
       $customer_id=$request->id;
    //   print_r($id);die();
        $task =\DB::table('tasks')->where('id',$id)->first();
         $admin_employee =\DB::table('admin_employee')->where('customer_id',$customer_id)->get();
         
         return response()->json(['message'=>'success','task'=>$task,'admin_employee'=>$admin_employee]);

        
       
    }
    
    public function submit_edit_task(Request $request){
           $id=$request->ids;
       $customer_id=$request->id;
        //   print_r($id);die();
         $tasks=Task::find($id);
         if($tasks)
         {
           $tasks->customer_id=$request->id;
            $tasks->employee_id=$request->employee_id;
            $tasks->task_title=$request->task_title;
            $tasks->task_discription=$request->task_discription;
            $tasks->task_comments=$request->task_comments;
            $tasks->task_assigned_date=$request->task_assigned_date;
            $tasks->expected_completion_date=$request->expected_completion_date;
            $tasks->completion_date=$request->completion_date;
           
            $tasks->update();  
         }
            
        

return response()->json(['message'=>'success','tasks'=>$tasks]);

        
       
    }
  
    public function create_password(){

        return view('template/frontend/userdashboard/pages/email/create-password');
    }
    
    public function create_password_lead(Request $request){
        $this->validate($request,
            [
                'user_password'=>'required',


            ]);
        $get_email=\DB::table('dow_new_b2b_laravel.task_details')->orderBy('id', 'DESC')->first('email');
       $get_email_1=$get_email->email;
        $mail=$request->input('lead_passenger_email');
        $vi=$mail ==$get_email_1;
        if($vi)
        {

            $lead_passengers=new LeadPassenger();
            $lead_passengers->email=$get_email_1;
            $lead_passengers->password=Hash::make($request->input('user_password'));
            $lead_passengers->save();
            return redirect()->back()->with('message','password Created successfully');
        }
        else
        {
            return redirect()->back()->with('message','password Not Created successfully!!');
        }
    }
}
