<?php

namespace App\DomainObjects;
use App\DomainObjects\Medicamento;
use App\DomainObjects\Sucursal;

class Receta
{
    private int $id;
    private array $medicamentos;
    private \DateTime $fecha;
    private Sucursal $sucursal;

    public function __construct(int $id, array $medicamentos = [], \DateTime $fecha, Sucursal $sucursal = null)
    {
        $this->id = $id;
        $this->medicamentos = $medicamentos;
        $this->fecha = $fecha;
        $this->sucursal = $sucursal;
    }

    public function agregarMedicamento(Medicamento $medicamento): void
    {
        $this->medicamentos[] = $medicamento;
    }

    public function obtenerMedicamentos(): array
    {
        return $this->medicamentos;
    }

    public function getFecha(): \DateTime
    {
        return $this->fecha;
    }

    public function getSucursal(): ?Sucursal
    {
        return $this->sucursal;
    }

    public function setFecha(\DateTime $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function setSucursal(Sucursal $sucursal): void
    {
        $this->sucursal = $sucursal;
    }
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
