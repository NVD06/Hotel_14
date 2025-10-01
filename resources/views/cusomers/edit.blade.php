@extends('layouts.hotel')
@section('title','Edit Customer')
@section('page_title','Edit Customer')

@section('content')
<div class="card p-5 max-w-3xl">
  <form method="POST" action="{{ route('customers.update',$customer) }}" class="grid grid-cols-2 gap-4">
    @csrf @method('PUT')
    <div class="col-span-2">
      <label class="lbl">Full name</label>
      <input name="full_name" class="field" value="{{ old('full_name',$customer->full_name) }}">
    </div>
    <div>
      <label class="lbl">Email</label>
      <input name="email" class="field" value="{{ old('email',$customer->email) }}">
    </div>
    <div>
      <label class="lbl">Phone</label>
      <input name="phone" class="field" value="{{ old('phone',$customer->phone) }}">
    </div>
    <div>
      <label class="lbl">ID number</label>
      <input name="id_number" class="field" value="{{ old('id_number',$customer->id_number) }}">
    </div>
    <div>
      <label class="lbl">Date of birth</label>
      <input type="date" name="date_of_birth" class="field" value="{{ old('date_of_birth',optional($customer->date_of_birth)->format('Y-m-d')) }}">
    </div>
    <div class="col-span-2">
      <label class="lbl">Address</label>
      <input name="address" class="field" value="{{ old('address',$customer->address) }}">
    </div>
    <div class="col-span-2">
      <label class="lbl">Nationality</label>
      <input name="nationality" class="field" value="{{ old('nationality',$customer->nationality) }}">
    </div>
    <div class="col-span-2">
      <label class="lbl">Notes</label>
      <textarea name="notes" class="field">{{ old('notes',$customer->notes) }}</textarea>
    </div>
    <div class="col-span-2">
      <button class="btn btn-primary">Update</button>
      <a href="{{ route('customers.index') }}" class="ml-2">Cancel</a>
    </div>
  </form>
</div>
@endsection
