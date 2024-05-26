@extends(
    'layouts.app',
    [
      'title' => 'Edit User ' . $object->name . ' - ' . config('app.name'),
      'description' => 'Editing user',
      'titleContent' => 'Edit User ' . $object->name,
    ]);

@section('content')
<form method="POST" action="" class="edit-user">
  @csrf

  @error('')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror

  <div class="mb-3">
    <label for="name">Name</label>
    <input name="name" value="{{ old('name', $object->name) }}" type="text"
        id="name" class="@error('name') is-invalid @enderror">

    @error('name')
      <div class="alert alert-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="email">Email</label>
    <input name="email" value="{{ old('email', $object->email) }}" type="email"
        id="email" class="@error('email') is-invalid @enderror">

    @error('email')
      <div class="alert alert-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="role">Role</label>
    <select name="role" id="role" class="@error('role') is-invalid @enderror">
      <option value="">--Select Role--</option>
      @foreach ($roles as $role)
        <option value="{{ $role->name }}"
            @if ((old('role') && old('role') == $role->name) ||
                (!old('role') && $object->hasRole($role))) selected @endif>
          {{ ucfirst($role->name) }}
        </option>
      @endforeach
    </select>

    @error('role')
      <div class="alert alert-danger">{{ $message }}</div>
    @enderror
  </div>

  <button type="submit" class="btn btn-primary">Update</button>
  <button type="reset" class="btn btn-secondary">Reset</button>

  @if (Request::query('next'))
    <a href="{{ Request::query('next') }}" class="btn btn-link go-back">Back</a>
  @endif
</form>
@endsection
