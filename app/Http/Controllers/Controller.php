<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DomainModel\Modelo;

class Controller 
{
    private Modelo $modelo;

    public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
    }

    public function formularioReceta() {
      $medicamentos = $this->modelo->obtenerMedicamentos();
      $sucursales = $this->modelo->obtenerSucursales();
      return view('formulario_receta', ['medicamentos' => $medicamentos, 'sucursales' => $sucursales]);
    }

    public function procesarReceta(Request $request) {
        try {
            $sucursal_id = $request->input('sucursal_id');
            $medicamentos = $request->input('medicamentos');
            
            $receta = $this->modelo->procesarReceta($sucursal_id, $medicamentos);
            
            // Verificar si es una petición AJAX
            if ($request->ajax() || $request->wantsJson()) {
                // Renderizar la vista como HTML string para AJAX
                $html = view('mostrar_receta', ['receta' => $receta])->render();
                
                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'message' => 'Receta procesada correctamente!'
                ]);
            }
            
            // Si no es AJAX, guardar en sesión y redirigir
            session(['receta_procesada' => $receta]);
            return redirect()->route('mostrar.receta');

        } catch (\Exception $e) {
            \Log::error('Error procesando receta: ' . $e->getMessage(), [
                'sucursal_id' => $sucursal_id ?? null,
                'medicamentos' => $medicamentos ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Error al procesar la receta: ' . $e->getMessage()
                ], 500);
            }
            
            // Si no es AJAX, redirigir con error
            return redirect()->back()->with('error', 'Error al procesar la receta: ' . $e->getMessage());
        }
    }

    public function mostrarReceta() {
        $receta = session('receta_procesada');
        if (!$receta) {
            return redirect()->route('formulario.receta');
        }
        return view('mostrar_receta', ['receta' => $receta]);
    }
}
