@extends('layouts.app')

@section('title','Contact Us')

@section('content')
<div style="display:flex; gap:20px; align-items:flex-start;">
    <div style="flex:1;">
        <h1>About Us</h1>
        <p>Welcome to our hotel! We are committed to providing you with the best service and comfort during your stay.</p>
        <p>Our hotel offers a variety of amenities including a swimming pool, fitness center, and complimentary breakfast.</p>
        <p>We look forward to hosting you and making your stay memorable!</p>
        <p>Contact us for more information or to make a reservation.</p>
        <p>Email:hotte@gmaikgfjhajhg
        </p>Phone: 0123456789</p>
    </div>
    <div>
        <img src="{{ asset('Image/a3.jpg') }}" alt="Hotel Image" style=" width:600px; border-radius:8px;">
    </div>
</div>
@endsection