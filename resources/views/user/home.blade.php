@extends('layouts.app')

@section('title')

@section('content')
  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <p>Chào {{ Auth::user()->name }}!</p>
        <p>Đây là trang dashboard </p>
      </div>
    </div>
  </div>
@endsection
