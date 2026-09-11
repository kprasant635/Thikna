<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Thikana — Find shops, rentals & products near you')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script src="{{ asset('js/theme.js') }}"></script>
@stack('styles')
</head>
<body>

@include('partials.header')

<main>
    @yield('content')
</main>

@include('partials.footer')
@include('partials.theme-customizer')

@stack('scripts')
</body>
</html>
