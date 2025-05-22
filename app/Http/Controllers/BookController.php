<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index() {
        $books = Book::get();
        return view('admin.book.index')->with('books', $books);
    }

    public function form() {
        return view('admin.book.form');
    }

    public function insert(Request $request) {
        // Validasi input
        $request->validate([
            'title_book' => 'required',
            'author_book' => 'required',
            'isbn_book' => 'required',
            'synopsis_book' => 'required',
            'cover_book' => 'image|mimes:jpg,jpeg,png|max:2048',
            'id_category' => 'required',
            'id_shelf' => 'required'
        ]);

        $book = new Book;

        // Simpan cover jika ada
        if ($request->hasFile('cover_book')) {
            $coverPath = $request->file('cover_book')->store('covers', 'public');
            $book->cover_book = $coverPath;
        }

        $book->title_book = $request->title_book;
        $book->author_book = $request->author_book;
        $book->isbn_book = $request->isbn_book;
        $book->synopsis_book = $request->synopsis_book;
        $book->id_category = $request->id_category;
        $book->id_shelf = $request->id_shelf;

        $book->save();

        return redirect()->route('book.index');
    }

    public function detail(Request $request) {
        $book = Book::where('id', $request->id)->first();
        return view('admin.book.detail')->with('book', $book);
    }

    public function update(Request $request) {
        $request->validate([
            'title_book' => 'required',
            'author_book' => 'required',
            'isbn_book' => 'required',
            'synopsis_book' => 'required',
            'cover_book' => 'image|mimes:jpg,jpeg,png|max:2048',
            'id_category' => 'required',
            'id_shelf' => 'required'
        ]);

        $book = Book::find($request->id);

        if ($request->hasFile('cover_book')) {
            // Hapus cover lama jika ada
            if ($book->cover_book && Storage::disk('public')->exists($book->cover_book)) {
                Storage::disk('public')->delete($book->cover_book);
            }
            $coverPath = $request->file('cover_book')->store('covers', 'public');
            $book->cover_book = $coverPath;
        }

        $book->title_book = $request->title_book;
        $book->author_book = $request->author_book;
        $book->isbn_book = $request->isbn_book;
        $book->synopsis_book = $request->synopsis_book;
        $book->id_category = $request->id_category;
        $book->id_shelf = $request->id_shelf;

        $book->save();

        return redirect()->route('book.index');
    }

    public function delete(Request $request) {
        $book = Book::firstWhere('id', decrypt($request->id));

        // Hapus file cover dari storage
        if ($book->cover_book && Storage::disk('public')->exists($book->cover_book)) {
            Storage::disk('public')->delete($book->cover_book);
        }

        $book->delete();

        return redirect()->route('book.index');
    }
}
