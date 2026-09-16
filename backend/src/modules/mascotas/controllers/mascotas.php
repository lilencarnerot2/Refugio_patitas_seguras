<?php

require_once __DIR__ . '/../services/mascotas.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejarMascotas() {
    $metodo = $_SERVER['REQUEST_METHOD'] ?? '';

    switch ($metodo) {

        case 'GET':
            manejarGetMascotas();
            break;

        case 'POST':
            manejarPostMascotas();
            break;

        case 'PUT':
            manejarPutMascotas();
            break;
        
        case 'DELETE':
            manejarDeleteMascotas();
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

function manejarGetMascotas(){
    $id = $_GET['id'] ?? null;

    try {

        if ($id !== null) {

            if (!ctype_digit($id)) {
                responderJson(
                    false,
                    'El ID debe ser un número entero.',
                    null,
                    400
                );

                return;
            }

            $mascota = buscarMascotaPorId((int)$id);

            if (!$mascota) {
                responderJson(
                    false,
                    'Mascota no encontrada.',
                    null,
                    404
                );

                return;
            }

            responderJson(
                true,
                null,
                $mascota
            );

            return;
        }

        $mascotas = listarMascotas();

        responderJson(
            true,
            null,
            $mascotas
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

function manejarPostMascotas(){
    try {

        $contenido = file_get_contents('php://input');

        $datos = json_decode($contenido, true);

        if (!is_array($datos)) {
            responderJson(
                false,
                'Los datos enviados no son válidos.',
                null,
                400
            );

            return;
        }

        $mascota = registrarMascota($datos);

        responderJson(
            true,
            'Mascota creada correctamente.',
            $mascota,
            201
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

function manejarPutMascotas()
{
    $id = $_GET['id'] ?? null;

    if ($id === null || !ctype_digit($id)) {
        responderJson(
            false,
            'El ID de la mascota debe ser un número entero.',
            null,
            400
        );

        return;
    }

    try {

        $contenido = file_get_contents('php://input');
        $datos = json_decode($contenido, true);

        if (!is_array($datos)) {
            responderJson(
                false,
                'Los datos enviados no son válidos.',
                null,
                400
            );

            return;
        }

        $mascota = modificarMascota(
            (int)$id,
            $datos
        );

        responderJson(
            true,
            'Mascota actualizada correctamente.',
            $mascota,
            200
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

function manejarDeleteMascotas()
{
    $id = $_GET['id'] ?? null;

    if ($id === null || !ctype_digit($id)) {
        responderJson(
            false,
            'El ID de la mascota debe ser un número entero.',
            null,
            400
        );

        return;
    }

    try {

        $mascota = eliminarMascota((int)$id);

        responderJson(
            true,
            'Mascota desactivada correctamente.',
            $mascota,
            200
        );

    } catch (Exception $e) {

        responderJson(
            false,
            $e->getMessage(),
            null,
            404
        );
    }
}