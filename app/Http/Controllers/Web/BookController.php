<?php

namespace App\Http\Controllers\Web;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::paginate(10);
        return view('books.index', compact('books'));
    }
    public function create()
    {
        return view('books.create');
    }
    public function store(Request $req)
    {
        $req->validate([
            'title' => 'required',
            'author' => 'required'
        ]);
        Book::create($req->only('title', 'author', 'published_year', 'genre'));
        return redirect()->route('books.index')->with('success', 'เพิ่มแล้ว');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        // Validate input data
        $validatedData = $request->validate([
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'genre'          => 'nullable|string|max:100',
        ]);

        try {
            // Update book record
            $book->update($validatedData);
            // Redirect to books list with success message
            return redirect()->route('books.index', $book->id)->with('success', 'Book updated successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Book update failed: ' . $e->getMessage(), [
                'book_id' => $book->id,
                'user_id' => auth()->id(),
                'input'   => $validatedData,
            ]);

            // Redirect back with error message and input
            return redirect()->route('books.edit', $book->id)->with('error', 'Book updated failed.');
        }
    }

    public function destroy(Book $book)
    {
        try {
            $book->delete();
            Log::info("Book deleted: ID {$book->id} by " . auth()->user()->id);
            return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Delete failed for book ID {$book->id}: " . $e->getMessage());
            return redirect()->route('books.index')->with('error', 'Failed to delete book. Please try again.');
        }
    }
}
