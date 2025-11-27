<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Task;
use App\Models\TBL_Attendance;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use DateTime;
use \DateTimeZone;
use Carbon\Carbon;
use Stevebauman\Location\Facades\Location;
use Illuminate\Console\Scheduling\Schedule;
use Session;
use DB;

class EmployeeController extends Controller
{
    public function employees()
    {
        
        $login_admin_id = Auth::guard('web')->user()->id;
        $employees = \DB::table('admin_employee')
        ->where('customer_id','=',$login_admin_id)
        ->get(); 
        
        return view('template/frontend/userdashboard/pages/employees/view_employee',compact('employees'));
    }
    public function create(){
        
     
        $login_user_id=Auth::guard('web')->user()->id;
        $roles = \DB::table('admin_hrms')
        ->where('customer_id','=',$login_user_id)
        ->get();
     
       
        return view('template/frontend/userdashboard/pages/employees/add_employee',compact('roles'));
    }
    public function store(Request $request)
    {
        
        $login_admin_id = Auth::guard('web')->user()->id;
        $employe_image='';
        if($request->hasFile('profile_image')){

            $file = $request->file('profile_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/uploads/employee_imgs/', $filename);
            $employe_image = $filename;
        }
        else{
            $employe_image = '';
        }
        $add_emp = \DB::table('admin_employee')
        ->insert([
            
            'customer_id' => $login_admin_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'address' => $request->address,
            'salary' => $request->salary,
            'birth_date' => $request->birth_date,
            'contact_info' => $request->contact_info,
            'gender' => $request->gender,
            'position' => $request->position,
            'schedule' => $request->schedule,
            'employee_status' => $request->employee_status,
            'image' => $employe_image
        ]);
        $request->session()->flash('success','Successful Add Employee!');
        return redirect('super_admin/employees');
    }
    public function edit($id)
    {
        
        $employees = \DB::table('admin_employee')
        ->where('id','=',$id)
        ->get()[0];
        $login_user_id=Auth::guard('web')->user()->id;
        $roles = DB::table('admin_hrms')
        ->where('admin_id','=',$login_user_id)
        ->get();
       

        return view('template/frontend/userdashboard/pages/employees/edit_employee',compact('employees','roles'));
    }
    public function update(Request $request,$id)
    {
        $emp = \DB::table('admin_employee')
        ->where('id','=',$id)
        ->get()[0];
        $image = "";
        $emp_previous_img = $emp->image;
        if($request->hasFile('profile_image')){

            $file = $request->file('profile_image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/uploads/employee_imgs/', $filename);
            $image = $filename;
        }
        else{
            if($emp_previous_img!=''){

                $image = $emp_previous_img;
            }else{
                
                $image = "";     
            }
            
        }
        $edit_emp = \DB::table('admin_employee')
        ->where('id','=',$id)
        ->update([
            
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'address' => $request->address,
            'salary' => $request->salary,
            'birth_date' => $request->birth_date,
            'contact_info' => $request->contact_info,
            'gender' => $request->gender,
            'position' => $request->position,
            'schedule' => $request->schedule,
            'image' => $image
        ]);
        $request->session()->flash('success','Successful Update Employee!');
        return redirect('super_admin/employees');
    }
    public function delete(Request $request,$id)
    {
        $delete_employee = \DB::table('admin_employee')->where('id','=', $id)->delete();
        $request->session()->flash('success','Successful Delete Employee!');
        return redirect('super_admin/employees');
    }
public function view_location($id)
{
    $date=date('Y-m-d');
$year = date('Y') . '-12-31';
$b2c_visa_not_applied = \DB::table('bookings')->
        whereDate('checkin', [$date,$year])->where('visa_applied','!=','1')->get();
    $employees=Employee::find($id);
$data_b2b = \DB::table('bookings')->where('hotel_makkah_booking_status','=','cancelled')->orwhere('hotel_madina_booking_status','=','cancelled')->orwhere('transfer_booking_status','=','cancelled')->where('id','DESC')->limit('5')->get();

$data_b2c = \DB::table('bookings')->where('hotel_makkah_booking_status','=','cancelled')->orwhere('hotel_madina_booking_status','=','cancelled')->orwhere('transfer_booking_status','=','cancelled')->orwhere('ground_service_booking_status','=','cancelled')->where('id','DESC')->limit('5')->get();

    $b2c_visa_applied = \DB::table('bookings')->where('visa_applied','=','1')->orderBy('id', 'DESC')->limit('5')->get();
    $b2c_notification = \DB::table('bookings')->orderBy('id', 'DESC')->limit('5')->get();
    $b2b_notification = \DB::table('bookings')->orderBy('id', 'DESC')->limit('5')->get();
    return view('template/frontend/userdashboard/pages/employees/view_map',compact('employees','b2c_visa_applied','b2c_notification','b2b_notification','data_b2b','data_b2c','b2c_visa_not_applied'));
}
    public function view_task_location($id)
    {
        $date=date('Y-m-d');
$year = date('Y') . '-12-31';
$b2c_visa_not_applied = \DB::table('bookings')->
        whereDate('checkin', [$date,$year])->where('visa_applied','!=','1')->get();
        $data_b2b = \DB::table('bookings')->where('hotel_makkah_booking_status','=','cancelled')->orwhere('hotel_madina_booking_status','=','cancelled')->orwhere('transfer_booking_status','=','cancelled')->where('id','DESC')->limit('5')->get();

$data_b2c = \DB::table('bookings')->where('hotel_makkah_booking_status','=','cancelled')->orwhere('hotel_madina_booking_status','=','cancelled')->orwhere('transfer_booking_status','=','cancelled')->orwhere('ground_service_booking_status','=','cancelled')->where('id','DESC')->limit('5')->get();
        $task=Task::find($id);

        $b2c_visa_applied = \DB::table('bookings')->where('visa_applied','=','1')->orderBy('id', 'DESC')->limit('5')->get();
        $b2c_notification = \DB::table('bookings')->orderBy('id', 'DESC')->limit('5')->get();
        $b2b_notification = \DB::table('bookings')->orderBy('id', 'DESC')->limit('5')->get();
        return view('template/frontend/userdashboard/pages/employees/task_view_map',compact('task','b2c_visa_applied','b2c_notification','b2b_notification','data_b2b','data_b2c','b2c_visa_not_applied'));
    }
    public function loginForm(Request $request)
    {

        $todaytime = Carbon::now()->toDateTimeString();

        return view('template/frontend/userdashboard/pages/employees/employee_login',compact('todaytime'));
    }
    public function login(Request $request)
    {
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
        $current_t =$dateTime->format("H:i A");
        $this->validate($request,
            [
                'employee_id'=>'required',

            ]);

        $emp_login = \DB::table('attendances')->where('employee_id','=',$request->employee_id)->first();


            if($emp_login)
            {
                $emp_login = new TBL_Attendance();
                $emp_login->employee_id = $request->employee_id;
                $emp_login->time_in = $current_t;
                $emp_login->save();
                $request->session()->flash('success','Successful Add Attendance!');
                return redirect('super_admin/employees');
            }
            else {
                return redirect()->back()->with('message', 'Employee Id is not correct!!');
            }


    }
    public function logout(Request $request)
    {
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Karachi'));
        $current_t =$dateTime->format("H:i A");
        TBL_Attendance::orderBy('id','desc')->limit('1')
            ->update([
                'time_out' => $current_t
            ]);
            return redirect('super_admin/login_emp');
    }

    public function employee_roles(){
        
        
        $login_user_id=Auth::guard('web')->user()->id;
        $roles = \DB::table('admin_hrms')
        ->where('admin_id','=',$login_user_id)
        ->get();
      
        return view('template/frontend/userdashboard/pages/employees/emp_role',compact('roles'));
    }

    public function add_roles(Request $request){

        $add_role = \DB::table('admin_hrms')
        ->insert([
            'admin_id' => $request->login_user_id,
            'role_title' =>$request->role_title
        ]);
        if($add_role){

            $request->session()->flash('success','Successful Add Role!');
            return redirect('super_admin/employee_roles');
        }
    }

    public function edit_roles(Request $request){

        $edit_role = \DB::table('admin_hrms')
        ->where('id','=',$request->role_id)
        ->update([

            'role_title' => $request->role_title,
        ]);
        if($edit_role){

            $request->session()->flash('success','Role Updated Successfully!');
            return redirect('super_admin/employee_roles');
        }
    }

    public function del_roles($id){
        $delete_role = \DB::table('admin_hrms')->where('id','=', $id)->delete();
        if($delete_role){

            session::flash('success','Role Deleted Successfully!');
            return redirect('super_admin/employee_roles');
        }
    }
    // public function get_agent_added_designation(Request $request){
    //     echo 'yes';   
    // }
}
