<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\User;

class BorrowingController extends Controller
{
    public function index() {
        $borrowings = Borrowing::with(['user', 'book', 'fine'])->get();
        return view('admin.borrowing.index')->with('borrowings', $borrowings);
    }

    public function form() {
        try {
             $listUsers = User::all();
            return view('admin.borrowing.form', compact('listUsers'));
        } 
        catch (\Exception $e) {
            return redirect()->back()->with('error', 'Oops! User not available');
        }
    }

    // public function insert(Request $request) {
    //     // Validasi input
    //     // $request->validate([
    //     //     'title_book' => 'required',
    //     //     'author_book' => 'required',
    //     //     'isbn_book' => 'required',
    //     //     'synopsis_book' => 'required',
    //     //     'cover_book' => 'image|mimes:jpg,jpeg,png|max:2048',
    //     //     'id_category' => 'required',
    //     //     'id_shelf' => 'required'
    //     // ]);

    //     // $book = new Book;

    //     // // Simpan cover jika ada
    //     // if ($request->hasFile('cover_book')) {
    //     //     $coverPath = $request->file('cover_book')->store('covers', 'public');
    //     //     $book->cover_book = $coverPath;
    //     // }

    //     // $book->title_book = $request->title_book;
    //     // $book->author_book = $request->author_book;
    //     // $book->isbn_book = $request->isbn_book;
    //     // $book->synopsis_book = $request->synopsis_book;
    //     // $book->id_category = $request->id_category;
    //     // $book->id_shelf = $request->id_shelf;

    //     // $book->save();

    //     // return redirect()->route('book.index');
    // }

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

        return redirect()->back()->with('success', 'The return status has been updated successfully!');
    }

}
