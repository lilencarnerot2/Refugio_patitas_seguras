<?php

require_once __DIR__ . '/../repositories/tipos_vacunas.php';

function listar_tipos_vacunas(): array
{
    return obtener_tipos_vacunas();
}