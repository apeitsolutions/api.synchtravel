<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ShowInterestController;

// Show Interest Routes
Route::post('create_ShowInterest',[ShowInterestController::class,'create_ShowInterest']);
Route::post('view_ShowInterest',[ShowInterestController::class,'view_ShowInterest']);
Route::post('view_ShowInterest_2024',[ShowInterestController::class,'view_ShowInterest_2024']);
Route::post('edit_ShowInterest',[ShowInterestController::class,'edit_ShowInterest']);
Route::post('update_ShowInterest',[ShowInterestController::class,'update_ShowInterest']);
Route::post('deleteseat_ShowInterest',[ShowInterestController::class,'deleteseat_ShowInterest']);