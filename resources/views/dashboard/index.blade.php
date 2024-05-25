@extends(
    'layouts.app',
    [
      'title' => 'Dashboard - ' . config('app.name'),
      'description' => 'Dashboard to manage users',
      'titleContent' => 'Dashboard',
    ])

@section('content')
<table class="table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Company</th>
      <th>Role</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($users as $user)
    <tr>
      <td>{{ $user->name }}</td>
      <td>{{ $user->company->name }}</td>
      <td>{{ $user->getRolenames()->implode(', ') }}</td>
      <td>
        @can('update', $user)
        <a href="{{ route('user.update', ['object' => $user, 'next' => url()->full()]) }}"
            class="btn btn-link btn-sm">
          Edit
        </a>
        @endcan
        @can('delete', $user)
        <a href="{{ route('user.delete', ['object' => $user, 'next' => url()->full()]) }}"
            class="btn btn-link btn-sm">
          Delete
        </a>
        @endcan
      </td>
    </tr>
  @endforeach
  </tbody>
@endsection
