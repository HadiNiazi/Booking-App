{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}


@extends('layouts.auth')

@section('title', 'Edit Profile')

@section('css')
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
    /* General rule for all input fields */
input[type="text"],
input[type="email"],
input[type="password"] {
    color: #333; /* Set the desired text color */
    font-size: 16px; /* Optional: Adjust font size */
}

/* Optional: Add styling for focus state */
input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
    color: #000; /* Change the color when the field is focused */
    border-color: #007bff; /* Optional: Highlight border on focus */
    outline: none; /* Optional: Remove default outline */
}
</style>
@endsection

@section('content')

<div class="py-12">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        @if (auth()->user()->role == 'admin')
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
