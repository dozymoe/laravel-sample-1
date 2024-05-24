@extends(
    'layouts.app',
    [
      'title' => 'Delete User ' . $object->name . ' - ' . config('app.name'),
      'description' => 'Deleting user',
      'titleContent' => 'Delete User ' . $object->name,
    ]);

@section('content')
<form method="POST" action="">
  @csrf

  @error('')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror

  <p>Do you really want to delete this user: {{ $object->email }}?</p>

  <button type="submit" class="btn btn-primary">Delete</button>

  @if (Request::query('next'))
    <a href="{{ Request::query('next') }}" class="btn btn-link">Back</a>
  @endif
</form>
@endsection
