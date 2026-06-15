<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Gamepedia - Ensiklopedia Game')</title>

    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-900 antialiased flex flex-col min-h-screen">

    @include('components.navbar')

    <main class="grow container mx-auto px-4 py-8">
        @yield('content')
    </main>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

    @stack('scripts')
</body>

</html>
