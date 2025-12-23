@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="inline-block w-16 h-16 bg-blue-600 rounded-xl mb-4 flex items-center justify-center text-white font-bold text-2xl">
                T
            </div>
            <h2 class="text-3xl font-bold text-gray-900">TMS Login</h2>
            <p class="text-gray-600 mt-2">Sign in to your account</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full btn-primary">
                    Sign In
                </button>
            </form>

            <div class="mt-6 p-4 bg-gray-50 rounded-lg text-sm text-gray-600">
                <p class="font-medium mb-2">Test Accounts:</p>
                <p>Admin: admin@example.com / password</p>
                <p>Dispatcher: dispatcher@example.com / password</p>
                <p>Driver: driver@example.com / password</p>
            </div>
        </div>
    </div>
</div>
@endsection
