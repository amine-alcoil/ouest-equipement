<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/svg+xml" href="/images/Favicon_alcoil.svg">
    <title>ALCOIL</title>
    
    <!-- DNS Prefetch and Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-clean">

@include('components.alert-notifications')

@include('layouts.navbar')

<main>
    @yield('content')
</main>

@include('layouts.footer')

</body>
</html>
