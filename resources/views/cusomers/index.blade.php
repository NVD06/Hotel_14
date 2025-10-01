@extends('layouts.hotel')
@section('title','Customers')
@section('page_title','Customers')

@section('content')
<div class="card p-5">
  <div class="flex items-center justify-between">
    <form method="GET" class="flex gap-2">
      <input name="q" value="{{ request('q') }}" placeholder="Search name/email/phone" class="field" style="max-width:320px">
      <button class="btn btn-secondary">Search</button>
    </form>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">+ New</a>
  </div>

  <div class="mt-4 overflow-x-auto">
    <table class="w-full text-sm">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-3">Name</th><th class="p-3">Email</th><th class="p-3">Phone</th><th class="p-3">ID No</th><th class="p-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y">
        @foreach($customers as $c)
          <tr>
            <td class="p-3">{{ $c->full_name }}</td>
            <td class="p-3">{{ $c->email }}</td>
            <td class="p-3">{{ $c->phone }}</td>
            <td class="p-3">{{ $c->id_number }}</td>
            <td class="p-3 text-right">
              <a href="{{ route('customers.edit',$c) }}" class="text-indigo-600">Edit</a>
              <form action="{{ route('customers.destroy',$c) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE') <button class="ml-2 btn-danger">Delete</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">{{ $customers->links() }}</div>
</div>
@endsection
