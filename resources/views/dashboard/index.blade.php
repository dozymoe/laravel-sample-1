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
    </tr>
  </thead>
  @foreach ($users as $user)
    <tr>
      <td>{{ $user->name }}</td>
      <td>{{ $user->company->name }}</td>
    </tr>
  @endforeach
@endsection
