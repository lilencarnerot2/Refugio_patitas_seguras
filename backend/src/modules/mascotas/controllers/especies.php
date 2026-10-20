<?php

require_once __DIR__ . '/../services/especies.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../../middleware/autorizacion.php';

function manejarEspecies() {
    $metodo = $_SERVER['REQUEST_METHOD'];

    try {
        switch ($metodo) {
            case 'GET':
                manejarGetEspecies();
                break;

            case 'POST':

                if (!requerir_roles([
                    'Administrador',
                    'Super Administrador'
                ])) {
                    return;
                }

                $datos = json_decode(
                    file_get_contents('php://input'),
                    true
                );
                if (!is_array($datos)) {
                    responderJson(
                        false,
                        'JSON inválido.',
                        null,
                        400
                    );
                    return;
                }

                $especie = registrarEspecie($datos);
                responderJson(
                    true,
                    'Especie creada correctamente.',
                    $especie,
                    201
                );
                break;

            case 'PUT':
                if (!requerir_roles([
                    'Administrador',
                    'Super Administrador'
                ])) {
                    return;
                }

                $id = filter_var(
                    $_GET['id'] ?? null,
                    FILTER_VALIDATE_INT
                );
                if ($id === false || $id <= 0) {
                    responderJson(
                        false,
                        'El ID de especie no es válido.',
                        null,
                        400
                    );
                    return;
                }
                $datos = json_decode(
                    file_get_contents('php://input'),
                    true
                );

                if (!is_array($datos)) {
                    responderJson(
                        false,
                        'JSON inválido.',
                        null,
                        400
                    );
                    return;
                }
                $especie = modificarEspecie(
                    (int)$id,
                    $datos
                );
                responderJson(
                    true,
                    'Especie actualizada correctamente.',
                    $especie,
                    200
                );
                break;

            case 'DELETE':
                if (!requerir_roles([
                    'Administrador',
                    'Super Administrador'
                ])) {
                    return;
                }

                $id = filter_var(
                    $_GET['id'] ?? null,
                    FILTER_VALIDATE_INT
                );
                if ($id === false || $id <= 0) {
                    responderJson(
                        false,
                        'El ID de especie no es válido.',
                        null,
                        400
                    );
                    return;
                }

                $especie = darBajaEspecie((int)$id);
                responderJson(
                    true,
                    'Especie eliminada correctamente.',
                    $especie,
                    200
                );
                break;

            default:
                responderJson(
                    false,
                    'Método no permitido.',
                    null,
                    405
                );
        }

    } catch (InvalidArgumentException $e) {
        responderJson(
            false,
            $e->getMessage(),
            null,
            400
        );

    } catch (RuntimeException $e) {
        responderJson(
            false,
            $e->getMessage(),
            null,
            500
        );

    } catch (Throwable $e) {
        error_log(
            'ERROR ESPECIES: ' .
            $e->getMessage()
        );

        responderJson(
            false,
            'Error al procesar la especie.',
            null,
            500
        );
    }
}


function manejarGetEspecies() {
    $especies = listarEspecies();
    responderJson(
        true,
        null,
        $especies,
        200
    );
}