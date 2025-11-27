@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 
 

 
 

    <div class="mt-5" id="">
        <div class="row">
            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Hotel Gallery</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                       
                                            <div class="row list-box-listing">
                                                
                                                
                                            
                                            <?php
                                            if(isset($data->hotels))
                                            {
                                            $hotels=$data->hotels;
                                            $room_gallery=json_decode($hotels->room_gallery);
                                           
                                            foreach($room_gallery as $img)
                                            {
                                                ?>
                                            <div class="list-box-listing-img col-md-4 mb-1">
                                                <div class="list-box-listing-img">
                                                    <img src="{{ asset('public/uploads/package_imgs') }}/{{ $img }}" alt="" style="height: 190px;width: 300px;">
                                               
                                            </div>
                                            </div>
                                                <?php
                                            
                                                
                                            }
                                            }
 
       
       

                                            
                                            ?>
                                            
                                            
                                        </div>
                                       
                                                                             
                                            
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
        </div>
    </div>
   
   
   
     
                            

                        
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
          <script>
  
</script>  
                       
 @endsection