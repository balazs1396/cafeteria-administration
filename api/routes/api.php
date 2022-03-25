<?php

use App\Http\Controllers\CafeteriaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('cafeteria', [CafeteriaController::class, 'store']);
