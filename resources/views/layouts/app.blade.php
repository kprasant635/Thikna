<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'SKOP-X — Skill Today, Better Tomorrow')</title>

<!-- Social & Open Graph Metadata for Link Sharing -->
<meta property="og:site_name" content="SKOP-X">
<meta property="og:title" content="@yield('og_title', View::hasSection('title') ? View::getSection('title') : 'SKOP-X — Skill Today, Better Tomorrow')">
<meta property="og:description" content="@yield('og_description', 'Join SKOP-X: People, Skills, Opportunities, Progress. Skill Today, Better Tomorrow.')">
<meta property="og:image" content="@yield('og_image', asset('images/skopx-logo.png'))">
<meta property="og:image:alt" content="SKOP-X Logo">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">

<!-- Twitter Card Metadata -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('og_title', View::hasSection('title') ? View::getSection('title') : 'SKOP-X — Skill Today, Better Tomorrow')">
<meta name="twitter:description" content="@yield('og_description', 'Join SKOP-X: People, Skills, Opportunities, Progress. Skill Today, Better Tomorrow.')">
<meta name="twitter:image" content="@yield('og_image', asset('images/skopx-logo.png'))">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="icon" type="image/png" href="{{ asset('images/skopx-logo.png') }}">
<link rel="shortcut icon" href="{{ asset('images/skopx-logo.png') }}">
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
