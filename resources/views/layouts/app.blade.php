<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Gamepedia - Ensiklopedia Game')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-900 antialiased flex flex-col min-h-screen">

    @include('components.navbar')

    <main class="grow container mx-auto ">
        @yield('content')
    </main>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

    @stack('scripts')
    @if (session('success'))
        <div id="toast-alert"
            class="fixed bottom-6 right-6 z-50 p-4 text-sm text-fg-success-strong rounded-base bg-success-soft shadow-lg"
            role="alert">
            <span class="font-medium">Success!</span> {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const el = document.getElementById('toast-alert');
                if (el) el.style.display = 'none';
            }, 3000);
        </script>
    @endif
</body>

</html>
