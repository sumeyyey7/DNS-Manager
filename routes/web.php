<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dnsmanage;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\DomainController;


Route::get('/login',[Dnsmanage::class,'login']);
Route::post('/login',[Dnsmanage::class,'authenticate']);

Route::get('/dashboard',[Dashboard::class,'dashboard']);

Route::get('/domains', [DomainController::class, 'index']);
Route::get('/domains/create', [DomainController::class, 'create']);
Route::post('/domains', [DomainController::class, 'store']);

