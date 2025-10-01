<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Rooms</h2></x-slot>

  <div class="p-6">
    <div class="flex items-center justify-between">
      <form method="GET" class="flex gap-2">
        <input name="q" value="{{ request('q') }}" placeholder="Search room no."
               class="border rounded p-2"/>
        <button class="px-3 py-2 bg-gray-800 text-white rounded">Search</button>
      </form>

      <a href="{{ route('rooms.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">+ New</a>
    </div>

    @if(session('ok'))<div class="mt-3 text-green-700">{{ session('ok') }}</div>@endif

    <table class="mt-4 w-full border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">#</th>
          <th class="p-2 border">Room No</th>
          <th class="p-2 border">Type</th>
          <th class="p-2 border">Floor</th>
          <th class="p-2 border">Status</th>
          <th class="p-2 border"></th>
        </tr>
      </thead>
      <tbody>
      @foreach($rooms as $r)
        <tr>
          <td class="p-2 border">{{ $r->id }}</td>
          <td class="p-2 border">{{ $r->room_number }}</td>
          <td class="p-2 border">{{ $r->type?->name }}</td>
          <td class="p-2 border">{{ $r->floor }}</td>
          <td class="p-2 border">
            <span class="px-2 py-1 rounded bg-gray-100">{{ $r->status }}</span>
          </td>
          <td class="p-2 border text-right">
            <a href="{{ route('rooms.edit',$r) }}" class="text-blue-600">Edit</a>
            <form action="{{ route('rooms.destroy',$r) }}" method="POST" class="inline" onsubmit="return confirm('Delete room?')">
              @csrf @method('DELETE')
              <button class="text-red-600 ml-2">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>

    <div class="mt-3">{{ $rooms->links() }}</div>
  </div>
</x-app-layout>
