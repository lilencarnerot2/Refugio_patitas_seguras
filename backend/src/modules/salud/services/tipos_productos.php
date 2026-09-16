<?php

require_once __DIR__ . '/../repositories/tipos_productos.php';

function listar_tipos_productos(): array
{
    return obtener_tipos_productos();
}