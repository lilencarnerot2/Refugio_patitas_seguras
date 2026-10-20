<?php

require_once __DIR__ . '/../services/razas.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../../middleware/autorizacion.php';

function manejarRazas(){
    $metodo = $_SERVER['REQUEST_METHOD'];

    try {

        switch ($metodo) {

            case 'GET':
                manejarGetRazas();
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

                $raza = registrarRaza($datos);

                responderJson(
                    true,
                    'Raza creada correctamente.',
                    $raza,
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
                        'El ID de raza no es válido.',
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
                $raza = modificarRaza(
                    (int)$id,
                    $datos
                );
                responderJson(
                    true,
                    'Raza actualizada correctamente.',
                    $raza,
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
                        'El ID de raza no es válido.',
                        null,
                        400
                    );
                    return;
                }

                $raza = darBajaRaza((int)$id);
                responderJson(
                    true,
                    'Raza eliminada correctamente.',
                    $raza,
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
            'ERROR RAZAS: ' .
            $e->getMessage()
        );
        responderJson(
            false,
            'Error al procesar la raza.',
            null,
            500
        );
    }
}


function manejarGetRazas(){
    $idEspecie = $_GET['id_especie'] ?? null;
    if (
        $idEspecie === null ||
        !ctype_digit($idEspecie)
    ) {
        responderJson(
            false,
            'El ID de especie debe ser un número entero.',
            null,
            400
        );

        return;
    }
    $razas = listarRazasPorEspecie(
        (int)$idEspecie
    );
    responderJson(
        true,
        null,
        $razas,
        200
    );
}