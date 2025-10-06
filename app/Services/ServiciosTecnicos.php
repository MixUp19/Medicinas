<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function procesarReceta(int $sucursal_id, array $medicamentosSeleccionados): Receta
    {
        try {
            DB::beginTransaction();

            $recetaId = DB::table('recetas')->insertGetId([
                'fecha' => now()->toDateString(),
                'id_sucursal' => $sucursal_id
            ],'id_receta');
            
            $sucursal = SucursalRepository::obtenerPorId($sucursal_id);
            $receta = new Receta($recetaId, [], new \DateTime(), $sucursal);
            foreach ($medicamentosSeleccionados as $index => $medicamento) {
                
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

                $existenciasAntes = $inventario->existencias;
                $inventario->existencias -= $medicamento['unidades'];
                $saveResult = $inventario->save();

                DB::table('detallereceta')->insert([
                    'id_receta' => $recetaId,
                    'id_medicamento' => $medicamento['medicamento_id'],
                    'unidades' => $medicamento['unidades']
                ]);
                
                $medicamentoDominio = MedicamentoRepository::obtenerPorId($medicamento['medicamento_id']);
                $medicamentoDominio->setUnidades($medicamento['unidades']);
                $receta->agregarMedicamento($medicamentoDominio);
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
