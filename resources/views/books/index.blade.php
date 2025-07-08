@extends('layouts.app')

@section('title', 'Book List')

@section('content')
<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
  <h2 class="text-3xl font-bold text-gray-800 dark:text-white">📚 Book List</h2>
  <a href="{{ route('books.create') }}"
     class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-sm font-medium py-2 px-4 rounded-lg shadow transition-all">
    + Add Book
  </a>
</div>

<!-- Success Message -->
@if (session('success'))
  <div class="mb-4 p-4 rounded-lg bg-green-100 border border-green-200 text-green-800">
    <strong>{{ session('success') }}</strong>
  </div>
@endif

<!-- Error Message -->
@if (session('error'))
  <div class="mb-4 p-4 rounded-lg bg-red-100 border border-red-200 text-red-800">
    <strong>{{ session('error') }}</strong>
  </div>
@endif

<!-- Book Table -->
<div class="overflow-x-auto rounded-lg shadow-md bg-white dark:bg-gray-800">
  <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-100">
    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs tracking-wider">
      <tr>
        <th class="px-6 py-3">Title</th>
        <th class="px-6 py-3">Author</th>
        <th class="px-6 py-3">Year</th>
        <th class="px-6 py-3">Genre</th>
        <th class="px-6 py-3 text-right">Actions</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
      @forelse($books as $book)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
          <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $book->title }}</td>
          <td class="px-6 py-4">{{ $book->author }}</td>
          <td class="px-6 py-4">{{ $book->published_year ?? '-' }}</td>
          <td class="px-6 py-4">{{ $book->genre ?? '-' }}</td>
          <td class="px-6 py-4 text-right">
            <div class="flex justify-end gap-2">
              <!-- Edit Button -->
              <a href="{{ route('books.edit', $book->id) }}"
                 class="inline-flex items-center text-sm font-medium text-blue-600 hover:underline">
                ✏️ Edit
              </a>

              <!-- Delete Form -->
              <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this book?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center text-sm font-medium text-red-600 hover:underline">
                  🗑️ Delete
                </button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No books found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<!-- Pagination -->
<div class="mt-6">
  {{ $books->links('pagination::tailwind') }}
</div>
@endsection
