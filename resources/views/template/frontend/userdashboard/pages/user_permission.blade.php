@extends('template/frontend/userdashboard/layout/default')

@section('content')

@if(session()->has('success'))

<div x-data="{ show: true }" x-show="show"

     class="flex justify-between items-center bg-yellow-200 relative text-yellow-600 py-3 px-3 rounded-lg">

    <div>

        <span class="font-semibold text-yellow-700"> {{session()->get('success')}}</span>

    </div>

    <div>

        <button type="button" @click="show = false" class=" text-yellow-700">

            <span class="text-2xl">&times;</span>

        </button>

    </div>

</div>

@endif

<div class="intro-y box mt-5" >

    <div class="col-span-6 intro-y box mt-5">

        <div class="intro-y box lg:mt-5">

            <div class="flex items-center p-5 border-b border-gray-200">

                <h2 class="font-medium text-base mr-auto">

                    Add User With Permission

                </h2>

            </div>

            <form action="{{url('super_admin/add_user_permission')}}" method="post">

                @csrf
                <!-- <input type="hidden" name="_token" value="VQS7q2IToG55iw3VHtMwAFpkVhx83AFl2csRAi0y">  -->

                <div class="p-5">

                    <div>

                        <label>Name</label>

                        <input type="text" name="name" class="input w-full border mt-2">

                    </div>

                    <div>

                        <label>Email</label>

                        <input type="email" name="eamil" class="input w-full border mt-2">

                    </div>

                    <div class="mt-3">

                        <label>Password</label>

                        <input type="password" name="password" class="input w-full border mt-2">

                    </div>

                    <div class="mt-3">

                        <label><b>Permission</b></label>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[b2b_bookings]" class="input border mr-2" id="vertical-checkbox-chris-evans">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-chris-evans">B2B Bookings</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[b2c_bookings]" class="input border mr-2" id="vertical-checkbox-liam-neeson">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-liam-neeson">B2C Bookings</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[agents]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Agents</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[add_markup]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Add Markup</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[supplier_meta]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Suppliers Meta</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[accounts]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Accounts</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[visa]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Visa</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[b2b_arrival_departure]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">B2B Arrivals & Departure</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[b2c_arrival_departure]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">B2C Arrivals & Departure</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[support_ticket]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Support Ticket</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[hrms]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">HRMS</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[settings]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Settings</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[draft_bookings]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Draft Bookings</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[booking_commission]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Booking Commission</label>

                        </div>

                        <div class="flex items-center text-gray-700 dark:text-gray-500 mt-2">

                            <input type="checkbox" name="user_permission[manage_user_roles]" class="input border mr-2" id="vertical-checkbox-daniel-craig">

                            <label class="cursor-pointer select-none" for="vertical-checkbox-daniel-craig">Manage User Permissions</label>

                        </div>

                    </div>

                    <!-- <div class="mt-3">

                        <label>Confirm New Password</label>

                        <input type="password" name="cnew_password" class="input w-full border mt-2" placeholder="Confirm New Password">

                    </div> -->

                    <button type="submit" name="submit" class="button bg-theme-1 text-white mt-4">Add User</button>

                </div>

            </form>

        </div>           

    </div>

</div>

@endsection