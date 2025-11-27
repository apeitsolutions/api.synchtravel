<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\frontend\admin_dashboard\SubscriptionsPackage\SubscriptionsPackageController;

Route::post('add_Subscriptions_Package',[SubscriptionsPackageController::class,'add_Subscriptions_Package']);
Route::post('subscriptions_Package_Details',[SubscriptionsPackageController::class,'subscriptions_Package_Details']);