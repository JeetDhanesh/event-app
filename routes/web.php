<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', [EventController::class, 'frontend']);
Route::get('/admin', [EventController::class, 'admin']);

Route::get('/fetch-events', [EventController::class, 'fetchEvents']);
Route::post('/events', [EventController::class, 'store']);
Route::get('/events/{id}/edit', [EventController::class, 'edit']);
Route::post('/events/{id}/update', [EventController::class, 'update']);
Route::post('/events/{id}/delete', [EventController::class, 'destroy']);