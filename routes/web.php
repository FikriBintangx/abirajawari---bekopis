<?php

use App\Http\Controllers\ExcelController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', [ExcelController::class, 'index']);
Route::post('/admin/sheets', [ExcelController::class, 'store']);
Route::put('/admin/sheets/{id}', [ExcelController::class, 'update']);
Route::delete('/admin/sheets/{id}', [ExcelController::class, 'destroy']);
