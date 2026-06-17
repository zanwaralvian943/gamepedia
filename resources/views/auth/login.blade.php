@extends('layouts.app')
@section('title', 'Gamepedia - Login')
@section('content')
    <div class="flex justify-center items-center min-h-[70vh] px-4">
        <div class="w-full max-w-sm bg-white p-6 border border-gray-200 rounded-2xl shadow-sm">


            <form action="{{ route('login') }}" method="POST">
                @csrf

                <h5 class="text-xl font-bold text-gray-950 mb-6">Sign in to our platform</h5>

                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-semibold text-gray-800">Your email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-purple-600 focus:border-purple-600 block w-full px-3 py-2.5 placeholder:text-gray-400"
                        placeholder="example@company.com" required />
                    @error('email')
                        <span class="text-xs text-red-500 mt-1.5 block font-medium">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-2 text-sm font-semibold text-gray-800">Your password</label>
                    <input type="password" id="password" name="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-purple-600 focus:border-purple-600 block w-full px-3 py-2.5 placeholder:text-gray-400"
                        placeholder="•••••••••" required />
                </div>

                <div class="flex items-center justify-between my-6">
                    <div class="flex items-center">
                        <input id="checkbox-remember" name="remember" type="checkbox"
                            class="w-4 h-4 text-purple-600 border border-gray-300 rounded-md bg-gray-50 focus:ring-2 focus:ring-purple-500">
                        <label for="checkbox-remember"
                            class="ms-2 text-sm font-medium text-gray-700 select-none cursor-pointer">Remember me</label>
                    </div>
                    <a href="#" class="text-sm font-semibold text-purple-600 hover:underline">Lost Password?</a>
                </div>

                <button type="submit"
                    class="text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-200 font-semibold leading-5 rounded-xl text-sm px-4 py-2.5 focus:outline-none w-full mb-4 transition-all shadow-xs">
                    Login to your account
                </button>
                <div class="text-sm font-medium text-gray-500 text-center">
                    Not registered?
                    <a href="{{ route('register') }}" class="text-purple-600 font-semibold hover:underline">
                        Create account
                    </a>
                </div>
            </form>

        </div>
    </div>

@endsection
