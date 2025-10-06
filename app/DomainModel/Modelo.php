<?php

namespace App\DomainModel;
use App\DomainObjects\Medicamento;
use App\DomainObjects\Sucursal;
use App\DomainObjects\Receta;
use App\Services\Repositories\MedicamentoRepository;
use App\Services\Repositories\SucursalRepository;
use App\Services\ServiciosTecnicos;
class Modelo
{
    private ServiciosTecnicos $serviciosTecnicos; 
    public function __construct()
    {
        $this->serviciosTecnicos = ServiciosTecnicos::getInstance();
    }

    public function procesarReceta(int $sucursal_id, array $medicamentos): ?Receta 
    {
      try{
        $receta = $this->serviciosTecnicos->procesarReceta($sucursal_id, $medicamentos);
        return $receta;
      }
      catch(\Exception $e){
        throw new \Exception("Error en Modelo al procesar la receta: " . $e->getMessage());
      }
    }
    public function obtenerMedicamentos(): array
    {
        return MedicamentoRepository::obtenerTodos();
    }

    public function obtenerSucursales(): array
    {
        return SucursalRepository::obtenerTodas();
    }
}