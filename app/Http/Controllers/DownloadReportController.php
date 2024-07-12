<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class DownloadReportController extends Controller
{
    public function __invoke(Report $report)
    {
        return Pdf::view('pdf', ['report' => $report])
            ->format('a4')
            ->name($report->site->name . '.pdf');
    }
}
