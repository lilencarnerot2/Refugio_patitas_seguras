<?php

require_once __DIR__ . '/../services/especies.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejarEspecies()
{
    $metodo = $_SERVER['REQUEST_METHOD'];

    switch ($metodo) {

        case 'GET':
            manejarGetEspecies();
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

function manejarGetEspecies()
{
    try {

        $especies = listarEspecies();

        responderJson(
            true,
            null,
            $especies
        );

    } catch (Exception $e) {

        responderJson(
            false,
            $e->getMessage(),
            null,
            500
        );
    }
}