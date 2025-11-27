@extends('template/frontend/userdashboard/layout/default')
@section('content')
<?php
// dd($suppliers);
?>


                <div class="container-fluid">
                    
                    <div class="table-responsive">
                        <div class="row">
                            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" style="background-color:#f5cfcf;" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" style="background-color:#d4edda;" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                

                 @if($suppliers != [])
                            <h3>Occupancy Record Against Flight Id.{{$suppliers[0]->flight_id ?? ""}}</h3>
                            <table class="table table-centered w-100 dt-responsive nowrap" id="example1">
                                <thead class="table-light">
                                    <tr>
                                        <th style="text-align: center;">Tour Id</th>
                                        <th style="text-align: center;">Tour Title</th>
                                        <th style="text-align: center;">Tour Pax</th>
                                       
                                       
                                        <th style="width: 85px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    @foreach($suppliers as $tour)
                                    <tr>
                                    <td><span class="badge bg-success" style="font-size: 15px">{{$tour->id ?? ""}}</span></td>
                                    <td><span class="badge bg-success" style="font-size: 15px">{{$tour->title ?? ""}}</span></td>
                                    <td><span class="badge bg-success" style="font-size: 15px">{{$tour->no_of_pax_days ?? ""}}</span></td>
                            
                                    <td> <a href="{{URL::to('invoice_for_occupancy')}}/{{$tour->id ?? ""}}"><span class="badge bg-success" style="font-size: 15px">View Invoice</span></a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                @endif
                            @if($suppliers == [])
                            
                            <!--<table class="table table-centered w-100 dt-responsive nowrap" id="example1">-->
                            <!--    <thead class="table-light">-->
                            <!--        <tr>-->
                            <!--            <th style="text-align: center;">Tour Id</th>-->
                            <!--            <th style="text-align: center;">Tour Title</th>-->
                            <!--            <th style="text-align: center;">Tour Pax</th>-->
                                       
                                       
                            <!--            <th style="width: 85px;">Action</th>-->
                            <!--        </tr>-->
                            <!--    </thead>-->
                            <!--    <tbody style="text-align: center;">-->
                            <!--        @foreach($suppliers as $tour)-->
                            <!--        <tr>-->
                            <!--        <td><span class="badge bg-success" style="font-size: 15px">{{$tour->id ?? ""}}</span></td>-->
                            <!--        <td><span class="badge bg-success" style="font-size: 15px">{{$tour->title ?? ""}}</span></td>-->
                            <!--        <td><span class="badge bg-success" style="font-size: 15px">{{$tour->no_of_pax_days ?? ""}}</span></td>-->
                                  
                            <!--        <td> <a href="{{URL::to('invoice_for_occupancy')}}/{{$tour->id ?? ""}}"><span class="badge bg-success" style="font-size: 15px">View Invoice</span></a></td>-->
                            <!--        </tr>-->
                            <!--        @endforeach-->
                            <!--    </tbody>-->
                            <!--</table>-->
                            <h1>Tour Has Been Deleted.</h1>
                            @endif
                        </div>
                    </div> 
                    
                </div>

@endsection

@section('scripts')

   

    <script>
        $(".dataTables_empty").html("Tour Has Been Deleted.");
        $(document).ready(function(){
        //   $(".dataTables_empty").html("Tour Has Been Deleted.");
        });
    </script>

@stop

@section('slug')

@stop
