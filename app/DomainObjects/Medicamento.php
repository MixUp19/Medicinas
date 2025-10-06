<?php

namespace App\DomainObjects;

class Medicamento
{
    private int $id;
    private string $nombre;
    private float $precio;
    private ?string $concentracion;
    private bool $requiereReceta;
    
    public function __construct(
        ?int $id,
        string $nombre,
        float $precio,
        ?string $concentracion,
        bool $requiereReceta,
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->concentracion = $concentracion;
        $this->requiereReceta = $requiereReceta;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
    public function getPrecio(): float
    {
        return $this->precio;
    }
    public function setPrecio(float $precio): void
    {
        $this->precio = $precio;
    }
    public function getConcentracion(): ?string
    {
        return $this->concentracion;
    }
    public function setConcentracion(?string $concentracion): void
    {
        $this->concentracion = $concentracion;
    }
    public function getRequiereReceta(): bool
    {
        return $this->requiereReceta;
    }
    public function setRequiereReceta(bool $requiereReceta): void
    {
        $this->requiereReceta = $requiereReceta;
    }
}