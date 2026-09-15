<?php

require_once __DIR__ . '/../repositories/tipos_movimientos_mascotas.php';

function listar_tipos_movimientos_mascotas(): array
{
    return obtener_tipos_movimientos_mascotas();
}