@extends('layouts.app')

@section('content')
<div class="min-h-screen w-full flex items-center justify-center bg-gradient-to-tr from-[#f8f4f1] to-[#ded1c7] overflow-hidden relative">
  <!-- optional blur overlay -->
  <div class="absolute inset-0 bg-white/30 backdrop-blur-[1px] z-0"></div>

  <!-- Login Card -->
  <div class="relative z-10 w-full max-w-md bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8 sm:p-10 space-y-6">
    <div class="text-center">
      <h2 class="text-3xl font-bold text-gray-800">Welcome Back 👋</h2>
      <p class="text-sm text-gray-600 mt-1">Log in to your bookstore account</p>
    </div>

    @if(session('error'))
      <div class="bg-red-100 border border-red-300 text-red-800 text-sm p-3 rounded-lg">
        {{ session('error') }}
      </div>
    @endif

    <form method="POST" action="{{ url('/login') }}" class="space-y-5">
      @csrf

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
        <input type="email" name="email" id="email" required
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#b2927d] focus:border-[#b2927d] shadow-sm"
          placeholder="you@example.com" />
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input type="password" name="password" id="password" required
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#b2927d] focus:border-[#b2927d] shadow-sm"
          placeholder="••••••••" />
      </div>

      <!-- Remember / Forgot -->
      <div class="flex justify-between items-center text-sm">
        <label class="inline-flex items-center">
          <input type="checkbox" name="remember" class="form-checkbox text-[#a86f3f] focus:ring-[#b2927d]" />
          <span class="ml-2 text-gray-600">Remember me</span>
        </label>
        <a href="#" class="text-[#a86f3f] hover:underline">Forgot password?</a>
      </div>

      <!-- Submit -->
      <button type="submit"
        class="w-full py-3 flex items-center justify-center bg-gradient-to-r from-[#c7a07a] to-[#a86f3f] hover:from-[#ba8c61] hover:to-[#8c5b2e] text-white font-semibold rounded-lg transition-transform hover:scale-[1.02] focus:ring-4 focus:ring-[#b2927d]">
        Login
      </button>
    </form>

    <p class="text-center text-sm text-gray-600">
      Don't have an account?
      <a href="{{ url('/register') }}" class="text-[#a86f3f] font-medium hover:underline">Register</a>
    </p>
  </div>
</div>
@endsection
