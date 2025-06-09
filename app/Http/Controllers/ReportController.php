<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // Borrowing
    public function borrowing(Request $request) {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        $query = Borrowing::with(['user', 'book', 'fine'])->orderBy('borrowing_date', 'desc');

        // FILTER
        if ($startDate && $endDate) {
            $query->whereBetween('borrowing_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $query->where('borrowing_date', '>=', $startDate . ' 00:00:00');
        } elseif ($endDate) {
            $query->where('borrowing_date', '<=', $endDate . ' 23:59:59');
        }

        $borrowings = $query->get();
        
        return view('admin.reports.borrowing')->with('borrowings', $borrowings);
    }

    // Generate Borrowing PDF report
    public function borrowingPDF(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        $query = Borrowing::with(['user', 'book', 'fine'])->orderBy('borrowing_date', 'asc');

        // FILTER
        if ($startDate && $endDate) {
            $query->whereBetween('borrowing_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $query->where('borrowing_date', '>=', $startDate . ' 00:00:00');
        } elseif ($endDate) {
            $query->where('borrowing_date', '<=', $endDate . ' 23:59:59');
        }

        $borrowings = $query->get();

        // Load view khusus (template) PDF 
        $pdf = PDF::loadView('admin.reports.borrowingPDF', compact('borrowings'));

        // Download PDF dengan nama file
        return $pdf->download('borrowing_report.pdf');
    }

}
