<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalles de la Receta</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Receta Médica</h1>
                <p class="text-sm text-gray-500">ID: #{{ $receta_id ?? 'N/A' }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Fecha</p>
                <p class="text-lg font-semibold">{{ $receta->getFecha()->format('Y-m-d') ?? date('Y-m-d') }}</p>
            </div>
        </div>

        <!-- Sucursal Info -->
        <div class="mb-6 bg-blue-50 p-4 rounded-lg">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">Información de la Sucursal</h2>
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-blue-700 font-medium">
                    {{ $receta->getSucursal() ? $receta->getSucursal()->getNombre() : 'Sucursal no especificada' }}
                </span>
            </div>
        </div>

        <!-- Medicamentos -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-4 flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.78 0-2.678-2.153-1.415-3.414l5-5A2 2 0 009 9.172V5L8 4z"></path>
                </svg>
                Medicamentos Recetados
            </h2>
            
            <div class="space-y-4">
                @if($receta->obtenerMedicamentos() && count($receta->obtenerMedicamentos()) > 0)
                    @foreach($receta->obtenerMedicamentos() as $index => $medicamento)
                        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border-l-4 border-green-400">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                        #{{ $index + 1 }}
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $medicamento->getNombre() }}
                                    </h3>
                                    @if($medicamento->getConcentracion())
                                        <span class="text-sm text-gray-500">
                                            {{ $medicamento->getConcentracion() }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        ${{ number_format($medicamento->getPrecio(), 2) }}
                                    </span>
                                    @if($medicamento->getRequiereReceta())
                                        <span class="flex items-center text-red-600">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5C3.962 16.333 4.924 18 6.464 18z"></path>
                                            </svg>
                                            Requiere Receta
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <div class="flex items-center justify-end gap-2 mb-1">
                                    <span class="text-sm text-gray-500">Medicamento agregado</span>
                                </div>
                                <div class="text-lg font-semibold text-green-600">
                                    ${{ number_format($medicamento->getPrecio(), 2) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>No se encontraron medicamentos en esta receta.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Total -->
        @if($receta->obtenerMedicamentos() && count($receta->obtenerMedicamentos()) > 0)
            <div class="border-t pt-4 mb-6">
                <div class="flex justify-between items-center bg-green-50 p-4 rounded-lg">
                    <span class="text-xl font-semibold text-green-800">Total de la Receta:</span>
                    <span class="text-2xl font-bold text-green-600">
                        @php
                            $total = 0;
                            foreach($receta->obtenerMedicamentos() as $medicamento) {
                                $total += $medicamento->getPrecio();
                            }
                        @endphp
                        ${{ number_format($total, 2) }}
                    </span>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-6 border-t">
            <a href="{{ route('formulario.receta') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nueva Receta
            </a>
            
            <button onclick="window.print()" 
                    class="inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Imprimir
            </button>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        body { 
            background: white !important; 
        }
        .no-print { 
            display: none !important; 
        }
        .container { 
            max-width: none !important; 
            padding: 0 !important; 
        }
    }
</style>

</body>
</html>