@extends(
    'layouts.app',
    [
      'title' => 'Login - ' . config('app.name'),
      'description' => 'Login to Laravel website',
      'titleContent' => 'Login',
    ])

@section('content')
<form method="POST" action="">
  @csrf

  @error('')
    <div class="alert alert-danger">{{ $message }}</div>
  @enderror

  <div class="mb-3">
    <label for="email">Email</label>
    <input name="email" value="" type="text" id="email"
        class="@error('email') is-invalid @enderror">

    @error('email')
      <div class="alert alert-danger">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-3">
    <label for="password">Password</label>
    <input name="password" value="" type="password" id="password"
        class="@error('password') is-invalid @enderror">

    @error('password')
      <div class="alert alert-danger">{{ $message }}</div>
    @enderror
  </div>

  <button type="submit" class="btn btn-primary">Login</button>
</form>
@endsection
