<?php

require_once __DIR__ . '/../services/razas.php';
require_once __DIR__ . '/../utils/response.php';

function manejarRazas()
{
    $metodo = $_SERVER['REQUEST_METHOD'];

    switch ($metodo) {

        case 'GET':
            manejarGetRazas();
            break;

        default:
            responderJson(
                false,
                'Método no permitido.',
                null,
                405
            );
            break;
    }
}

function manejarGetRazas()
{
    $idEspecie = $_GET['id_especie'] ?? null;

    if (
        $idEspecie === null
        || !ctype_digit($idEspecie)
    ) {
        responderJson(
            false,
            'El ID de especie debe ser un número entero.',
            null,
            400
        );

        return;
    }

    try {

        $razas = listarRazasPorEspecie(
            (int)$idEspecie
        );

        responderJson(
            true,
            null,
            $razas
        );

    } catch (Exception $e) {

        responderJson(
            false,
            $e->getMessage(),
            null,
            400
        );
    }
}