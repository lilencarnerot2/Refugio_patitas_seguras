<?php

require_once __DIR__ . '/../repositories/razas.php';
require_once __DIR__ . '/../repositories/especies.php';

function listarRazasPorEspecie($idEspecie)
{
    if ($idEspecie <= 0) {
        throw new Exception(
            'El ID de especie no es válido.'
        );
    }

    if (!existeEspecie($idEspecie)) {
        throw new Exception(
            'La especie indicada no existe.'
        );
    }

    return obtenerRazasPorEspecie($idEspecie);
}