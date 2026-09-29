<?php

require_once __DIR__ . '/../repositories/tipos_vivienda.php';

function listar_tipos_vivienda(): array
{
    return obtener_tipos_vivienda();
}