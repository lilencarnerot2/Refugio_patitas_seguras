<?php

require_once __DIR__ . '/../services/tipos_vivienda.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../../middleware/autorizacion.php';

function manejar_tipos_vivienda(){
    $metodo = $_SERVER['REQUEST_METHOD'];

    try {
        switch ($metodo) {
            case 'GET':

                $tipos_vivienda = listar_tipos_vivienda();

                responderJson(
                    true,
                    null,
                    $tipos_vivienda,
                    200
                );
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
                $tipo = registrar_tipo_vivienda($datos);
                responderJson(
                    true,
                    'Tipo de vivienda creado correctamente.',
                    $tipo,
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
                        'El ID del tipo de vivienda no es válido.',
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
                $tipo = modificar_tipo_vivienda(
                    (int)$id,
                    $datos
                );
                responderJson(
                    true,
                    'Tipo de vivienda actualizado correctamente.',
                    $tipo,
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
                        'El ID del tipo de vivienda no es válido.',
                        null,
                        400
                    );
                    return;
                }
                $tipo = dar_baja_tipo_vivienda(
                    (int)$id
                );

                responderJson(
                    true,
                    'Tipo de vivienda eliminado correctamente.',
                    $tipo,
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
            'ERROR TIPOS VIVIENDA: ' .
            $e->getMessage()
        );

        responderJson(
            false,
            'Error al procesar el tipo de vivienda.',
            null,
            500
        );
    }
}