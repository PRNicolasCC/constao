<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg border border-accent p-8">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-primary">Habit Tracker</h1>
            <p class="text-secondary text-sm mt-2">Mejora tu vida, día a día.</p>
        </div>
        
        @yield('content')
        
    </div>
</body>
</html>