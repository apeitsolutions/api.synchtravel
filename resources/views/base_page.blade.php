@extends('template/frontend/userdashboard/layout/default')
@section('content')
@if(session()->has('message'))
<div x-data="{ show: true }" x-show="show"
     class="flex justify-between items-center bg-yellow-200 relative text-yellow-600 py-3 px-3 rounded-lg">
    <div>
        <span class="font-semibold text-yellow-700"> {{session()->get('message')}}</span>
    </div>
    <div>
        <button type="button" @click="show = false" class=" text-yellow-700">
            <span class="text-2xl">&times;</span>
        </button>
    </div>
</div>
@endif
<div class="intro-y box mt-5" style="width: 935px;" >
    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
        <div class="w-full sm:w-auto flex items-center sm:ml-auto mt-3 sm:mt-0">
            
        </div>
    </div>
</div>
@endsection