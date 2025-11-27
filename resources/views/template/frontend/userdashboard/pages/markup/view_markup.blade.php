<?php

 //$get_data_token=$get_data_hotel_token;
 
 
 
 

?>


@extends('template/frontend/userdashboard/layout/default')
 @section('content')
 

        <div class="row mt-5">
            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">

                                        <h4 class="header-title">Hotel Markup</h4>
                                        <p class="text-muted font-14">
                                            
                                        </p>

                                        
                                       
                                                <table id="example_1" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>id</th>
                                                            <th>Provider</th>
                                                            <th>Customer</th>
                                                            <th>Markup Type</th>
                                                             <th>Markup Value</th>
                                                            <th>Eidt</th>
                                                             <th>Delete</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                
                                                
                                                    <tbody>
                                                        <?php
                                                        foreach($markup as $data_markup)
                                                        {
                                                        ?>
                                                        <tr>
                                                            <td>{{$data_markup->id}}</td>
                                                             <td>{{$data_markup->provider ?? ''}}</td>
                                                              <td>
                                                                  <?php
                                                                  $customer=\DB::table('customer_subcriptions')->where('id',$data_markup->customer_id)->select('name','lname')->first();
                                                                  echo $customer->name . ' ' . $customer->lname;
                                                                  ?>
                                                                 
                                                                  
                                                                  </td>
                                                               <td>{{$data_markup->markup_type ?? ''}}</td>
                                                                <td>{{$data_markup->markup_value ?? ''}}</td>
                                                                <td><a href="{{URL::to('super_admin/markup/edit')}}/{{$data_markup->id}}" class="btn btn-primary">Edit</a></td>
                                                                <td><a href="{{URL::to('super_admin/markup/delete')}}/{{$data_markup->id}}" class="btn btn-primary">Delete</a></td>
                                                        </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>                                           
                                            
                                        
                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
        </div>
  

                        
   
           
                  
                     
 @endsection