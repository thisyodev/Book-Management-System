<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;


class BookApiController extends Controller
{
    /**
     * @group Books API
     *
     * Get list of books (paginated)
     *
     * @response 200 {
     *   "data": [
     *     {
     *       "id": 1,
     *       "title": "Book Title",
     *       "author": "John Doe",
     *       "published_year": 2024,
     *       "genre": "Fiction"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        return Book::paginate(10);
    }

    /**
     * @group Books API
     *
     * Create a new book
     *
     * @bodyParam title string required The title of the book. Example: The Alchemist
     * @bodyParam author string required Author of the book. Example: Paulo Coelho
     * @bodyParam published_year integer The year it was published. Example: 1988
     * @bodyParam genre string The genre. Example: Fiction
     *
     * @response 201 {
     *   "id": 10,
     *   "title": "The Alchemist",
     *   "author": "Paulo Coelho",
     *   "published_year": 1988,
     *   "genre": "Fiction"
     * }
     */
    public function store(Request $req)
    {
        $data = $req->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'published_year' => 'nullable|integer',
            'genre' => 'nullable|string|max:100',
        ]);
        $book = Book::create($data);
        return response()->json($book, 201);
    }


    /**
     * @group Books API
     *
     * Update a book
     *
     * @urlParam id int required The ID of the book. Example: 1
     *
     * @bodyParam title string required The title of the book. Example: The Alchemist
     * @bodyParam author string required Author of the book. Example: Paulo Coelho
     * @bodyParam published_year integer The year it was published. Example: 1988
     * @bodyParam genre string The genre. Example: Fiction
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "The Alchemist",
     *   "author": "Paulo Coelho",
     *   "published_year": 1988,
     *   "genre": "Fiction"
     * }
     */
    public function update(Request $req, $id)
    {
        try {
            $data = $req->validate([
                'title' => 'required|string',
                'author' => 'required|string',
                'published_year' => 'nullable|integer',
                'genre' => 'nullable|string|max:100',
            ]);

            $book = Book::findOrFail($id);
            $book->update($data);

            return response()->json($book);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unexpected error'], 500);
        }
    }

    /**
     * @group Books API
     *
     * Delete a book
     *
     * @urlParam id int required The ID of the book. Example: 1
     *
     * @response 200 {
     *   "message": "Deleted"
     * }
     */
    public function destroy($id)
    {
        try {
            Book::destroy($id);
            Log::info("Book deleted via API: ID {$id}");
            return response()->json(['message' => 'Deleted']);
        } catch (\Exception $e) {
            Log::error("API Delete failed: {$e->getMessage()}");
            return response()->json(['error' => 'Delete failed'], 500);
        }
    }
}
