<?php

namespace App\Http\Controllers\HotelMangers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\RoomsType;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\hotel_manager\RoomGallery;
use DB;
use Auth;

class RoomController extends Controller
{
    public function view_rooms_Admin(Request $request,$hotel_id){
        $rooms          = Rooms::where('hotel_id',$hotel_id)->get();
        $Rooms_count    = count($rooms);
        if($Rooms_count > 0){
            $rooms  = Rooms::where('hotel_id',$hotel_id)->get();
            $rooms  = json_encode($rooms);
            $rooms  = json_decode($rooms);
        }else{
            $rooms      = '';
        }
        $all_Users  = DB::table('customer_subcriptions')->get();
        $all_Users  = json_encode($all_Users);
        $all_Users  = json_decode($all_Users);
        return view('template/frontend/userdashboard/pages/hotel_manager/manage_rooms/view_rooms',compact('all_Users','rooms'));
    }
    
    public function rooms_list_Admin(Request $request){
        $user_rooms = Rooms::join('hotels','rooms.hotel_id','=','hotels.id')
                        ->join('rooms_types','rooms.room_type_id','=','rooms_types.room_type')
                        ->orderBy('rooms.created_at', 'desc')
                        ->get(['rooms.owner_id','rooms.more_room_type_details','rooms.id','rooms.room_img','rooms.availible_from','rooms.availible_to','rooms.quantity','rooms.status','rooms_types.room_type','hotels.property_name']);
        $all_Users  = DB::table('customer_subcriptions')->get();
        return view('template/frontend/userdashboard/pages/hotel_manager.rooms_list',compact('user_rooms','all_Users'));
    }

    public function index(){
        $user_rooms = Rooms::join('hotels','rooms.hotel_id','=','hotels.id')
              ->join('rooms_types','rooms.room_type_id','=','rooms_types.id')
              ->where('rooms.owner_id',Auth::user()->id)
              ->orderBy('rooms.created_at', 'desc')
              ->get(['rooms.id','rooms.room_img','rooms.availible_from','rooms.availible_to','rooms.quantity','rooms.status',
                    'rooms_types.room_type','hotels.property_name']);

        // dd($user_rooms);
        return view('template/frontend/userdashboard/pages/hotel_manager.rooms_list',compact('user_rooms'));
    }

    public function showAddRoomFrom(){
        $user_hotels = Hotels::select(['id','property_name'])
                                ->where('hotels.owner_id',Auth::user()->id)
                                ->get();

        $roomTypes = RoomsType::all();        
        return view('template/frontend/userdashboard/pages/hotel_manager.add_rooms',compact('user_hotels','roomTypes'));
    }

    public function addRoom(Request $request){
         
        // $validated = $request->validate([
        //     'room_av_from'=>'required',
        //     'room_av_to'=>'required',
        //     'quantity'=>'required|numeric|min:1',
        //     'min_stay'=>'required|numeric|min:1',
        //     'room_desc'=>'required',
        //     'room_img'=>'image|required|mimes:jpg,png,jpeg,gif,svg|max:2048',
        // ]);

        // dd($request->all());
        $Rooms = new Rooms;
        $Rooms->hotel_id =  $request->hotel;
       
        $Rooms->room_type_id =  $request->room_type; 
        $Rooms->room_view =  $request->room_view; 
        $Rooms->price_type =  $request->price_type; 
        $Rooms->adult_price =  $request->adult_price;
        $Rooms->child_price =  $request->child_price; 
        $Rooms->quantity =  $request->quantity;  
        $Rooms->min_stay =  $request->min_stay; 
        $Rooms->max_child =  $request->max_childrens; 
        $Rooms->max_adults =  $request->max_adults; 
        $Rooms->extra_beds =  $request->extra_beds; 
        $Rooms->extra_beds_charges =  $request->extra_beds_charges; 
        $Rooms->availible_from =  $request->room_av_from; 
        $Rooms->availible_to =  $request->room_av_to; 
        $Rooms->price_week_type =  $request->week_price_type; 
        $Rooms->price_all_days =  $request->price_all_days; 
        $Rooms->weekdays = serialize($request->weekdays); 
        $Rooms->weekdays_price =  $request->week_days_price; 
        $Rooms->weekends =  serialize($request->weekend); 
        $Rooms->weekends_price =  $request->week_end_price; 
        $Rooms->room_description =  $request->room_desc; 
        $Rooms->amenitites =  serialize($request->amenities);
        $Rooms->status =  $request->status;
        
        $Rooms->cancellation_details =  $request->cancellation_details;
        $Rooms->additional_meal_type =  $request->additional_meal_type;
        $Rooms->additional_meal_type_charges =  $request->additional_meal_type_charges;
        
        
        
        if($request->file('room_img')){
            
            $img_file = $request->file('room_img');
            $name_gen = hexdec(uniqid());
            $img_ext = strtolower($img_file->getClientOriginalExtension());
            $img_name = $name_gen.".".$img_ext;
            $upload = 'public/images/rooms';
            $file_upload = $img_file->move($upload,$img_name);
            if($file_upload){
                $user_id = Auth::user()->id;
                $Rooms->room_img = $img_name;
                $Rooms->owner_id = $user_id;
                    $result = $Rooms->save();
                    if($result){
                        return redirect()->back()->with('success','Room Added Successfuly');                
                    }else{
                        return redirect()->back()->with('error','Sorry Something WentWrong Try Again');
                    }

                            
            }else{
                // return redirect()->back()->with('error','Sorry Something WentWrong Try Again');

            }
        }

    }

    public function updateShowForm(Request $request,$id){
        $user_hotels = Hotels::select(['id','property_name'])
                                ->where('hotels.owner_id',Auth::user()->id)
                                ->get();

        $roomTypes = RoomsType::all();
        
        $roomData = Rooms::find($id);

        $roomGallery = RoomGallery::where('room_id',$id)->get();
        return view('template/frontend/userdashboard/pages/hotel_manager.view_room',compact('user_hotels','roomTypes','roomData','roomGallery'));
    }

    public function update_room(Request $request,$id){
        // dd($request->all());

        if($request->file('room_gallery')){
            foreach($request->file('room_gallery') as $file){
                $img_file = $file;
                $name_gen = hexdec(uniqid());
                $img_ext = strtolower($img_file->getClientOriginalExtension());
                $img_name = $name_gen.".".$img_ext;
                $upload = 'public/images/rooms/roomGallery';
                $file_upload = $img_file->move($upload,$img_name);
                if($file_upload){
                    $roomGallery = new RoomGallery;
                    $roomGallery->img_name = $img_name;
                    $roomGallery->room_id = $id;
                    $result = $roomGallery->save();
                }
            }

            if($result){
                return redirect()->back()->with('success','Updated Successfuly');                
            }else{
                return redirect()->back()->with('error','Sorry Something WentWrong Try Again');
            }
           
        }
    }
}
