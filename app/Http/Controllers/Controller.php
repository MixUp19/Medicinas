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
            return view ('mostrar_receta', ['receta' => $receta]);  
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error al procesar la receta: ' . $e->getMessage()
            ], 500);
        }
    }
}
