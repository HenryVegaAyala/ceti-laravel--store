<?php

use App\Http\Api\Product\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Api\Login\LoginController;
use App\Http\Api\Login\RegisterController;

Route::post("login", [LoginController::class, "__invoke"]);
Route::post("register", [RegisterController::class, "__invoke"]);

Route::get("products", [ProductController::class, "index"]);
Route::post("products", [ProductController::class, "create"]);
Route::get("products/{id}", [ProductController::class, "show"]);
Route::delete("products/{id}", [ProductController::class, "destroy"]);
Route::put("products/{id}", [ProductController::class, "update"]);
