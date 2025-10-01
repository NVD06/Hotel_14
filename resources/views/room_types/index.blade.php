<x-app-layout>
  <x-slot name="header"><h2 class="font-semibold text-xl">Room Types</h2></x-slot>
  <div class="p-6">
    <a href="{{ route('room-types.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">+ New</a>
    @if(session('ok'))<div class="mt-3 text-green-700">{{ session('ok') }}</div>@endif
    <table class="mt-4 w-full border">
      <thead><tr class="bg-gray-100">
        <th class="p-2 border">Name</th><th class="p-2 border">Capacity</th><th class="p-2 border">Base price</th><th class="p-2 border"></th>
      </tr></thead>
      <tbody>
      @foreach($types as $t)
        <tr>
          <td class="p-2 border">{{ $t->name }}</td>
          <td class="p-2 border">{{ $t->capacity }}</td>
          <td class="p-2 border">{{ number_format($t->base_price,0) }}</td>
          <td class="p-2 border text-right">
            <a href="{{ route('room-types.edit',$t) }}" class="text-blue-600">Edit</a>
            <form action="{{ route('room-types.destroy',$t) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
              @csrf @method('DELETE')
              <button class="text-red-600 ml-2">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
    <div class="mt-3">{{ $types->links() }}</div>
  </div>
</x-app-layout>
