<?php

namespace App\Http\Controllers\frontend\admin_dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\addManageQuotation;
use App\Models\viewBooking;
use App\Models\hotel_manager\Hotels;
use App\Models\hotel_manager\Rooms;
use App\Models\country;
use DateTime;
use Carbon\Carbon;
use DB;

class ManageQuotationsController extends Controller
{
    // Manage Quotation
   public function manage_Quotation(){
      $hotels = Hotels::all();
      $all_countries = country::all();
      return view('template/frontend/userdashboard/pages/quotations/manage_Quotation',compact(['hotels','all_countries']));
   }

   // Add Manage Quotation
   public function add_Manage_Quotation(Request $req){

      $validatedData = $req->validate([
         'prefix' => 'required',
         'f_name' => 'required',
         'middle_name' => 'required',
         'surname' => 'required',
         'email' => 'required',
         'contact_landline' => 'required',
         'email' => 'required',
         'mobile' => 'required',
         'contact_work' => 'required',
         'postCode' => 'required',
         'country' => 'required',
         'city' => 'required',
         'primery_address' => 'required',
         'quotation_prepared' => 'required',
         'quotation_valid_date' => 'required',
      ]);

      //Adults
      $titlesAdults            = $req->titlesAdults;
      $first_nameAdults        = $req->first_nameAdults;
      $last_nameAdults         = $req->last_nameAdults;
      $dobAdults               = $req->dobAdults;
      $GenderAdults            = $req->GenderAdults;
      $countryAAdults          = $req->countryAAdults;
      $customer_passportAdults = $req->customer_passportAdults;
      $passport_expiryAdults   = $req->passport_expiryAdults;
      $c_countryAdults         = $req->c_countryAdults;

      $passengerAdults=array(
            'titlesAdults'            =>$titlesAdults,
            'first_nameAdults'        =>$first_nameAdults,
            'last_nameAdults'         =>$last_nameAdults,
            'dobAdults'               =>$dobAdults,
            'GenderAdults'            =>$GenderAdults,
            'countryAAdults'          =>$countryAAdults,
            'customer_passportAdults' =>$customer_passportAdults,
            'passport_expiryAdults'   =>$passport_expiryAdults,
            'c_countryAdults'         =>$c_countryAdults, 
      );
      $passengerDetailAdults = json_encode($passengerAdults);

      //Childs
      $titlesChilds            = $req->titlesChilds;
      $first_nameChilds        = $req->first_nameChilds;
      $last_nameChilds         = $req->last_nameChilds;
      $dobChilds               = $req->dobChilds;
      $GenderChilds            = $req->GenderChilds;
      $countryAChilds          = $req->countryAChilds;
      $customer_passportChilds = $req->customer_passportChilds;
      $passport_expiryChilds   = $req->passport_expiryChilds;
      $c_countryChilds         = $req->c_countryChilds;

      $passengerChilds=array(
            'titlesChilds'            =>$titlesChilds,
            'first_nameChilds'        =>$first_nameChilds,
            'last_nameChilds'         =>$last_nameChilds,
            'dobChilds'               =>$dobChilds,
            'GenderChilds'            =>$GenderChilds,
            'countryAChilds'          =>$countryAChilds,
            'customer_passportChilds' =>$customer_passportChilds,
            'passport_expiryChilds'   =>$passport_expiryChilds,
            'c_countryChilds'         =>$c_countryChilds, 
      );
      $passengerDetailChilds = json_encode($passengerChilds);

      //Infant
      $titlesInfant            = $req->titlesInfant;
      $first_nameInfant        = $req->first_nameInfant;
      $last_nameInfant         = $req->last_nameInfant;
      $dobInfant               = $req->dobInfant;
      $GenderInfant            = $req->GenderInfant;
      $countryAInfant          = $req->countryAInfant;
      $customer_passportInfant = $req->customer_passportInfant;
      $passport_expiryInfant   = $req->passport_expiryInfant;
      $c_countryInfant         = $req->c_countryInfant;

      $passengerInfant=array(
            'titlesInfant'            =>$titlesInfant,
            'first_nameInfant'        =>$first_nameInfant,
            'last_nameInfant'         =>$last_nameInfant,
            'dobInfant'               =>$dobInfant,
            'GenderInfant'            =>$GenderInfant,
            'countryAInfant'          =>$countryAInfant,
            'customer_passportInfant' =>$customer_passportInfant,
            'passport_expiryInfant'   =>$passport_expiryInfant,
            'c_countryInfant'         =>$c_countryInfant, 
      );
      $passengerDetailInfant = json_encode($passengerInfant);


      //Oneway
      $departure     = $req->departure;
      $deprturedate  = $req->deprturedate;
      $deprturetime  = $req->deprturetime;
      $arrival       = $req->arrival;
      $ArrivalDate   = $req->ArrivalDate;
      $ArrivalTime   = $req->ArrivalTime;
      $airline_name  = $req->airline_name;

      $oneWay=array(
            'departure'    =>$departure,
            'deprturedate' =>$deprturedate,
            'deprturetime' =>$deprturetime,
            'arrival'      =>$arrival,
            'ArrivalDate'  =>$ArrivalDate,
            'ArrivalTime'  =>$ArrivalTime,
            'airline_name' =>$airline_name,
      );
      $oneWayDetails = json_encode($oneWay);
      // print_r($oneWayDetails);die();

      //RoundTrip
      $departure_return     = $req->departure_return;
      $deprturedate_return  = $req->deprturedate_return;
      $deprturetime_return  = $req->deprturetime_return;
      $arrival_return       = $req->arrival_return;
      $ArrivalDate_return   = $req->ArrivalDate_return;
      $ArrivalTime_return   = $req->ArrivalTime_return;
      $airline_name_return  = $req->airline_name_return;

      $roundTrip=array(
            'departure_return'    =>$departure_return,
            'deprturedate_return' =>$deprturedate_return,
            'deprturetime_return' =>$deprturetime_return,
            'arrival_return'      =>$arrival_return,
            'ArrivalDate_return'  =>$ArrivalDate_return,
            'ArrivalTime_return'  =>$ArrivalTime_return,
            'airline_name_return' =>$airline_name_return,
      );
      $roundTripDetails = json_encode($roundTrip);
      // print_r($roundTripDetails);die();

      $insert = new addManageQuotation();
      //1
      $insert->prefix                  = $req->prefix;
      $insert->f_name                  = $req->f_name;
      $insert->middle_name             = $req->middle_name;
      $insert->surname                 = $req->surname;
      $insert->email                   = $req->email;
      $insert->contact_landline        = $req->contact_landline;
      $insert->mobile                  = $req->mobile;
      $insert->contact_work            = $req->contact_work;
      $insert->postCode                = $req->postCode;
      $insert->country                 = $req->country;
      $insert->city                    = $req->city;
      $insert->primery_address         = $req->primery_address;
      $insert->quotation_prepared      = $req->quotation_prepared;
      $insert->quotation_valid_date    = $req->quotation_valid_date;
      //2
      $insert->adults                  = $req->adults;
      $insert->childs                  = $req->childs;
      $insert->infant                  = $req->infant;
      $insert->passengerDetailAdults   = $passengerDetailAdults;
      $insert->passengerDetailChilds   = $passengerDetailChilds;
      $insert->passengerDetailInfant   = $passengerDetailInfant;
      //3
      $insert->oneWayDetails           = $oneWayDetails;
      $insert->roundTripDetails        = $roundTripDetails;
      $insert->f_date                  = $req->f_date;
      $insert->f_adults                = $req->f_adults;
      $insert->f_children              = $req->f_children;
      $insert->f_infant                = $req->f_infant;
      $insert->flighttype              = $req->flighttype;
      $insert->flight_price            = $req->flight_price;
      //4
      $insert->dateinmakkah            = $req->dateinmakkah;
      $insert->dateoutmakkah           = $req->dateoutmakkah;
      $insert->hotel_name_makkah       = $req->hotel_name_makkah;
      $insert->hotel_rooms_makkah      = $req->hotel_rooms_makkah;
      $insert->no_of_rooms_makkah      = $req->no_of_rooms_makkah;
      $insert->no_Of_Nights_Makkah     = $req->no_Of_Nights_Makkah;
      $insert->Price_Per_Nights_Makkah = $req->Price_Per_Nights_Makkah;
      $insert->Makkah_total_price_night_cal  = $req->Makkah_total_price_night_cal;
      $insert->Makkah_total_price_cal  = $req->Makkah_total_price_cal;
      //5
      $insert->dateinmadinah           = $req->dateinmadinah;
      $insert->dateoutmadinah          = $req->dateoutmadinah;
      $insert->hotel_name_madina       = $req->hotel_name_madina;
      $insert->hotel_rooms_madinah     = $req->hotel_rooms_madinah;
      $insert->no_of_rooms_madina      = $req->no_of_rooms_madina;
      $insert->no_Of_Nights_Madinah    = $req->no_Of_Nights_Madinah;
      $insert->price_per_night_madinah = $req->price_per_night_madinah;
      $insert->Madinah_total_price_night_cal  = $req->Madinah_total_price_night_cal;
      $insert->madinah_total_price_cal = $req->madinah_total_price_cal;
      //6
      $insert->pickuplocat             = $req->pickuplocat;
      $insert->dropoflocat             = $req->dropoflocat;
      $insert->dest_t                  = $req->dest_t;
      $insert->passenger               = $req->passenger;
      $insert->transfer_vehicle        = $req->transfer_vehicle;
      $insert->transf_price            = $req->transf_price;
      $insert->transfers_head_total    = $req->transfers_head_total;
      //7
      $insert->visa_fees_adult         = $req->visa_fees_adult;
      $insert->visa_fees_child         = $req->visa_fees_child;
      $insert->visa_fees_price         = $req->visa_fees_price;
      $insert->grand_total_price       = $req->grand_total_price;

      $insert->save();
      return redirect('manage_Quotation');
   }

   // View Quotations
   public function view_Quotations(){
      $data = addManageQuotation::all();
      return view('template/frontend/userdashboard/pages/quotations/view_Quotations',compact('data'));
   }

   // View QuotationsID
   public function view_QuotationsID($id) {
      $a = addManageQuotation::find($id);
      echo $a ;
   }

    // Edit_Quotations
    public function edit_Quotations($id){
        $data = addManageQuotation::find($id);
        $decode_DataOW = json_decode($data->oneWayDetails);
        $decode_DataRT = json_decode($data->roundTripDetails);
        $passengerDetailAdults = json_decode($data->passengerDetailAdults);
        // print_r($passengerDetailAdults);die();
        return view('template/frontend/userdashboard/pages/quotations/edit_Quotations',compact('data','decode_DataOW','decode_DataRT','passengerDetailAdults'));
    }

   // Update Manage Quotation
   public function update_Manage_Quotation(Request $req,$id){

      $insert = addManageQuotation::find($id);

      //Adults
      $titlesAdults            = $req->titlesAdults;
      $first_nameAdults        = $req->first_nameAdults;
      $last_nameAdults         = $req->last_nameAdults;
      $dobAdults               = $req->dobAdults;
      $GenderAdults            = $req->GenderAdults;
      $countryAAdults          = $req->countryAAdults;
      $customer_passportAdults = $req->customer_passportAdults;
      $passport_expiryAdults   = $req->passport_expiryAdults;
      $c_countryAdults         = $req->c_countryAdults;

      $passengerAdults=array(
            'titlesAdults'            =>$titlesAdults,
            'first_nameAdults'        =>$first_nameAdults,
            'last_nameAdults'         =>$last_nameAdults,
            'dobAdults'               =>$dobAdults,
            'GenderAdults'            =>$GenderAdults,
            'countryAAdults'          =>$countryAAdults,
            'customer_passportAdults' =>$customer_passportAdults,
            'passport_expiryAdults'   =>$passport_expiryAdults,
            'c_countryAdults'         =>$c_countryAdults, 
      );
      $passengerDetailAdults = json_encode($passengerAdults);

      //Childs
      $titlesChilds            = $req->titlesChilds;
      $first_nameChilds        = $req->first_nameChilds;
      $last_nameChilds         = $req->last_nameChilds;
      $dobChilds               = $req->dobChilds;
      $GenderChilds            = $req->GenderChilds;
      $countryAChilds          = $req->countryAChilds;
      $customer_passportChilds = $req->customer_passportChilds;
      $passport_expiryChilds   = $req->passport_expiryChilds;
      $c_countryChilds         = $req->c_countryChilds;

      $passengerChilds=array(
            'titlesChilds'            =>$titlesChilds,
            'first_nameChilds'        =>$first_nameChilds,
            'last_nameChilds'         =>$last_nameChilds,
            'dobChilds'               =>$dobChilds,
            'GenderChilds'            =>$GenderChilds,
            'countryAChilds'          =>$countryAChilds,
            'customer_passportChilds' =>$customer_passportChilds,
            'passport_expiryChilds'   =>$passport_expiryChilds,
            'c_countryChilds'         =>$c_countryChilds, 
      );
      $passengerDetailChilds = json_encode($passengerChilds);

      //Infant
      $titlesInfant            = $req->titlesInfant;
      $first_nameInfant        = $req->first_nameInfant;
      $last_nameInfant         = $req->last_nameInfant;
      $dobInfant               = $req->dobInfant;
      $GenderInfant            = $req->GenderInfant;
      $countryAInfant          = $req->countryAInfant;
      $customer_passportInfant = $req->customer_passportInfant;
      $passport_expiryInfant   = $req->passport_expiryInfant;
      $c_countryInfant         = $req->c_countryInfant;

      $passengerInfant=array(
            'titlesInfant'            =>$titlesInfant,
            'first_nameInfant'        =>$first_nameInfant,
            'last_nameInfant'         =>$last_nameInfant,
            'dobInfant'               =>$dobInfant,
            'GenderInfant'            =>$GenderInfant,
            'countryAInfant'          =>$countryAInfant,
            'customer_passportInfant' =>$customer_passportInfant,
            'passport_expiryInfant'   =>$passport_expiryInfant,
            'c_countryInfant'         =>$c_countryInfant, 
      );
      $passengerDetailInfant = json_encode($passengerInfant);

      //Oneway
      $departure     = $req->departure;
      $deprturedate  = $req->deprturedate;
      $deprturetime  = $req->deprturetime;
      $arrival       = $req->arrival;
      $ArrivalDate   = $req->ArrivalDate;
      $ArrivalTime   = $req->ArrivalTime;
      $airline_name  = $req->airline_name;

      $oneWay=array(
            'departure'    =>$departure,
            'deprturedate' =>$deprturedate,
            'deprturetime' =>$deprturetime,
            'arrival'      =>$arrival,
            'ArrivalDate'  =>$ArrivalDate,
            'ArrivalTime'  =>$ArrivalTime,
            'airline_name' =>$airline_name,
      );
      $oneWayDetails = json_encode($oneWay);
      // print_r($oneWayDetails);die();

      //RoundTrip
      $departure_return     = $req->departure_return;
      $deprturedate_return  = $req->deprturedate_return;
      $deprturetime_return  = $req->deprturetime_return;
      $arrival_return       = $req->arrival_return;
      $ArrivalDate_return   = $req->ArrivalDate_return;
      $ArrivalTime_return   = $req->ArrivalTime_return;
      $airline_name_return  = $req->airline_name_return;

      $roundTrip=array(
            'departure_return'    =>$departure_return,
            'deprturedate_return' =>$deprturedate_return,
            'deprturetime_return' =>$deprturetime_return,
            'arrival_return'      =>$arrival_return,
            'ArrivalDate_return'  =>$ArrivalDate_return,
            'ArrivalTime_return'  =>$ArrivalTime_return,
            'airline_name_return' =>$airline_name_return,
      );
      $roundTripDetails = json_encode($roundTrip);
       // print_r($roundTripDetails);die();

      //1
      $insert->prefix                  = $req->prefix;
      $insert->f_name                  = $req->f_name;
      $insert->middle_name             = $req->middle_name;
      $insert->surname                 = $req->surname;
      $insert->email                   = $req->email;
      $insert->contact_landline        = $req->contact_landline;
      $insert->mobile                  = $req->mobile;
      $insert->contact_work            = $req->contact_work;
      $insert->postCode                = $req->postCode;
      $insert->country                 = $req->country;
      $insert->city                    = $req->city;
      $insert->primery_address         = $req->primery_address;
      $insert->quotation_prepared      = $req->quotation_prepared;
      $insert->quotation_valid_date    = $req->quotation_valid_date;
      //2
      $insert->adults                  = $req->adults;
      $insert->childs                  = $req->childs;
      $insert->infant                  = $req->infant;
      $insert->passengerDetailAdults   = $passengerDetailAdults;
      $insert->passengerDetailChilds   = $passengerDetailChilds;
      $insert->passengerDetailInfant   = $passengerDetailInfant;
      //3
      $insert->oneWayDetails           = $oneWayDetails;
      $insert->roundTripDetails        = $roundTripDetails;
      $insert->f_date                  = $req->f_date;
      $insert->f_adults                = $req->f_adults;
      $insert->f_children              = $req->f_children;
      $insert->f_infant                = $req->f_infant;
      $insert->flighttype              = $req->flighttype;
      $insert->flight_price            = $req->flight_price;
      //4
      $insert->dateinmakkah            = $req->dateinmakkah;
      $insert->dateoutmakkah           = $req->dateoutmakkah;
      $insert->hotel_name_makkah       = $req->hotel_name_makkah;
      $insert->hotel_rooms_makkah      = $req->hotel_rooms_makkah;
      $insert->no_of_rooms_makkah      = $req->no_of_rooms_makkah;
      $insert->no_Of_Nights_Makkah     = $req->no_Of_Nights_Makkah;
      $insert->Price_Per_Nights_Makkah = $req->Price_Per_Nights_Makkah;
      $insert->Makkah_total_price_night_cal  = $req->Makkah_total_price_night_cal;
      $insert->Makkah_total_price_cal  = $req->Makkah_total_price_cal;
      //5
      $insert->dateinmadinah           = $req->dateinmadinah;
      $insert->dateoutmadinah          = $req->dateoutmadinah;
      $insert->hotel_name_madina       = $req->hotel_name_madina;
      $insert->hotel_rooms_madinah     = $req->hotel_rooms_madinah;
      $insert->no_of_rooms_madina      = $req->no_of_rooms_madina;
      $insert->no_Of_Nights_Madinah    = $req->no_Of_Nights_Madinah;
      $insert->price_per_night_madinah = $req->price_per_night_madinah;
      $insert->Madinah_total_price_night_cal  = $req->Madinah_total_price_night_cal;
      $insert->madinah_total_price_cal = $req->madinah_total_price_cal;
      //6
      $insert->pickuplocat             = $req->pickuplocat;
      $insert->dropoflocat             = $req->dropoflocat;
      $insert->dest_t                  = $req->dest_t;
      $insert->passenger               = $req->passenger;
      $insert->transfer_vehicle        = $req->transfer_vehicle;
      $insert->transf_price            = $req->transf_price;
      $insert->transfers_head_total    = $req->transfers_head_total;
      //7
      $insert->visa_fees_adult         = $req->visa_fees_adult;
      $insert->visa_fees_child         = $req->visa_fees_child;
      $insert->visa_fees_price         = $req->visa_fees_price;
      $insert->grand_total_price       = $req->grand_total_price;

      // Date
      $currDateTime = date('Y-m-d H:i:s');
      $insert->created_at = $currDateTime;
      // dd($insert);

      $insert->update();
      return redirect('view_Quotations');
   }

   // Add Bookings
   public function add_Bookings(Request $req,$id){
      // dd($currDateTime);
      $data = addManageQuotation::where('id',$id)->update([
         'confirm' => '1',
       ]);
      return redirect()->route('view_Bookings',compact('data'));
   }

   // View Bookings
   public function view_Bookings(){
      $data = addManageQuotation::all();
      return view('template/frontend/userdashboard/pages/quotations/view_Bookings',compact('data'));
   }

   // Hotel Makkah
   public function hotel_Makkah_Room(){
      $available_From = $_POST['availible_from'];
      $available_To   = $_POST['availible_to'];
       $rooms  = DB::table('rooms')->where('availible_from','<=',$available_From)->where('availible_to','>=',$available_To)
               ->join('rooms_types','rooms.room_type_id','=','rooms_types.id')
               ->join('hotels','rooms.hotel_id','=','hotels.id')->where('property_city',1)
               ->get();
      echo $rooms ;
   }

   // Room Makkah
   public function makkah_Room($id){
      $rooms = Rooms::where('hotel_id',$id)->join('rooms_types','rooms.room_type_id','=','rooms_types.id')->get();
      echo $rooms ;
   }

   // Hotel Madinah
   public function hotel_Madinah_Room(){
      $available_From = $_POST['availible_from'];
      $available_To   = $_POST['availible_to'];
       $rooms  = DB::table('rooms')->where('availible_from','<=',$available_From)->where('availible_to','>=',$available_To)
               ->join('rooms_types','rooms.room_type_id','=','rooms_types.id')
               ->join('hotels','rooms.hotel_id','=','hotels.id')->where('property_city',2)
               ->get();
      echo $rooms ;
   }

   // Room Madinah 
   public function madinah_Room($id){
      $rooms = Rooms::where('hotel_id',$id)->join('rooms_types','rooms.room_type_id','=','rooms_types.id')->get();
      echo $rooms ;
   }
}
