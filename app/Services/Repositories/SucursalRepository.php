<?php

namespace App\Services\Repositories;

use App\DomainObjects\Sucursal;
use App\Models\Sucursal as SucursalModel;
use App\Services\Mappers\SucursalesMapper;
use Illuminate\Database\Eloquent\Collection;

class SucursalRepository
{
    public static function obtenerTodas(): array
    {
        $sucursalesEloquent = SucursalModel::all();

        return $sucursalesEloquent->map(function ($sucursalEloquent) {
            return SucursalesMapper::toDomain($sucursalEloquent);
        })->toArray();
    }

    public static function obtenerPorId(int $id): ?Sucursal
    {
        $sucursalEloquent = SucursalModel::find($id);
        return $sucursalEloquent ? SucursalesMapper::toDomain($sucursalEloquent) : null;
    }
}