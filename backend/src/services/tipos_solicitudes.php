<?php

require_once __DIR__ . '/../repositories/tipos_solicitudes.php';

function listarTiposSolicitudes(): array
{
    return obtenerTiposSolicitudes();
}