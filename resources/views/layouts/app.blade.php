<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? config('app.name') }}</title>
  <meta name="description" content="{{ $description ?? config('app.description') }}">
  @vite(['resources/css/app.scss', 'resources/js/app.js'])
  @stack('head-css')
  @stack('head-js')
</head>
<body>
  <div class="container-fluid">
    <x-nav-bar title="{{ $titleContent ?? $title }}">
    </x-nav-bar>
    @yield('content')
  </div>
</body>
