<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit Tracker</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans">

    <div class="max-w-2xl mx-auto py-10 px-4">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">
            Mis Hábitos Diarios
        </h1>

        <div class="mb-8 bg-white p-6 rounded-lg shadow dark:bg-gray-800">
            <form action="{{ route('habits.store') }}" method="POST" class="flex gap-4">
                @csrf
                <div class="relative w-full">
                    <input type="text" name="name" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" 
                           placeholder="Ej: Leer 30 minutos..." required>
                </div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Agregar
                </button>
            </form>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        <th scope="col" class="px-6 py-3">Hábito</th>
                        <th scope="col" class="px-6 py-3 text-right">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($habits as $habit)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 w-4">
                                <form action="{{ route('habits.toggle', $habit) }}" method="POST" id="form-check-{{ $habit->id }}">
                                    @csrf
                                    <input type="checkbox" 
                                           onchange="document.getElementById('form-check-{{ $habit->id }}').submit()"
                                           {{ $habit->isCompletedToday() ? 'checked' : '' }}
                                           class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer">
                                </form>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <span class="{{ $habit->isCompletedToday() ? 'line-through text-gray-400' : '' }}">
                                    {{ $habit->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('habits.destroy', $habit) }}" method="POST" onsubmit="return confirm('¿Borrar hábito?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($habits->isEmpty())
                <p class="text-center p-4 text-gray-500">No tienes hábitos aún. ¡Agrega uno arriba!</p>
            @endif
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</body>
</html>