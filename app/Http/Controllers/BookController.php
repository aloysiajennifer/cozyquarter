<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Shelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    //crud book
   public function index(Request $request){
    $search = $request->query('search');

    if ($search) {
        $books = Book::where('title_book', 'like', '%' . $search . '%')->get();

        if ($books->isEmpty()) {
            return view('admin.book.index', [
                'books' => [],
                'message' => "There's no book titled \"$search\".",
                'alertType' => 'error'
            ]);
        }

        return view('admin.book.index', [
            'books' => $books,
            'message' => "Search result for \"$search\".",
            'alertType' => 'info'

        ]);
    }

    $books = Book::get();
    return view('admin.book.index', [
        'books' => $books,
        'message' => null,
        'alertType' => null
    ]);
    }

    public function form() {
        $categories = Category::all();
        $shelves = Shelf::all();

        return view('admin.book.form', [
            'categories' => $categories,
            'shelves' => $shelves
        ]);
    }

    public function insert(Request $request) {
        // Validasi input
        $request->validate([
            'title_book' => 'required',
            'author_book' => 'required',
            'isbn_book' => 'required|unique:book,isbn_book',
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

        return redirect()->route('book.index')->with('success', 'Book successfully added!');
    }

    public function detail(Request $request) {
        $id = decrypt($request->id);
        $book = Book::findOrFail($id);

        $categories = Category::all();
        $shelves = Shelf::all();

        return view('admin.book.detail', [
            'book' => $book,
            'categories' => $categories,
            'shelves' => $shelves
        ]);
    }

    public function update(Request $request) {
        $id = decrypt($request->id);

        $request->validate([
            'title_book' => 'required',
            'author_book' => 'required',
            'isbn_book' => 'required|unique:book,isbn_book,' . $id,
            'synopsis_book' => 'required',
            'cover_book' => 'image|mimes:jpg,jpeg,png|max:2048',
            'id_category' => 'required',
            'id_shelf' => 'required'
        ]);

        $book = Book::find($id);

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

        return redirect()->route('book.index')->with('success', 'Book successfully updated!');
    }

    public function delete(Request $request) {
        $id = decrypt($request->id);
        $book = Book::firstWhere('id', $id);

        // Hapus file cover dari storage
        if ($book->cover_book && Storage::disk('public')->exists($book->cover_book)) {
            Storage::disk('public')->delete($book->cover_book);
        }

        $book->delete();

        return redirect()->route('book.index')->with('success', 'Book successfully deleted!');
    }



//user home
    public function home(Request $request){
        $search = $request->query('search');
        $categoryId = $request->query('category');

        $query = Book::query();

        if ($search) {
            $query->where('title_book', 'like', '%' . $search . '%');
        }

        if ($categoryId) {
            $query->where('id_category', $categoryId);
        }

        $books = $query->get();
        $categories = Category::all();

    
        $selectedCategoryName = null;
        if ($categoryId) {
            $category = $categories->where('id_category', $categoryId)->first();
            $selectedCategoryName = $category ? $category->name_category : null;
        }

        // no result
        if ($books->isEmpty()) {
            $message = "No result found";
            if ($search && $selectedCategoryName) {
                $message .= " for title \"$search\" in category \"$selectedCategoryName\".";
            } elseif ($search) {
                $message .= " for title \"$search\".";
            } elseif ($selectedCategoryName) {
                $message .= " in category \"$selectedCategoryName\".";
            }

            return view('user.library.home', [
            'books' => [],
            'categories' => $categories,
            'message' => $message,
            'alertType' => 'error', // gunakan 'error' saat tidak ada hasil
            'selectedCategory' => $categoryId,
            'searchTerm' => $search
            ]);
        }

    // ada result
        $message = null;
        $alertType = null;
        if ($search) {
            $message = "Search result for \"$search\"";
            if ($selectedCategoryName) {
                $message .= " in category \"$selectedCategoryName\"";
            }
            $message .= ".";
            $alertType = 'info';
        } elseif ($selectedCategoryName) {
            $message = "Books in category \"$selectedCategoryName\".";
            $alertType = 'info';
        }

        return view('user.library.home', [
        'books' => $books,
        'categories' => $categories,
        'message' => $message,
        'alertType' => $alertType,
        'selectedCategory' => $categoryId,
        'searchTerm' => $search
        ]);
}

}
