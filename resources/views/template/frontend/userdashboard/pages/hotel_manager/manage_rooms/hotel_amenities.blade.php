@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 
 

 
 

    <div class="mt-5" id="">
        <div class="row">
            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Amenities Detail</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                        <div class="row">
                                            <?php
                                            if(isset($data->hotels))
                                            {
                                            $hotels=$data->hotels;
                                            $facilities=unserialize($hotels->facilities);
                                            $count=1;
                                            foreach($facilities as $fas)
                                            {
                                                ?>
                                            <div class="col-md-4">
                                                <span>{{$count}} )  <strong>{{$fas}}</strong></span>
                                            </div>
                                                <?php
                                                $count=$count+1;
                                                
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