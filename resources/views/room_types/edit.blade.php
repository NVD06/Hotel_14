<form method="POST" action="{{ route('room-types.update',$roomType) }}" class="space-y-4">
  @csrf @method('PUT')
  <!-- input + value="{{ old('name',$roomType->name) }}" ... -->
</form>
