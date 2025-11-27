<?php

namespace App\Http\Controllers\frontend\admin_dashboard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UmrahPackage;
use App\Models\booking_users;
use App\Models\Tour;
use App\Models\country;
use App\Models\MinaPackage;
use App\Models\ArfatPackage;
use App\Models\MuzdilifaPackage;
use App\Models\MinaTent;
use App\Models\ArfatTent;
use DB;
use Auth;
use Session;

class HajPackageController extends Controller
{
   
   
   
    public function mina_packages_selected(Request $request)
{
    $tents=DB::table('mina_tents')->where('customer_id',$request->customer_id)->where('tent_type',$request->package_id)->first();
   return response()->json([
            'message'           => 'success',
            'tents'     => $tents,
            
        ]);
}
      public function arfat_packages_selected(Request $request)
{
    $tents=DB::table('arfat_tents')->where('customer_id',$request->customer_id)->where('tent_type',$request->package_id)->first();
   return response()->json([
            'message'           => 'success',
            'tents'     => $tents,
            
        ]);
}  
 public function mina_add(Request $request)
{
    $minaPackage=MinaPackage::orderBy('id','DESC')->where('customer_id',$request->customer_id)->get();
   return response()->json([
            'message'           => 'success',
            'minaPackage'     => $minaPackage,
            
        ]);
}
public function mina_submit(Request $request)
{
    
     
       
    $MinaTent= new MinaTent();
    $MinaTent->customer_id=$request->customer_id;
    $MinaTent->tent_type=$request->tent_type;
   $MinaTent->tant_count=$request->tant_count;
    $MinaTent->tants=$request->tants;
   $MinaTent->save();
   
    return response()->json([
            'message'           => 'success',
            'MinaTent'     => $MinaTent,
            
        ]);
}
public function mina_list(Request $request)
{
    
      
    $MinaTent=MinaTent::orderBy('id','DESC')->where('customer_id',$request->customer_id)->get();
return response()->json([
            'message'           => 'success',
            'MinaTent'     => $MinaTent,
            
        ]);

}

public function mina_edit(Request $request,$id)
{
   $MinaTent=MinaTent::find($id);
    $minaPackage=MinaPackage::orderBy('id','DESC')->where('customer_id',$request->customer_id)->get();
   //print_r($MinaPackage);die();
    return response()->json([
            'message'           => 'success',
            'minaPackage'     => $minaPackage,
            'MinaTent'     => $MinaTent,
        ]);
   
   
}
public function mina_edit_submit(Request $request,$id)
{
   $MinaTent=MinaTent::find($id);
   if($MinaTent)
   {
      $tants=$request->tants;
       $tants=json_encode($tants);
       //print_r($tants);die();
    $MinaTent->tent_type=$request->tent_type;
   $MinaTent->tant_count=$request->tant_count;
    $MinaTent->tants=$tants;
   $MinaTent->update();
    return response()->json([
            'message'           => 'success',
            'MinaTent'     => $MinaTent,
            
        ]);
   }
   else
   {
      return response()->json([
            'message'           => 'failed',
            
            
        ]);
   }

}
public function mina_delete(Request $request,$id)
{
   $MinaTent=MinaTent::find($id);
   if($MinaTent)
   {
   $MinaTent->delete();
   return response()->json([
            'message'           => 'success',
            'MinaTent'     => $MinaTent,
            
        ]);
   }
   else
   {
     return response()->json([
            'message'           => 'failed',
            
            
        ]);
   }

}


public function arfat_add(Request $request)
{
    $ArfatPackage=ArfatPackage::orderBy('id','DESC')->where('customer_id',$request->customer_id)->get();
   return response()->json([
            'message'           => 'success',
            'ArfatPackage'     => $ArfatPackage,
            
        ]); 
}
public function arfat_submit(Request $request)
{
    
      
    $ArfatTent= new ArfatTent();
     $ArfatTent->customer_id=$request->customer_id;
    $ArfatTent->tent_type=$request->tent_type;
   $ArfatTent->tant_count=$request->tant_count;
    $ArfatTent->tants=$request->tants;
   $ArfatTent->save();
   
return response()->json([
            'message'           => 'success',
            'ArfatTent'     => $ArfatTent,
            
        ]);
}
public function arfat_list(Request $request)
{
    
      
    $ArfatTent=ArfatTent::orderBy('id','DESC')->where('customer_id',$request->customer_id)->get();
   return response()->json([
            'message'           => 'success',
            'ArfatTent'     => $ArfatTent,
            
        ]);

}

public function arfat_edit(Request $request,$id)
{
   $ArfatTent=ArfatTent::find($id);
    $arfatPackage=ArfatPackage::orderBy('id','DESC')->where('customer_id',$request->customer_id)->get();
   //print_r($MinaPackage);die();
      return response()->json([
            'message'           => 'success',
            'ArfatTent'     => $ArfatTent,
            'arfatPackage'     => $arfatPackage,
            
        ]);
   
   
}
public function arfat_edit_submit(Request $request,$id)
{
   $ArfatTent=ArfatTent::find($id);
   if($ArfatTent)
   {
      $ArfatTent->customer_id=$request->customer_id;
    $ArfatTent->tent_type=$request->tent_type;
   $ArfatTent->tant_count=$request->tant_count;
    $ArfatTent->tants=$request->tants;
   $ArfatTent->update();
   return response()->json([
            'message'           => 'success',
            'ArfatTent'     => $ArfatTent,
            
        ]);
   }
   else
   {
       return response()->json([
            'message'           => 'failed',
           
            
        ]);
   }

}
public function arfat_delete(Request $request,$id)
{
   $ArfatTent=ArfatTent::find($id);
   if($ArfatTent)
   {
   $ArfatTent->delete();
    return response()->json([
            'message'           => 'success',
            'ArfatTent'     => $ArfatTent,
            
        ]);
   }
   else
   {
      return response()->json([
            'message'           => 'failed',
          
            
        ]);
   }

}












public function minapackage_add()
{
    
   return view('template/frontend/userdashboard/pages/haj_tech/minapackage'); 
}

public function minapackage_submit(Request $request)
{
   $MinaPackage=new MinaPackage();
   $MinaPackage->customer_id=$request->customer_id;
   $MinaPackage->package_name=$request->package_name;
   $MinaPackage->package_price=$request->package_price;
   $MinaPackage->package_description=$request->package_description;
   $MinaPackage->save();
   return response()->json([
            'message'           => 'success',
            'MinaPackage'     => $MinaPackage,
            
        ]);

   
}

public function minapackage_list(Request $request)
{
   $minaPackage=MinaPackage::orderBy('id','DESC')->where('customer_id',$request->customer_id)->get();
   return response()->json([
            'message'           => 'success',
            'minaPackage'     => $minaPackage,
            
        ]);
   
   
}
public function minapackage_edit(Request $request,$id)
{
   $minaPackage=MinaPackage::find($id);
   return response()->json([
            'message'           => 'success',
            'minaPackage'     => $minaPackage,
            
        ]);
   
   
}
public function minapackage_edit_submit(Request $request,$id)
{
   $minaPackage=MinaPackage::find($id);
   if($minaPackage)
   {
       $minaPackage->customer_id=$request->customer_id;
      $minaPackage->package_name=$request->package_name;
   $minaPackage->package_price=$request->package_price;
   $minaPackage->package_description=$request->package_description;
   $minaPackage->update();
   
     return response()->json([
            'message'           => 'success',
            'minaPackage'     => $minaPackage,
            
        ]);
  
   }
  

}
public function minapackage_delete(Request $request,$id)
{
   $minaPackage=MinaPackage::find($id);
   if($minaPackage)
   {
   $minaPackage->delete();
   return response()->json([
            'message'           => 'success',
            'minaPackage'     => $minaPackage,
            
        ]);
   }
   

}

public function arfatpackage_add()
{
   return view('template/frontend/userdashboard/pages/haj_tech/arfatpackage'); 
}

public function arfatpackage_submit(Request $request)
{
   $arfatPackage=new ArfatPackage();
   $arfatPackage->customer_id=$request->customer_id;
   $arfatPackage->package_name=$request->package_name;
   $arfatPackage->package_price=$request->package_price;
   $arfatPackage->package_description=$request->package_description;
   $arfatPackage->save();
   
  return response()->json([
            'message'           => 'success',
            'arfatPackage'     => $arfatPackage,
            
        ]);
   
}

public function arfatpackage_list(Request $request)
{
   $arfatPackage=ArfatPackage::orderBy('id','DESC')->where('customer_id',$request->customer_id)->get();
   return response()->json([
            'message'           => 'success',
            'arfatPackage'     => $arfatPackage,
            
        ]); 
   
   
}
public function arfatpackage_edit(Request $request,$id)
{
   $arfatPackage=ArfatPackage::find($id);
      return response()->json([
            'message'           => 'success',
            'arfatPackage'     => $arfatPackage,
            
        ]);
   
   
}
public function arfatpackage_edit_submit(Request $request,$id)
{
   $arfatPackage=ArfatPackage::find($id);
   if($arfatPackage)
   {
       $arfatPackage->customer_id=$request->customer_id;
      $arfatPackage->package_name=$request->package_name;
   $arfatPackage->package_price=$request->package_price;
   $arfatPackage->package_description=$request->package_description;
   $arfatPackage->update();
    return response()->json([
            'message'           => 'success',
            'arfatPackage'     => $arfatPackage,
            
        ]);
   }
   else
   {
       return response()->json([
            'message'           => 'failed',
           
            
        ]);
   }

}
public function arfatpackage_delete(Request $request,$id)
{
    
    
   $arfatPackage=ArfatPackage::find($id);
   if($arfatPackage)
   {
   $arfatPackage->delete();
   return response()->json([
            'message'           => 'success',
            'arfatPackage'     => $arfatPackage,
            
        ]);
   }
   else
   {
      return response()->json([
            'message'           => 'success',
            'arfatPackage'     => $arfatPackage,
            
        ]);
   }

}




public function muzdalfapackage_add()
{
   return view('template/frontend/userdashboard/pages/haj_tech/muzdalfapackage'); 
}

public function muzdalfapackage_submit(Request $request)
{
   $muzdalfaPackage=new MuzdilifaPackage();
   $muzdalfaPackage->customer_id=$request->customer_id;
   $muzdalfaPackage->package_name=$request->package_name;
   $muzdalfaPackage->package_price=$request->package_price;
   $muzdalfaPackage->package_description=$request->package_description;
   $muzdalfaPackage->save();
   
  return response()->json([
            'message'           => 'success',
            'muzdalfaPackage'     => $muzdalfaPackage,
            
        ]);
   
}

public function muzdalfapackage_list(Request $request)
{
   $muzdalfaPackage=MuzdilifaPackage::orderBy('id','DESC')->where('customer_id',$request->customer_id)->get();
   
     return response()->json([
            'message'           => 'success',
            'muzdalfaPackage'     => $muzdalfaPackage,
            
        ]);
   
}
public function muzdalfapackage_edit(Request $request,$id)
{
   $muzdalfaPackage=MuzdilifaPackage::find($id);
   return response()->json([
            'message'           => 'success',
            'muzdalfaPackage'     => $muzdalfaPackage,
            
        ]);
   
   
}
public function muzdalfapackage_edit_submit(Request $request,$id)
{
   $muzdalfaPackage=MuzdilifaPackage::find($id);
   if($muzdalfaPackage)
   {
      
      $muzdalfaPackage->package_name=$request->package_name;
   $muzdalfaPackage->package_price=$request->package_price;
   $muzdalfaPackage->package_description=$request->package_description;
   $muzdalfaPackage->update();
    return response()->json([
            'message'           => 'success',
            'muzdalfaPackage'     => $muzdalfaPackage,
            
        ]);
   }
   else
   {
      return response()->json([
            'message'           => 'failed',
            
            
        ]);
   }

}
public function muzdalfapackage_delete(Request $request,$id)
{
   $muzdalfaPackage=MuzdilifaPackage::find($id);
   if($muzdalfaPackage)
   {
   $muzdalfaPackage->delete();
  return response()->json([
            'message'           => 'success',
            'muzdalfaPackage'     => $muzdalfaPackage,
            
        ]);
   }
   else
   {
     return response()->json([
            'message'           => 'failed',
        
            
        ]);
   }

}



}
