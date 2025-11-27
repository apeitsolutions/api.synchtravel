<div class="dashboard-nav">
   <div class="dashboard-nav-inner">
      <ul>

         <!-- <li class="@if(Request::path() == 'hotel_manger/dashboard') active @endif"><a href="{{ URL::to('/hotel_manger/dashboard') }}"><i class="sl sl-icon-settings"></i> Dashboard</a></li>
         <li class="@if(Request::path() == 'hotel_manger/profile') active @endif"><a href="{{ URL::to('/hotel_manger/profile') }}"><i class="sl sl-icon-user"></i>Profile</a></li>
         <li class="@if(Request::path() == 'hotel_manger/add_hotel' || Request::path() == 'hotel_manger/hotel_list') active @endif">
         </li> -->

         <li class="">
             <a><i class="sl sl-icon-layers"></i>Packages</a>
             <ul>
             <li ><a href="{{ URL::to('create_package') }}"><i class="sl sl-icon-plus"></i>Create Package </a></li>
             </ul>
         </li>

         <li class="">
             <a><i class="sl sl-icon-layers"></i>Acounting Details</a>
             <ul>
             <li ><a href="{{ URL::to('acc_Users') }}"><i class="sl sl-icon-plus"></i>Users </a></li>
             <li ><a href="{{ URL::to('transactions') }}"><i class="sl sl-icon-plus"></i>Transactions </a></li>
             </ul>
         </li>

         <li class="">
             <a><i class="sl sl-icon-layers"></i>Quotations</a>
             <ul>
             <li ><a href="{{ URL::to('manage_Quotation') }}"><i class="sl sl-icon-plus"></i>Manage Quotations </a></li>
             <li ><a href="{{ URL::to('view_Quotations') }}"><i class="sl sl-icon-plus"></i>View Quotations </a></li>
             </ul>
         </li>

         <li class="">
             <a><i class="sl sl-icon-layers"></i>Bookings</a>
             <ul>
             <li ><a href="#"><i class="sl sl-icon-plus"></i>Add Bookings </a></li>
             <li ><a href="{{ URL::to('view_Bookings') }}"><i class="sl sl-icon-plus"></i>View Bookings </a></li>
             </ul>
         </li>

         <li class="@if(Request::path() == 'hotel_manger/add_hotel' || Request::path() == 'hotel_manger/hotel_list') active @endif">
             <a><i class="sl sl-icon-layers"></i> Hotels</a>
             <ul>
             <li class="@if(Request::path() == 'hotel_manger/add_hotel') active @endif"><a href="{{ URL::to('/hotel_manger/add_hotel') }}"><i class="sl sl-icon-plus"></i>Hotel Add </a></li>
             <li class="@if(Request::path() == 'hotel_manger/hotel_list') active @endif"><a href="{{ URL::to('/hotel_manger/hotel_list') }}"><i class="sl sl-icon-list"></i>Hotels List</a></li>
             </ul>
         </li>

         <li class="@if(Request::path() == 'hotel_manger/add_room' || Request::path() == 'hotel_manger/rooms_list') active @endif">
             <a><i class="sl sl-icon-layers"></i> Rooms</a>
             <ul>
             <li class="@if(Request::path() == 'hotel_manger/add_room') active @endif"><a href="{{ URL::to('/hotel_manger/add_room') }}"><i class="sl sl-icon-plus"></i>Room Add </a></li>
             <li class="@if(Request::path() == 'hotel_manger/rooms_list') active @endif"><a href="{{ URL::to('/hotel_manger/rooms_list') }}"><i class="sl sl-icon-list"></i>Rooms List</a></li>
             </ul>
         </li>

         <li>
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault();
               document.getElementById('logout-form').submit();">
               <i class="sl sl-icon-power"></i> {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
               @csrf
            </form>
         </li>
      </ul>
   </div>
</div>
