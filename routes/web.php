<?php

use App\Http\Controllers\DownloadReportController;
use App\Models\Report;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('admin');
})->middleware('throttle:60,1');

Route::get('/print/{report}', function ($report) {

    $report = Report::findOrFail($report);
    return view('print', ['report' => $report]);
})->middleware(['auth', 'throttle:60,1'])->name('print');
