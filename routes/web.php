<?php

use Illuminate\Support\Facades\Route;




use App\Http\Controllers\FontController;

Route::get('/{any}', [FontController::class, 'index'])->where('any', '.*');




Route::view('/{any}', 'app')->where('any', '.*');








