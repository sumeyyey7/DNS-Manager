<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dnsmanage;
use App\Http\Controllers\Dashboard;


Route::get('/login',[Dnsmanage::class,'login']);
Route::post('/login',[Dnsmanage::class,'authenticate']);

Route::get('/dashboard',[Dashboard::class,'dashboard']);


