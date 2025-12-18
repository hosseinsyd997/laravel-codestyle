<?php

use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::get('/countries', [CountryController::class, 'index']);
