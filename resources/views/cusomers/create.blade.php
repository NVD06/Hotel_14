@extends('layouts.hotel')
@section('title','New Customer')
@section('page_title','New Customer')

@section('content')
<div class="card p-5 max-w-3xl">
  <form method="POST" action="{{ route('customers.store') }}" class="grid grid-cols-2 gap-4">
    @csrf
    <div class="col-span-2">
      <label class="lbl">Full name</label>
      <input name="full_name" class="field" value="{{ old('full_name') }}">
    </div>
    <div>
      <label class="lbl">Email</label>
      <input name="email" class="field" value="{{ old('email') }}">
    </div>
    <div>
      <label class="lbl">Phone</label>
      <input name="phone" class="field" value="{{ old('phone') }}">
    </div>
    <div>
      <label class="lbl">ID number</label>
      <input name="id_number" class="field" value="{{ old('id_number') }}">
    </div>
    <div>
      <label class="lbl">Date of birth</label>
      <input type="date" name="date_of_birth" class="field" value="{{ old('date_of_birth') }}">
    </div>
    <div class="col-span-2">
      <label class="lbl">Address</label>
      <input name="address" class="field" value="{{ old('address') }}">
    </div>
    <div class="col-span-2">
      <label class="lbl">Nationality</label>
      <input name="nationality" class="field" value="{{ old('nationality') }}">
    </div>
    <div class="col-span-2">
      <label class="lbl">Notes</label>
      <textarea name="notes" class="field">{{ old('notes') }}</textarea>
    </div>
    <div class="col-span-2">
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('customers.index') }}" class="ml-2">Cancel</a>
    </div>
  </form>
</div>
@endsection
