<?php

namespace App\Services\Mappers;

use App\DomainObjects\Sucursal as SucursalDomain;
use App\Models\Sucursal as SucursalEloquent;

class SucursalesMapper
{
    public static function toDomain(SucursalEloquent $sucursalEloquent): SucursalDomain
    {
        return new SucursalDomain(
            id: $sucursalEloquent->id_sucursal,
            nombre: $sucursalEloquent->nombre,
        );
    }

    public static function toEloquent(SucursalDomain $sucursalDomain): array
    {
        return [
            'nombre' => $sucursalDomain->nombre,
            'direccion' => $sucursalDomain->direccion,
            'telefono' => $sucursalDomain->telefono,
        ];
    }
}