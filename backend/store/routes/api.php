<?php

use Illuminate\Support\Facades\Route;
use App\Http\Api\Login\LoginController;
use App\Http\Api\Login\RegisterController;

Route::post("login", [LoginController::class, "__invoke"]);
Route::post("register", [RegisterController::class, "__invoke"]);
