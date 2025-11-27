@extends('template/frontend/userdashboard/layout/default')
@section('content')
 
    <div class="mt-2">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Rooms Details</h4>

                        <table id="example_11" class="table dt-responsive nowrap w-100">
                            <thead class="theme-bg-clr">
                                <tr>
                                    <th>id</th>
                                    <th>Customer Name</th>
                                    <th>Room Name</th>
                                    <th>Room Available</th>
                                    <th>Room Booked</th>
                                    <th>Availability</th>
                                    <th>Price Per Room</th>
                                </tr>
                            </thead>
                        
                            <tbody>
                                <?php 
                                    if(isset($rooms) && $rooms != null && $rooms != ''){
                                        $count = 1; // dd($rooms); 
                                        foreach($rooms as $rooms_value){
                                            if($rooms_value->quantity == $rooms_value->booked){
                                ?>
                                                <tr style="background: #ffe6e9;">
                                                    <td>{{ $count }}</td>
                                                    <td>
                                                        @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                            @foreach($all_Users as $all_Users_value)
                                                                @if($rooms_value->owner_id == $all_Users_value->id)
                                                                    <b>{{ $all_Users_value->name }}</b>
                                                                    <?php
                                                                        $currency = $all_Users_value->currency_symbol;
                                                                    ?>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <b>{{ $rooms_value->room_type_name ?? '' }}</b><br>
                                                        <b>{{ $rooms_value->room_meal_type ?? '' }}</b>
                                                    </td>
                                                    <td>{{ $rooms_value->quantity ?? '' }}</td>
                                                    <td>{{ $rooms_value->booked ?? '' }}</td>
                                                    <td>
                                                        {{ $rooms_value->availible_from ?? '' }} <br>
                                                        {{ $rooms_value->availible_to ?? '' }}
                                                    </td>
                                                    <td>
                                                        <?php
                                                            if($rooms_value->price_week_type == 'for_all_days'){
                                                                echo ('All Days Price = ' . $currency . ' ' . $rooms_value->price_all_days);
                                                            }
                                                            else{
                                                                echo ('Week Days Price = '. $currency . ' ' . $rooms_value->weekdays_price);
                                                                echo '</br>';
                                                                echo ('Weekend Days Price = '. $currency . ' ' . $rooms_value->weekends_price);
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                <?php       }
                                            else{
                                ?>
                                                <tr>
                                                    <td>{{ $count }}</td>
                                                    <td>
                                                        @if(isset($all_Users) && $all_Users != null && $all_Users != '')
                                                            @foreach($all_Users as $all_Users_value)
                                                                @if($rooms_value->owner_id == $all_Users_value->id)
                                                                    <b>{{ $all_Users_value->name }}</b>
                                                                    <?php
                                                                        $currency = $all_Users_value->currency_symbol;
                                                                    ?>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <b>{{ $rooms_value->room_type_name ?? '' }}</b><br>
                                                        <b>{{ $rooms_value->room_meal_type ?? '' }}</b>
                                                    </td>
                                                    <td>{{ $rooms_value->quantity ?? '' }}</td>
                                                    <td>{{ $rooms_value->booked ?? '' }}</td>
                                                    <td>
                                                        {{ $rooms_value->availible_from ?? '' }} <br>
                                                        {{ $rooms_value->availible_to ?? '' }}
                                                    </td>
                                                    <td>
                                                        <?php
                                                            if($rooms_value->price_week_type == 'for_all_days'){
                                                                print_r('All Days Price=' . $currency . ' ' . $rooms_value->price_all_days);
                                                            }
                                                            else{
                                                                print_r('Week Days Price='. $currency . ' ' . $rooms_value->weekdays_price);
                                                                echo '</br>';
                                                                print_r('Weekend Days Price='. $currency . ' ' . $rooms_value->weekends_price);
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                <?php       $count++;
                                            }
                                        } 
                                    }
                                ?>
                                
                            </tbody>
                        </table>                                           
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $('#example_11').DataTable({
                scrollX: true,
                scrollY: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
                
            });
        }); 
    </script>
 @endsection