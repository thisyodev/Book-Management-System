@extends('layouts.app')

@section('title', 'Add Book')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">

  <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
    </svg>
    Add New Book
  </h1>

  {{-- Alert Box --}}
  @php
    $hasErrors = $errors->any();
    $hasSuccess = session('success');
  @endphp
  @if ($hasErrors || $hasSuccess)
    <div role="alert" class="mb-6 rounded-lg p-4 border shadow-sm
      {{ $hasErrors ? 'bg-red-50 border-red-200 text-red-700' : '' }}
      {{ $hasSuccess ? 'bg-green-50 border-green-200 text-green-800' : '' }}">
      @if ($hasErrors)
        <strong class="font-semibold block mb-2">Please fix the following errors:</strong>
        <ul class="list-disc list-inside space-y-1 text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      @elseif ($hasSuccess)
        <p class="text-sm font-semibold">{{ $hasSuccess }}</p>
      @endif
    </div>
  @endif

  {{-- Form --}}
  <form id="addBookForm" method="POST" action="{{ route('books.store') }}" class="bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-2xl p-8" novalidate>
    @csrf

    <div class="space-y-6">

      <!-- Title -->
      <div>
        <label for="title" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">
          Book Title <span class="text-red-500">*</span>
        </label>
        <input type="text" id="title" name="title" value="{{ old('title') }}"
          class="w-full px-4 py-3 border rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500
          focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition
          @error('title') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
          placeholder="Enter book title" required />
        @error('title')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Author -->
      <div>
        <label for="author" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">
          Author <span class="text-red-500">*</span>
        </label>
        <input type="text" id="author" name="author" value="{{ old('author') }}"
          class="w-full px-4 py-3 border rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500
          focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition
          @error('author') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
          placeholder="Enter author name" required />
        @error('author')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Published Year & Genre -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="published_year" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Published Year</label>
          <input type="number" id="published_year" name="published_year" min="1000" max="{{ date('Y') }}"
            class="w-full px-4 py-3 border rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-white placeholder-gray-400 dark:placeholder-gray-500
            focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition
            @error('published_year') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
            placeholder="e.g., 2024" value="{{ old('published_year') }}" />
          @error('published_year')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="genre" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Genre</label>
          <select id="genre" name="genre"
            class="w-full px-4 py-3 border rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-white
            focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition
            @error('genre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
            <option value="">-- Select Genre --</option>
            @php
              $genres = ['Fiction','Non-Fiction','Mystery','Romance','Sci-Fi','Fantasy','Biography','Business'];
            @endphp
            @foreach ($genres as $g)
              <option value="{{ $g }}" {{ old('genre') == $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
          </select>
          @error('genre')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mt-8 flex flex-col sm:flex-row gap-4">
        <button
          id="submitBtn"
          type="submit"
          class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold py-3 rounded-xl shadow-lg transition transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-purple-300 flex justify-center items-center"
          aria-live="polite"
        >
          <svg id="loadingSpinner" class="hidden animate-spin h-5 w-5 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
          </svg>
          Save Book
        </button>

        <a href="{{ route('books.index') }}"
          class="flex-1 sm:flex-none text-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-white font-semibold py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-300 dark:border-gray-600 transition focus:outline-none focus:ring-4 focus:ring-gray-300">
          Cancel
        </a>
      </div>

    </div>
  </form>
</div>

<script>
  const form = document.getElementById('addBookForm');
  const submitBtn = document.getElementById('submitBtn');
  const spinner = document.getElementById('loadingSpinner');

  form.addEventListener('submit', () => {
    submitBtn.disabled = true;
    spinner.classList.remove('hidden');
    submitBtn.setAttribute('aria-busy', 'true');
  });
</script>
@endsection
