<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Edit Room #{{ $room->room_number }}</h2></x-slot>

  <div class="p-6 max-w-xl">
    <form method="POST" action="{{ route('rooms.update',$room) }}" class="space-y-4">
      @csrf @method('PUT')

      <div>
        <label class="block mb-1">Room number</label>
        <input name="room_number" value="{{ old('room_number',$room->room_number) }}" class="border rounded p-2 w-full">
        @error('room_number')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="block mb-1">Room type</label>
        <select name="room_type_id" class="border rounded p-2 w-full">
          @foreach($types as $t)
            <option value="{{ $t->id }}" @selected(old('room_type_id',$room->room_type_id)==$t->id)>{{ $t->name }}</option>
          @endforeach
        </select>
        @error('room_type_id')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block mb-1">Floor</label>
          <input type="number" name="floor" value="{{ old('floor',$room->floor) }}" class="border rounded p-2 w-full">
          @error('floor')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="block mb-1">Status</label>
          <select name="status" class="border rounded p-2 w-full">
            @foreach(['available','occupied','cleaning','maintenance'] as $s)
              <option value="{{ $s }}" @selected(old('status',$room->status)==$s)>{{ $s }}</option>
            @endforeach
          </select>
          @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
        </div>
      </div>

      <div>
        <label class="block mb-1">Notes</label>
        <textarea name="notes" class="border rounded p-2 w-full">{{ old('notes',$room->notes) }}</textarea>
      </div>

      <button class="px-3 py-2 bg-blue-600 text-white rounded">Update</button>
      <a href="{{ route('rooms.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
  </div>
</x-app-layout>
