<?php

require_once __DIR__ . '/../repositories/estados_solicitudes.php';

function listar_estados_solicitudes(): array
{
    return obtener_estados_solicitudes();
}