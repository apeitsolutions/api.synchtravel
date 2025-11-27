<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Leave;
use Illuminate\Http\Request;
use Auth;

class LeaveController_Api extends Controller
{
    public function index(Request $request)
    {
        $customer_id=$request->id;
        $leaves= Leave::where('customer_id',$customer_id)->get();
       
        return response()->json(['message'=>'success','leaves'=>$leaves]);
    }

    public function approve(Request $request,$id){

        $leave = Leave::findOrFail($id);
        $get_email = \DB::table('leaves')->select('email')->where('id','=',$leave)->get();
        $leave->status = 1; //Approved
        $leave->save();
        // $details = [
        //     'title' => '',
        //     'body' => 'Body: Mail From Mahatat Al Alam'
        // ];

        // \Mail::to($get_email)->send(new SendMail($details));
        $request->session()->flash('success','Approved Your Leave');
        // return view('template.frontend.userdashboard.emails.thanks');
        return redirect()->back(); //Redirect user somewhere
    }

    public function decline(Request $request,$id){

        $leave = Leave::findOrFail($id);
        $get_email = \DB::table('leaves')->select('email')->where('id','=',$leave)->get();
        $leave->status = 0; //Declined
        $leave->save();
        // $details = [
        //     'title' => '',
        //     'body' => 'Body:'
        // ];

        // \Mail::to($get_email)->send(new SendMail($details));
         $request->session()->flash('success','Reject Your Leave');
        // return view('template.frontend.userdashboard.emails.reject');
        return redirect()->back(); //Redirect user somewhere
    }


    public function status(Leave $leave,Request $request,$status,$id)
    {
        $leaves = Leave::find($id);
        $get_email = \DB::table('leaves')->select('email')->where('id','=',$leave)->get();

        $leaves->status=$status;
        $leaves->update();
        $details = [
            'title' => 'Title: Mail From Mahatat Al Alam',
            'body' => 'Body: Mail From Mahatat Al Alam'
        ];

        // \Mail::to($get_email)->send(new SendMail($details));
        // return view('template.frontend.userdashboard.emails.thanks');
        return redirect()->back()->with('message', 'Leave Request Submit');
//        echo $type;
//        echo $id;
    }
        public function store(Request $request)
        {
            $leaves = new Leave();
            $leaves->employee_id = $request->employee_id;
            $leaves->employee_name = $request->employee_name;
            $leaves->email = $request->email;
            $leaves->position = $request->position;
            $leaves->pax_leave = $request->pax_leave;
            $leaves->form_date = $request->form_date;
            $leaves->to_date = $request->to_date;
            $leaves->save();
            return redirect()->back()->with('message', 'Leave Request Submit');
        }
}
