<?php

use App\Http\Controllers\DownloadReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('admin');
});

Route::get('/pdf', function () {
    return view('pdf');
});

Route::get('pdf/{report}', DownloadReportController::class)->name('pdf'); 