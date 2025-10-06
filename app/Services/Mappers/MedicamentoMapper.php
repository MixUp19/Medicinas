<?php

namespace App\Services\Mappers;

use App\DomainObjects\Medicamento as MedicamentoDomain;
use App\Models\Medicamento as MedicamentoEloquent;

class MedicamentoMapper
{
    public static function toDomain(MedicamentoEloquent $medicamentoEloquent): MedicamentoDomain
    {
        return new MedicamentoDomain(
            id: $medicamentoEloquent->id_medicamento,
            nombre: $medicamentoEloquent->nombre,
            precio: $medicamentoEloquent->precio,
            concentracion: $medicamentoEloquent->concentracion,
            requiereReceta: $medicamentoEloquent->requiere_receta,
        );
    }

    public static function toEloquent(MedicamentoDomain $medicamentoDomain): array
    {
        return [
            'nombre' => $medicamentoDomain->nombre,
            'precio' => $medicamentoDomain->precio,
            'concentracion' => $medicamentoDomain->concentracion,
            'requiere_receta' => $medicamentoDomain->requiereReceta,
        ];
    }
}