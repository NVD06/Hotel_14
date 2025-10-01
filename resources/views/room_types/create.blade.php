<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">New Room Type</h2></x-slot>
  <div class="p-6 max-w-xl">
    <form method="POST" action="{{ route('room-types.store') }}" class="space-y-4">
      @csrf
      <div><label>Name</label>
        <input name="name" value="{{ old('name') }}" class="border w-full p-2 rounded" />
        @error('name')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      </div>
      <div><label>Capacity</label>
        <input type="number" name="capacity" value="{{ old('capacity',2) }}" class="border w-full p-2 rounded" />
      </div>
      <div><label>Base price</label>
        <input type="number" step="0.01" name="base_price" value="{{ old('base_price',0) }}" class="border w-full p-2 rounded" />
      </div>
      <div><label>Description</label>
        <textarea name="description" class="border w-full p-2 rounded">{{ old('description') }}</textarea>
      </div>
      <button class="px-3 py-2 bg-blue-600 text-white rounded">Save</button>
    </form>
  </div>
</x-app-layout>
