@extends('layouts.app')

@section('content')
<div class="min-h-screen w-full flex items-center justify-center bg-gradient-to-tr from-[#f8f4f1] via-[#e6dcd5] to-[#ded1c7] overflow-hidden relative font-sans">

  <!-- Optional subtle bookshelf background image -->
  <div class="absolute inset-0 bg-[url('/images/bookstore-bg.jpg')] bg-cover bg-center opacity-10 mix-blend-multiply"></div>
  <div class="absolute inset-0 bg-white/30 backdrop-blur-[2px]"></div>

  <!-- Form Container -->
  <div class="relative z-10 w-full max-w-md p-8 sm:p-10 bg-white/80 backdrop-blur-md rounded-2xl shadow-2xl space-y-6 border border-gray-100">

    <!-- Header -->
    <div class="text-center">
      <h2 class="text-3xl font-bold text-gray-800">Create Your Library Account</h2>
      <p class="text-sm text-gray-600 mt-1">Start your reading journey with us</p>
    </div>

    <!-- Alerts -->
    @if ($errors->any())
      <div class="bg-red-100 border border-red-300 text-red-800 text-sm p-4 rounded-lg">
        <strong class="font-medium">Please fix the following:</strong>
        <ul class="list-disc pl-5 mt-1 space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if (session('success'))
      <div class="bg-green-100 border border-green-300 text-green-800 p-4 rounded-lg text-sm">
        {{ session('success') }}
      </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ url('/register') }}" id="registerForm" class="space-y-5">
      @csrf

      <!-- Name -->
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}"
          class="mt-1 w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#a47148] focus:outline-none shadow-sm bg-white text-gray-800"
          placeholder="Jane Austen" required>
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}"
          class="mt-1 w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#a47148] focus:outline-none shadow-sm bg-white text-gray-800"
          placeholder="you@example.com" required>
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" id="password"
          class="mt-1 w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#a47148] focus:outline-none shadow-sm bg-white text-gray-800"
          placeholder="••••••••" required>
      </div>

      <!-- Confirm Password -->
      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation"
          class="mt-1 w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-[#a47148] focus:outline-none shadow-sm bg-white text-gray-800"
          placeholder="••••••••" required>
      </div>

      <!-- Submit -->
      <button type="submit" id="submitBtn"
        class="w-full flex items-center justify-center bg-gradient-to-r from-[#a47148] to-[#7e5d4a] hover:from-[#96654b] hover:to-[#6f4e3d] text-white font-semibold py-3 rounded-lg transition-transform hover:scale-[1.02] focus:ring-4 focus:ring-[#c9ada7]">
        <svg id="spinner" class="hidden animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
          viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
          <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
        </svg>
        Create Account
      </button>

      <div class="text-center text-sm text-gray-600">
        Already have an account?
        <a href="{{ url('/login') }}" class="text-[#a47148] font-medium hover:underline">Login</a>
      </div>
    </form>
  </div>
</div>

<!-- Spinner Script -->
<script>
  document.getElementById('registerForm').addEventListener('submit', function () {
    const btn = document.getElementById('submitBtn');
    const spinner = document.getElementById('spinner');
    btn.disabled = true;
    spinner.classList.remove('hidden');
    btn.classList.add('opacity-60', 'cursor-not-allowed');
  });
</script>
@endsection
