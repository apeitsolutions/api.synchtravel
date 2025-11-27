<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\website\WebsiteController;

// index Page Routes
Route::post('get_website_index_data',[WebsiteController::class,'get_website_index_data']);
Route::post('search_tours',[WebsiteController::class,'search_tours']); 
Route::post('filter_tour',[WebsiteController::class,'filter_tour']); 

Route::post('get_package_detail',[WebsiteController::class,'get_package_detail']); 

