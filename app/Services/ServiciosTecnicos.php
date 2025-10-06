<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Inventario;
use App\DomainObjects\Receta;
use App\Services\Repositories\SucursalRepository;
use App\Services\Repositories\MedicamentoRepository;

class ServiciosTecnicos
{
    private static ?self $serviciosTecnicos= null;

    private function __construct()
    {}

    public static function getInstance()
    {
        if(self::$serviciosTecnicos== null){
            self::$serviciosTecnicos= new self();
        }
        return self::$serviciosTecnicos;
    }

    public function procesarReceta(int $sucursal_id, array $medicamentosSeleccionados): array
    {
        try {
            DB::beginTransaction();

            $recetaId = DB::table('recetas')->insertGetId([
                'fecha' => now()->toDateString(),
                'id_sucursal' => $sucursal_id
            ]);
            
            $sucursal = SucursalRepository::obtenerPorId($sucursal_id);
            $receta = new Receta([], now()->toDateString(), $sucursal);
          
            foreach ($medicamentosSeleccionados as $medicamento) {
                $inventario = Inventario::where('id_sucursal', $sucursal_id)
                    ->where('id_medicamento', $medicamento['medicamento_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$inventario) {
                    throw new Exception("El medicamento con ID {$medicamento['medicamento_id']} no está disponible en la sucursal {$sucursal_id}.");
                }

                if ($inventario->existencias < $medicamento['unidades']) {
                    throw new Exception("No hay suficientes existencias del medicamento con ID {$medicamento['medicamento_id']} en la sucursal {$sucursal_id}.");
                }

                $inventario->existencias -= $medicamento['unidades'];
                $inventario->save();

                DB::table('detallereceta')->insert([
                    'id_receta' => $recetaId,
                    'id_medicamento' => $medicamento['medicamento_id'],
                    'unidades' => $medicamento['unidades']
                ]);  
                

                $receta->agregarMedicamento(MedicamentoRepository::obtenerPorId($medicamento['medicamento_id']));
            }
            DB::commit();
            return $receta;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Error al procesar la receta: " . $e->getMessage());
        }
    }

    private function __clone(): void
    {
        throw new \Exception('No se puede clonar una instancia singleton.');
    }

    public function __wakeup(): void
    {
        throw new \Exception('No se puede deserializar una instancia singleton.');
    }

}
