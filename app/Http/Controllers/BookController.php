<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Shelf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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
        // Validasi 
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

       if ($request->hasFile('cover_book')) {
    $cover = $request->file('cover_book');
    $destinationPath = public_path('images/covers');
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }
    $filename = uniqid() . '_' . $cover->getClientOriginalName();
    $cover->move($destinationPath, $filename);
    $book->cover_book = 'images/covers/' . $filename;
    }

        $book->title_book = $request->title_book;
        $book->author_book = $request->author_book;
        $book->isbn_book = $request->isbn_book;
        $book->synopsis_book = $request->synopsis_book;
        $book->id_category = $request->id_category;
        $book->id_shelf = $request->id_shelf;

        $book->save();

        return redirect()->route('admin.book.index')->with('success', 'Book successfully added!');
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
    
        if ($book->cover_book && file_exists(public_path($book->cover_book))) {
            unlink(public_path($book->cover_book));
        }
    
        $cover = $request->file('cover_book');
        $destinationPath = public_path('images/covers');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $filename = uniqid() . '_' . $cover->getClientOriginalName();
        $cover->move($destinationPath, $filename);
        $book->cover_book = 'images/covers/' . $filename;
        }

        $book->title_book = $request->title_book;
        $book->author_book = $request->author_book;
        $book->isbn_book = $request->isbn_book;
        $book->synopsis_book = $request->synopsis_book;
        $book->id_category = $request->id_category;
        $book->id_shelf = $request->id_shelf;

        $book->save();

        return redirect()->route('admin.book.index')->with('success', 'Book successfully updated!');
    }

    public function delete(Request $request) {
        $id = decrypt($request->id);
        $book = Book::firstWhere('id', $id);

       
        if ($book->cover_book && file_exists(public_path($book->cover_book))) {
            unlink(public_path($book->cover_book));
        }

        $book->delete();

        return redirect()->route('admin.book.index')->with('success', 'Book successfully deleted!');
    }


//home page library

public function home(Request $request){
    $search = $request->query('search');
    $encryptedCategoryId = $request->query('category');
    $categoryId = null;

    // decrypt
    if ($encryptedCategoryId) {
        try {
            $categoryId = Crypt::decrypt($encryptedCategoryId);
        } catch (\Exception $e) {
            return redirect()->route('library.home')->with([
                'alertType' => 'error',
                'message' => 'Invalid category filter.'
            ]);
        }
    }

    $query = Book::query();

    if ($search) {
        $query->where('title_book', 'like', '%' . $search . '%');
    }

    if ($categoryId) {
        $query->where('id_category', $categoryId);  // Filter buku berdasarkan foreign key id_category
    }

    $books = $query->get();
    $categories = Category::all();

    $selectedCategoryName = null;
    if ($categoryId) {
        $category = $categories->where('id', $categoryId)->first();  // Filter berdasarkan id kategori
        $selectedCategoryName = $category ? $category->name_category : null;
    }

    // klo g ada hasil
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
            'alertType' => 'error',
            'selectedCategory' => $encryptedCategoryId,
            'searchTerm' => $search,
            'selectedCategoryName' => $selectedCategoryName
        ]);
    }

    // klo ada hasil
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
        'selectedCategory' => $encryptedCategoryId,
        'searchTerm' => $search,
        'selectedCategoryName' => $selectedCategoryName 
    ]);
}

}
