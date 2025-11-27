<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\Ticket;
use App\Models\SubPcc;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function generate_ticket()
            {
                $countries = DB::table('countries')->get();
                 $login_agent=Auth::guard('agent')->user()->email;
    $agent_active=DB::table('active__agents')->where('agent_email',$login_agent)->orderBy('id', 'DESC')->limit('5')->get();
                $data_b2b = DB::table('bookings')->where('hotel_makkah_booking_status','=','cancelled')->orwhere('hotel_madina_booking_status','=','cancelled')->orwhere('transfer_booking_status','=','cancelled')->where('id','DESC')->limit('5')->where('agent_email',$login_agent)->get();


        
        
       
        $b2b_notification = DB::table('bookings')->where('agent_email',$login_agent)->orderBy('id', 'DESC')->limit('5')->get();
        return view('template/frontend/userdashboard/pages/support_ticket/generate_ticket',compact('countries','b2b_notification','data_b2b','agent_active'));

            }
            public function submit_ticket(Request $request)
            {
            	$key = random_int(0, 9999999);
$random = str_pad($key, 6, 0, STR_PAD_LEFT);
       

            	$ticket=new Ticket();
            	$ticket->company_name=$request->company_name;
            	$ticket->umrah_operator=$request->umrah_operator;
            	$ticket->email=$request->email;
            	$ticket->phone=$request->phone;
                $ticket->additinal_email=$request->additinal_email;
            	$ticket->ticket_type=$request->ticket_type;
            	$ticket->ticket_priorty=$request->ticket_priorty;
            	$ticket->subject=$request->subject;
                $ticket->ticket_id=$random;
            	$ticket->description=$request->description;
            	$ticket->agent_id=$request->agent_id;
            	$ticket->save();
            	 return redirect()->back()->with('message','Ticket Generate Successful!');
            }









            public function view_ticket()
            {
                $countries = DB::table('countries')->get();
                 $login_agent=Auth::guard('agent')->user()->email;
    $agent_active=DB::table('active__agents')->where('agent_email',$login_agent)->orderBy('id', 'DESC')->limit('5')->get();
                $data_b2b = DB::table('bookings')->where('hotel_makkah_booking_status','=','cancelled')->orwhere('hotel_madina_booking_status','=','cancelled')->orwhere('transfer_booking_status','=','cancelled')->where('id','DESC')->limit('5')->where('agent_email',$login_agent)->get();

$ticket=Ticket::orderBy('id','DESC')->get();
        
        
       
        $b2b_notification = DB::table('bookings')->where('agent_email',$login_agent)->orderBy('id', 'DESC')->limit('5')->get();
        return view('template/frontend/userdashboard/pages/support_ticket/view_ticket',compact('ticket','countries','b2b_notification','data_b2b','agent_active'));

            }

             public function add_sub_pcc()
            {
                $countries = DB::table('countries')->get();
                 $login_agent=Auth::guard('agent')->user()->email;
    $agent_active=DB::table('active__agents')->where('agent_email',$login_agent)->orderBy('id', 'DESC')->limit('5')->get();
                $data_b2b = DB::table('bookings')->where('hotel_makkah_booking_status','=','cancelled')->orwhere('hotel_madina_booking_status','=','cancelled')->orwhere('transfer_booking_status','=','cancelled')->where('id','DESC')->limit('5')->where('agent_email',$login_agent)->get();

$ticket=Ticket::orderBy('id','DESC')->get();
        
        
       
        $b2b_notification = DB::table('bookings')->where('agent_email',$login_agent)->orderBy('id', 'DESC')->limit('5')->get();
        return view('template/frontend/userdashboard/pages/sub_pcc/add_sub_pcc',compact('ticket','countries','b2b_notification','data_b2b','agent_active'));

            }
            //  public function submit_sub_pcc(Request $request)
            // {

            //     $agent_id=Auth::guard('agent')->user()->id;
            //     $subpcc=new SubPcc();
            //     $subpcc->valid_from=$request->valid_from;
            //     $subpcc->valid_to=$request->valid_to;
            //     $subpcc->hotel_name=$request->hotel_name;
            //     $subpcc->city=$request->city;
            //     $subpcc->sub_pcc_code=$request->sub_pcc_code;
            //     $subpcc->agent_id=$request->agent_id;
            //     $subpcc->save();
            //     return redirect()->back()->with('message','Added Sub Pcc Successful!');


            // }
              public function submit_sub_pcc(Request $request)
            {
                $login_id=Auth::guard('agent')->user()->id;
                
                 $req_code=$request->hotel_code;
                $check_subpcc=DB::table('sub_pccs')->where('agent_id', $login_id)->where('hotel_code', $req_code)->first();
               
                if($check_subpcc){
                    
                    $subpcc=DB::table('sub_pccs')
                    ->where('hotel_code',$request->hotel_code)
                    ->update(
                        [
                            'valid_from' => $request->valid_from,
                             'agent_id' => $request->agent_id,
                              'valid_to' => $request->valid_to,
                               'hotel_name' => $request->hotel_name,
                                'hotel_code' => $request->hotel_code,
                                 'city' => $request->city,
                                 'sub_pcc_code' => $request->sub_pcc_code
                        ]);
                    return redirect()->back()->with('message','Updated Sub Pcc Successful!');
                }
                
                $subpcc=new SubPcc();
                $subpcc->valid_from=$request->valid_from;
                $subpcc->agent_id=$request->agent_id;
                $subpcc->valid_to=$request->valid_to;
                $subpcc->hotel_name=$request->hotel_name;
                $subpcc->hotel_code=$request->hotel_code;
                $subpcc->city=$request->city;
                $subpcc->sub_pcc_code=$request->sub_pcc_code;
                $subpcc->save();
                return redirect()->back()->with('message','Added Sub Pcc Successful!');  


            }

            public function update_sub_pcc(Request $request,$id)
            {
                //$agent_id=Auth::guard('agent')->user()->id;
                $subpcc=SubPcc::find($id);
                if($subpcc)
                {
                   $subpcc->valid_from=$request->valid_from;
                $subpcc->valid_to=$request->valid_to;
                $subpcc->hotel_name=$request->hotel_name;
                $subpcc->city=$request->city;
                $subpcc->sub_pcc_code=$request->sub_pcc_code;
                //$subpcc->agent_id=$request->agent_id;
                $subpcc->update();
                return redirect()->back()->with('message','Updated Sub Pcc Successful!'); 
                }
                


            }


            public function view_sub_pcc()
            {
                $countries = DB::table('countries')->get();
                 $login_agent=Auth::guard('agent')->user()->email;
    $agent_active=DB::table('active__agents')->where('agent_email',$login_agent)->orderBy('id', 'DESC')->limit('5')->get();
                $data_b2b = DB::table('bookings')->where('hotel_makkah_booking_status','=','cancelled')->orwhere('hotel_madina_booking_status','=','cancelled')->orwhere('transfer_booking_status','=','cancelled')->where('id','DESC')->limit('5')->where('agent_email',$login_agent)->get();
          $agent_id=Auth::guard('agent')->user()->id;
          $ticket=SubPcc::orderBy('id','DESC')->where('agent_id',$agent_id)->get();
        
        
       
        $b2b_notification = DB::table('bookings')->where('agent_email',$login_agent)->orderBy('id', 'DESC')->limit('5')->get();
        return view('template/frontend/userdashboard/pages/sub_pcc/view_sub_pcc',compact('ticket','countries','b2b_notification','data_b2b','agent_active'));

            }


            public function ticket_submit(Request $request,$id)
            {
                
       $ticket=Ticket::find($id);
       if($ticket)
       {
       $key = random_int(0, 9999999);
$random = str_pad($key, 6, 0, STR_PAD_LEFT);
         $ticket=new Ticket();
                $ticket->company_name=$request->company_name;
                $ticket->umrah_operator=$request->umrah_operator;
                $ticket->email=$request->email;
                $ticket->phone=$request->phone;
                $ticket->additinal_email=$request->additinal_email;
                $ticket->ticket_type=$request->ticket_type;
                $ticket->ticket_priorty=$request->ticket_priorty;
                $ticket->subject=$request->subject;
                $ticket->description=$request->description;
                $ticket->status='Pending';
                  $ticket->ticket_id=$random;
                $ticket->add_comment=$request->add_comment;
                $ticket->agent_id=$request->agent_id;
                $ticket->save();
                 return redirect()->back()->with('message','Add Comment Successful!');
       }

               
            }
}
