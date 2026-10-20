<?php

require_once __DIR__ . '/../services/motivos_adopcion.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../../middleware/autorizacion.php';

function manejar_motivos_adopcion(): void {
    $metodo = $_SERVER['REQUEST_METHOD'];

    try {
        switch ($metodo) {
            case 'GET':
                $motivos = listar_motivos_adopcion();
                responderJson(
                    true,
                    'Motivos de adopción obtenidos correctamente.',
                    $motivos,
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
                $motivo = registrar_motivo_adopcion($datos);
                responderJson(
                    true,
                    'Motivo de adopción creado correctamente.',
                    $motivo,
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
                        'El ID del motivo de adopción no es válido.',
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
                $motivo = modificar_motivo_adopcion(
                    (int)$id,
                    $datos
                );
                responderJson(
                    true,
                    'Motivo de adopción actualizado correctamente.',
                    $motivo,
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
                        'El ID del motivo de adopción no es válido.',
                        null,
                        400
                    );
                    return;
                }
                $motivo = dar_baja_motivo_adopcion(
                    (int)$id
                );
                responderJson(
                    true,
                    'Motivo de adopción eliminado correctamente.',
                    $motivo,
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
            'ERROR MOTIVOS ADOPCION: ' .
            $e->getMessage()
        );
        responderJson(
            false,
            'Error al procesar el motivo de adopción.',
            null,
            500
        );
    }
}