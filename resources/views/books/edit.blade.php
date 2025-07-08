@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-12">

        <h1 class="text-4xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-purple-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.232 5.232l3.536 3.536M16 11v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-8a2 2 0 012-2h6m5 5l-8-8" />
            </svg>
            Edit Book
        </h1>

        {{-- Unified Alert Box --}}
        @php
            $hasErrors = $errors->any();
            $hasSuccess = session('success');
            $serverError = $errors->first('error');
        @endphp

        @if ($hasErrors || $hasSuccess || $serverError)
            <div role="alert" aria-live="polite"
                class="mb-6 rounded-lg p-4 border shadow-sm
  {{ $hasErrors || $serverError ? 'bg-red-50 border-red-200 text-red-700' : '' }}
  {{ $hasSuccess && !($hasErrors || $serverError) ? 'bg-green-100 border-green-400 text-green-800' : '' }}
  ">
                @if ($hasErrors)
                    <strong class="font-semibold block mb-2">Please fix the following errors:</strong>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @elseif ($serverError)
                    <strong class="font-semibold block mb-2">Error:</strong>
                    <p class="text-sm">{{ $serverError }}</p>
                @elseif ($hasSuccess)
                    <p class="text-sm font-semibold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Book updated successfully.
                    </p>
                @endif
            </div>
        @endif

        <form id="editBookForm" method="POST" action="{{ route('books.update', $book->id) }}"
            class="bg-white shadow-lg rounded-2xl p-8 border border-gray-200" novalidate>
            @csrf
            @method('PUT')

            <div class="space-y-6">

                <!-- Title -->
                <div>
                    <label for="title" class="block mb-2 font-semibold text-gray-700">
                        Book Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $book->title) }}" required
                        aria-required="true" aria-describedby="title-error" placeholder="Enter book title"
                        class="w-full px-4 py-3 border rounded-xl border-gray-300
          focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500
          placeholder-gray-400 transition duration-150
          @error('title') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" />
                    @error('title')
                        <p id="title-error" class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Author -->
                <div>
                    <label for="author" class="block mb-2 font-semibold text-gray-700">
                        Author <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="author" name="author" value="{{ old('author', $book->author) }}" required
                        aria-required="true" aria-describedby="author-error" placeholder="Enter author's name"
                        class="w-full px-4 py-3 border rounded-xl border-gray-300
          focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500
          placeholder-gray-400 transition duration-150
          @error('author') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" />
                    @error('author')
                        <p id="author-error" class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Published Year and Genre -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label for="published_year" class="block mb-2 font-semibold text-gray-700">Published Year</label>
                        <input type="number" id="published_year" name="published_year" min="1000"
                            max="{{ date('Y') }}" value="{{ old('published_year', $book->published_year) }}"
                            aria-describedby="year-error" placeholder="e.g., 2024"
                            class="w-full px-4 py-3 border rounded-xl border-gray-300
            focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500
            placeholder-gray-400 transition duration-150
            @error('published_year') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" />
                        @error('published_year')
                            <p id="year-error" class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="genre" class="block mb-2 font-semibold text-gray-700">Genre</label>
                        <select id="genre" name="genre" aria-describedby="genre-error"
                            class="w-full px-4 py-3 border rounded-xl border-gray-300
            focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500
            transition duration-150 hover:border-purple-300
            @error('genre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="">Select genre</option>
                            @php
                                $genres = [
                                    'Fiction',
                                    'Non-Fiction',
                                    'Mystery',
                                    'Romance',
                                    'Science Fiction',
                                    'Fantasy',
                                    'Biography',
                                    'History',
                                    'Self-Help',
                                    'Business',
                                    'Technology',
                                    'Other',
                                ];
                            @endphp
                            @foreach ($genres as $genre)
                                <option value="{{ $genre }}"
                                    {{ old('genre', $book->genre) === $genre ? 'selected' : '' }}>{{ $genre }}
                                </option>
                            @endforeach
                        </select>
                        @error('genre')
                            <p id="genre-error" class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <button id="submitBtn" type="submit"
                    class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700
        text-white font-semibold py-3 rounded-xl shadow-lg transition transform hover:scale-[1.02]
        active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-purple-300 flex justify-center items-center"
                    aria-live="polite">
                    <svg id="loadingSpinner" class="hidden animate-spin h-5 w-5 mr-3 text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    Save Changes
                </button>

                <a href="{{ route('books.index') }}"
                    class="flex-1 sm:flex-none text-center bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-xl py-3 font-semibold text-gray-700 transition focus:outline-none focus:ring-4 focus:ring-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        // Show loading spinner and disable submit on form submit
        const form = document.getElementById('editBookForm');
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('loadingSpinner');

        form.addEventListener('submit', () => {
            submitBtn.disabled = true;
            spinner.classList.remove('hidden');
            submitBtn.setAttribute('aria-busy', 'true');
        });
    </script>
@endsection
