@extends('layouts.app')
@section('title', 'Gamepedia - Register')
@section('content')

    <div class="flex justify-center items-center min-h-[70vh] px-4 my-8">
        <div class="w-full max-w-sm bg-white p-6 border border-gray-200 rounded-2xl shadow-sm">

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <h5 class="text-xl font-bold text-gray-950 mb-6">Create your account</h5>

                <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-semibold text-gray-800">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-purple-600 focus:border-purple-600 block w-full px-3 py-2.5 placeholder:text-gray-400"
                        placeholder="John Doe" required />
                    @error('name')
                        <span class="text-xs text-red-500 mt-1.5 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

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
                    @error('password')
                        <span class="text-xs text-red-500 mt-1.5 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block mb-2 text-sm font-semibold text-gray-800">Confirm
                        password</label>

                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-purple-600 focus:border-purple-600 block w-full px-3 py-2.5 placeholder:text-gray-400"
                        placeholder="•••••••••" required />
                </div>
                <button type="submit"
                    class="text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-200 font-semibold leading-5 rounded-xl text-sm px-4 py-2.5 focus:outline-none w-full mb-4 transition-all shadow-xs">
                    Register account
                </button>

                <div class="text-sm font-medium text-gray-500 text-center">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-purple-600 font-semibold hover:underline">
                        Login here
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
