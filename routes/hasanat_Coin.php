<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\frontend\admin_dashboard\HasanatCoin\HasanatCoinController;

Route::post('add_Hasanat_Coin_Limit',[HasanatCoinController::class,'add_Hasanat_Coin_Limit']);
Route::post('create_Hasanat_Coin_Limit',[HasanatCoinController::class,'create_Hasanat_Coin_Limit']);
Route::post('utilize_Hasanat_Coins',[HasanatCoinController::class,'utilize_Hasanat_Coins']);
Route::post('hasanat_Coins_Statament',[HasanatCoinController::class,'hasanat_Coins_Statament']);

Route::post('create_Discount_Coins',[HasanatCoinController::class,'create_Discount_Coins']);
Route::post('add_Discount_Coins',[HasanatCoinController::class,'add_Discount_Coins']);
Route::post('view_Discount_Coins',[HasanatCoinController::class,'view_Discount_Coins']);
Route::post('check_Discount_Coins',[HasanatCoinController::class,'check_Discount_Coins']);
Route::post('edit_Discount_Coins',[HasanatCoinController::class,'edit_Discount_Coins']);
Route::post('update_Discount_Coins',[HasanatCoinController::class,'update_Discount_Coins']);

Route::post('create_Purchase_Coins',[HasanatCoinController::class,'create_Purchase_Coins']);
Route::post('get_Booked_Coins_Details',[HasanatCoinController::class,'get_Booked_Coins_Details']);
Route::post('view_Purchase_Coins',[HasanatCoinController::class,'view_Purchase_Coins']);
Route::post('add_Purchase_Coins',[HasanatCoinController::class,'add_Purchase_Coins']);

Route::post('create_Hasanat_Credits',[HasanatCoinController::class,'create_Hasanat_Credits']);
Route::post('add_Hasanat_Credits',[HasanatCoinController::class,'add_Hasanat_Credits']);
Route::post('renew_Hasanat_Credits',[HasanatCoinController::class,'renew_Hasanat_Credits']);
Route::post('hasanat_Credit_Statement',[HasanatCoinController::class,'hasanat_Credit_Statement']);
Route::post('hasanat_Coin_Statement',[HasanatCoinController::class,'hasanat_Coin_Statement']);