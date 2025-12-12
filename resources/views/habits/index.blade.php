<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light font-sans min-h-screen">

    <nav class="bg-primary border-b border-secondary px-4 py-3">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div class="text-accent font-bold text-xl">Habit<span class="text-white">Tracker</span></div>
            <div class="flex items-center gap-4">
                <span class="text-accent text-sm hidden sm:block">Hola, {{ Auth::user()->name }}</span>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-white bg-secondary hover:bg-hover px-3 py-1.5 rounded transition-colors cursor-pointer">
                        Salir
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-2xl mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold text-primary mb-2 text-center">
            Tus Hábitos Diarios
        </h1>
        <p class="text-center text-secondary mb-8 text-sm">Construye tu disciplina, un día a la vez.</p>

        <div class="mb-8 bg-white p-6 rounded-lg shadow-lg border border-accent">
            <form action="{{ route('habits.store') }}" method="POST" class="flex gap-4">
                @csrf
                <div class="relative w-full">
                    <input type="text" name="name" 
                           class="bg-light border border-accent text-primary text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5 placeholder-last" 
                           placeholder="Ej: Leer 30 minutos..." required>
                </div>
                <button type="submit" class="text-white bg-button hover:bg-hover focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center transition-colors cursor-pointer">
                    Agregar
                </button>
            </form>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg border border-accent">
            <table class="w-full text-sm text-left text-last">
                <thead class="text-xs text-white uppercase bg-secondary">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-10">Estado</th>
                        <th scope="col" class="px-6 py-3">Hábito</th>
                        <th scope="col" class="px-6 py-3 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach($habits as $habit)
                        <tr class="border-b border-accent hover:bg-light transition-colors">
                            <td class="px-6 py-4">
                                <form action="{{ route('habits.toggle', $habit) }}" method="POST" id="form-check-{{ $habit->id }}">
                                    @csrf
                                    <input type="checkbox" 
                                           onchange="document.getElementById('form-check-{{ $habit->id }}').submit()"
                                           {{ $habit->isCompletedToday() ? 'checked' : '' }}
                                           class="w-5 h-5 text-button bg-light border-secondary rounded focus:ring-button focus:ring-2 cursor-pointer">
                                </form>
                            </td>
                            <td class="px-6 py-4 font-medium text-primary">
                                <span class="{{ $habit->isCompletedToday() ? 'line-through text-last opacity-70' : '' }}">
                                    {{ $habit->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('habits.destroy', $habit) }}" method="POST" onsubmit="return confirm('¿Borrar hábito?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-500 hover:text-red-700 hover:underline cursor-pointer">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($habits->isEmpty())
                <div class="text-center p-8 bg-white text-last">
                    <p>Aún no tienes hábitos registrados.</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>