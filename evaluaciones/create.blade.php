<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>EvalúaPráctica - Crear Evaluación</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md border p-6">
        <h2 class="text-2xl font-bold mb-4 text-indigo-700">Crear Nuevo Cuestionario</h2>

        @if(session('exito'))
            <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
                {{ session('exito') }}
            </div>
        @endif

        <form action="{{ route('evaluacion.guardar') }}" method="POST" class="space-y-4">
            @csrf 
            <div>
                <label class="block font-medium mb-1">ID Instructor Temporal:</label>
                <input type="number" name="user_id" value="1" required class="w-full p-2 border rounded bg-gray-50">
            </div>
            <div>
                <label class="block font-medium mb-1">Título de la Evaluación:</label>
                <input type="text" name="titulo" required class="w-full p-2 border rounded bg-gray-50">
            </div>
            <div>
                <label class="block font-medium mb-1">Tiempo Límite (minutos):</label>
                <input type="number" name="tiempo_limite" required class="w-full p-2 border rounded bg-gray-50">
            </div>
            <div>
                <label class="block font-medium mb-1">Descripción:</label>
                <textarea name="descripcion" rows="3" class="w-full p-2 border rounded bg-gray-50"></textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white p-2 rounded font-bold hover:bg-indigo-700">
                Guardar Evaluación (POST)
            </button>
        </form>
    </div>
</body>
</html>