<?php
  namespace App\Services\Repositories;
  
  use App\DomainObjects\Medicamento;
  use App\Models\Medicamento as MedicamentoModel;
  use App\Services\Mappers\MedicamentoMapper;
  use Illuminate\Database\Eloquent\Collection;
  
  class MedicamentoRepository
  {
      public static function obtenerTodos(): array
      {
          $medicamentosEloquent = MedicamentoModel::all();
          
          return $medicamentosEloquent->map(function ($medicamentoEloquent) {
              return MedicamentoMapper::toDomain($medicamentoEloquent);
          })->toArray();
      }

      public static function obtenerPorId(int $id): ?Medicamento
      {
          $medicamentoEloquent = MedicamentoModel::find($id);
          return $medicamentoEloquent ? MedicamentoMapper::toDomain($medicamentoEloquent) : null;
      }
  }