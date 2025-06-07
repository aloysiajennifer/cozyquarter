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
        $endDate = $request->query('end_date');
        
        $query = Borrowing::with(['user', 'book', 'fine'])->orderBy('borrowing_date', 'desc');
        
        // SEARCH
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

        // FILTER
        if ($startDate && $endDate) {
            $query->whereBetween('borrowing_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } elseif ($startDate) {
            $query->where('borrowing_date', '>=', $startDate . ' 00:00:00');
        } elseif ($endDate) {
            $query->where('borrowing_date', '<=', $endDate . ' 23:59:59');
        }


        // Tampilkan semua borrowing
        $borrowings = $query->get();
        

        // HITUNG DENDA REALTIME (tidak disimpan dalam database)
            $fineAmount = 1000;
            foreach ($borrowings as $borrowing) {       // semua borrowing dicek
                $returnDue = \Carbon\Carbon::parse($borrowing->return_due)->startOfDay();

                // Jika sudah dikembalikan
                if ($borrowing->return_date) {
                    $returnDate = \Carbon\Carbon::parse($borrowing->return_date)->startOfDay();

                    if ($returnDate->greaterThan($returnDue)) {
                        $daysLate = $returnDue->diffInDays($returnDate);
                        $totalFine = $daysLate * $fineAmount;
                        $borrowing->fine_realtime = $totalFine;
                    } else {
                        $borrowing->fine_realtime = 0;
                    }
                } else {
                    // Belum dikembalikan, hitung denda realtime
                    $today = \Carbon\Carbon::now()->startOfDay();

                    if ($today->greaterThan($returnDue)) {
                        $daysLate = $returnDue->diffInDays($today);
                        $borrowing->fine_realtime = $daysLate * $fineAmount;
                    } else {
                        $borrowing->fine_realtime = 0;
                    }
                }
            }

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


        // OPSI 1: HITUNG DENDA setelah direturn
        $returnDue = Carbon::parse($borrowing->return_due)->startOfDay();
        $returnDate = Carbon::parse($borrowing->return_date)->startOfDay();
        $fineAmount = 1000; // Denda per hari
        // Cek returnDate nya melebihi returnDue tidak
        if ($returnDate->greaterThan($returnDue)) {
            $daysLate = $returnDue->diffInDays($returnDate);

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
            $listUsers = User::orderBy('name', 'asc')->get();
            $listBooks = Book::where('status_book', 1)->orderBy('title_book', 'asc')->get();
            $borrowingDate = Carbon::now();
            $returnDue = Carbon::now()->addDays(7)->setTime(23, 59, 59);
            // $borrowingDate = Carbon::parse('2025-05-01 10:00:00');  // contoh tanggal pinjam sudah lalu
            // $returnDue = Carbon::parse('2025-05-12 23:59:59');      // contoh tanggal harus kembali (7 hari kemudian)

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


    // Books Borrowed (user)
    public function borrowed(Request $request) {
        // $user = Auth::user(); // Ambil user yang sedang login

        // Semua buku yang sedang dipinjam user ini (belum returned dan/atau belum bayar denda)
        $borrowings = Borrowing::with('book')
                        // ->where('user_id', $user->id)
                        ->where(function ($query) {
                            $query->where('status_returned', false) 
                                    ->orWhereHas('fine', function ($fineQuery) {
                                        $fineQuery->where('status_fine', false);
                                    });
                        })
                        ->get();

        return view('user.library.booksBorrowed')->with('borrowings', $borrowings);
    }

}
