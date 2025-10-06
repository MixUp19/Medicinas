<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Crear Receta</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-8">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">

        <h1 class="text-2xl font-bold mb-6 text-gray-800">Registrar Venta de Receta</h1>
            <div class="flex-grow mb-2">
                <label for="sucursal-select" class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
                <select id="sucursal-select" class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->getId() }}" data-nombre="{{ $sucursal->getNombre() }}">{{ $sucursal->getNombre() }}</option>
                    @endforeach
                </select>
            </div>
        <div class="flex items-end gap-4 mb-6">
          
            <div class="flex-grow">
                <label for="medicamento-select" class="block text-sm font-medium text-gray-700 mb-1">Medicamento</label>
                <select id="medicamento-select" class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach($medicamentos as $medicamento)
                        <option value="{{ $medicamento->getId() }}" data-nombre="{{ $medicamento->getNombre() . " " .$medicamento->getConcentracion() }}" data-precio="{{ $medicamento->getPrecio() }}">{{ $medicamento->getNombre() }}  {{ $medicamento->getConcentracion() }}</option>
                    @endforeach 
                </select>
            </div>
            <div class="w-24">
                <label for="unidades-input" class="block text-sm font-medium text-gray-700 mb-1">Unidades</label>
                <input type="number" id="unidades-input" value="1" min="1" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <button id="agregar-btn" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Agregar
            </button>
        </div>

        <hr class="my-6">

        <div>
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Detalle de la Venta</h2>
            <div id="lista-agregados-container" class="space-y-3">
                <p id="mensaje-vacio" class="text-gray-500">Aún no se han agregado medicamentos.</p>
            </div>
        </div>

        <div class="mt-8 text-right">
            <button id="enviar-btn" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                Enviar
            </button>
        </div>
    </div>
</div>

@vite('resources/js/recetas.js')

</body>
</html>