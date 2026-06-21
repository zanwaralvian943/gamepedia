<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gamepedia AI')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50 antialiased h-screen overflow-hidden">

    @yield('content')

    @stack('scripts')
</body>

</html>
