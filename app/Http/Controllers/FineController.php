<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;

class FineController extends Controller
{
    // Fungsi update status paid
    public function paid(Request $request) {
        $fine = Fine::find($request->id);
        if (!$fine) {
            return redirect()->back()->with('error', 'Fine not found.');
        }
        if ($fine->status_fine == 1) {
            return redirect()->back()->with('error', 'The fine has been paid.');
        }

        $fine->status_fine = 1;
        $fine->date_finepayment = now();
        
        $fine->save();

        return redirect()->back()->with('success', 'The fine status has been updated successfully!');
    }
}
