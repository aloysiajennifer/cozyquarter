<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Book;
use App\Models\Fine;
use Illuminate\Support\Carbon;

class BorrowingController extends Controller
{
    public function index(Request $request) {
        $search = $request->query('search');
        $startDate = $request->query('start_date');
        $startDate = $request->query('start_date');
        
        $query = Borrowing::with(['user', 'book', 'fine']);
        
        if ($search) {
            // Cari user dan book yang namanya cocok
            $userIds = User::where('name', 'like', "%$search%")->pluck('id');
            $bookIds = Book::where('title_book', 'like', "%$search%")->pluck('id');
             // Filter berdasarkan id_user atau id_book
            $query->where(function ($q) use ($userIds, $bookIds) {
                $q->whereIn('id_user', $userIds)
                ->orWhereIn('id_book', $bookIds);
            });
        }

        // Tampilkan semua borrowing
        $borrowings = $query->get();
        

        return view('admin.borrowing.index')->with('borrowings', $borrowings);
    }

    // Fungsi update status returned
    public function returned(Request $request) {
        $borrowing = Borrowing::find($request->id);
        if (!$borrowing) {
            return redirect()->back()->with('error', 'Borrowing not found.');
        }
        if ($borrowing->status_returned == 1) {
            return redirect()->back()->with('error', 'The book has been returned.');
        }

        $borrowing->status_returned = 1;
        $borrowing->return_date = now();
        $borrowing->save();

        // Update status buku jadi available
        $borrowing->book->status_book = 1;
        $borrowing->book->save();


        // HITUNG DENDA
        $returnDue = Carbon::parse($borrowing->return_due)->startOfDay();
        $returnDate = Carbon::parse($borrowing->return_date)->startOfDay();
        // Cek returnDate nya melebihi returnDue tidak
        if ($returnDate->greaterThan($returnDue)) {
            $daysLate = $returnDue->diffInDays($returnDate);
            $fineAmount = 1000; // Denda per hari

            // Simpan atau update denda
            $fine = Fine::updateOrCreate(
                ['id_borrowing' => $borrowing->id],
                ['fine_total' => $daysLate * $fineAmount]
            );
        }


        return redirect()->back()->with('success', 'The return status has been updated successfully!');
    }

    // Form create
        public function form() {
        try {
            $listUsers = User::all();
            $listBooks = Book::where('status_book', 1)->get();
            $borrowingDate = Carbon::now();
            $returnDue = Carbon::now()->addDays(7)->setTime(23, 59, 59);
            // $borrowingDate = Carbon::parse('2025-05-01 10:00:00');  // contoh tanggal pinjam sudah lalu
            // $returnDue = Carbon::parse('2025-05-08 23:59:59');      // contoh tanggal harus kembali (7 hari kemudian)

            return view('admin.borrowing.form', compact('listUsers', 'listBooks', 'borrowingDate', 'returnDue'));
        } 
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Oops! User not available');
        }
    }

    
    public function insert(Request $request) {
        // Validasi input
        $request->validate([
            'id_user' => 'required|exists:users,id',
            'id_book' => 'required|exists:book,id'
        ]);

        $borrowing = new Borrowing;
        $borrowing->id_user = $request->id_user;
        $borrowing->id_book = $request->id_book;
        $borrowing->borrowing_date = Carbon::parse($request->borrowing_date);
        $borrowing->return_due = Carbon::parse($request->return_due);
        $borrowing->status_returned = 0;
        $borrowing->save();

        // Update status buku jadi unavailable
        $borrowing->book->status_book = 0;
        $borrowing->book->save();

        return redirect()->route('borrowing.index')->with('success', 'Borrowing saved successfully!');
    }


}
