<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Dnsmanage;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\DnsRecord;
use App\Http\Controllers\LogController;


// Authentication
Route::get('/login', [Dnsmanage::class, 'login']);
Route::post('/login', [Dnsmanage::class, 'authenticate']);
Route::get('/logout', [Dnsmanage::class, 'logout']);

//Dashboard
Route::get('/dashboard', [Dashboard::class, 'dashboard']);

//Domains
Route::get('/domains', [DomainController::class, 'index']);
Route::post('/domains', [DomainController::class, 'store']);
Route::get('/domains/{id}/edit', [DomainController::class, 'edit']);
Route::put('/domains/{id}', [DomainController::class, 'update']);
Route::delete('/domains/{id}', [DomainController::class, 'destroy']);


//DNS Records
Route::get('/dns-records', [DnsRecord::class, 'index']);
Route::get('/dns-records/create', [DnsRecord::class, 'create']);
Route::post('/dns-records', [DnsRecord::class, 'store']);
Route::get('/dns-records/{id}/edit', [DnsRecord::class, 'edit']);
Route::put('/dns-records/{id}', [DnsRecord::class, 'update']);
Route::delete('/dns-records/{id}', [DnsRecord::class, 'destroy']);

// Logs
Route::get('/logs', [LogController::class, 'index']);

//Test
Route::get('/test-record', [Dashboard::class, 'testRecord']);

Route::get('/reload-test', function () {});

Route::get('/upload-test', function () {});

Route::post('/nat-search', [DnsRecord::class, 'findNatIp']);


