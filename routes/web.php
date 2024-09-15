<?php

use App\Models\Report;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('admin');
})->middleware('throttle:60,1');

Route::get('/print/{report}', function ($report) {

    $report = Report::findOrFail($report);
    $day = \Carbon\Carbon::parse($report->date)->dayName;
    $date = $report->date;
    $time = \Carbon\Carbon::parse($report->time)->format('g:i A');
    $description = $report->state_description;
    $reporter = $report->reporter->name;

    return view('print', compact('day', 'date', 'time', 'reporter', 'description'));
})->middleware(['auth', 'throttle:60,1'])->name('print');
