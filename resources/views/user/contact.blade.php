@extends('layouts.app')

@section('title','Contact Us')

@section('content')

<div>
    <form method="POST" action="{{ route('contact') }}" class="space-y-5">
        @csrf

        <input type="text" name="name" placeholder="Name"
            class="w-full h-14 rounded-md border border-red-200 focus:border-indigo-500 focus:ring-indigo-500 px-4" required>

        <input type="email" name="email" placeholder="Email"
            class="w-full h-14 rounded-md border border-red-200 focus:border-indigo-500 focus:ring-indigo-500 px-4" required>

        <input type="tel" name="phone" placeholder="Phone Number"
            class="w-full h-14 rounded-md border border-red-200 focus:border-indigo-500 focus:ring-indigo-500 px-4">

        <textarea name="message" rows="6" placeholder="Message"
            class="w-full rounded-md border border-red-200 focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3"></textarea>


        <button type="submit"
            class="w-full md:w-44 h-12 rounded-full bg-indigo-600 text-white font-semibold tracking-wide shadow hover:bg-indigo-700">
            SEND
        </button>
    </form>
</div>

@endsection