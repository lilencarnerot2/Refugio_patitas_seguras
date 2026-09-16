<?php

require_once __DIR__ . '/../services/solicitudes.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejar_solicitudes(): void
{
    $metodo = $_SERVER['REQUEST_METHOD'];

    try {

        // GET
        if ($metodo === 'GET') {

            if (isset($_GET['id'])) {

                $id = filter_var(
                    $_GET['id'],
                    FILTER_VALIDATE_INT
                );

                if ($id === false || $id <= 0) {
                    responderJson(
                        false,
                        'El id de la solicitud no es válido.',
                        null,
                        400
                    );
                    return;
                }

                $solicitud = buscar_solicitud_por_id($id);

                if (!$solicitud) {
                    responderJson(
                        false,
                        'Solicitud no encontrada.',
                        null,
                        404
                    );
                    return;
                }

                responderJson(
                    true,
                    null,
                    $solicitud,
                    200
                );

                return;
            }

            $solicitudes = listar_solicitudes();

            responderJson(
                true,
                null,
                $solicitudes,
                200
            );

            return;
        }

        // PUT
        if ($metodo === 'PUT') {

            if (!isset($_GET['id'])) {
                responderJson(
                    false,
                    'Debe indicar el id de la solicitud.',
                    null,
                    400
                );
                return;
            }

            $id_solicitud = filter_var(
                $_GET['id'],
                FILTER_VALIDATE_INT
            );

            if ($id_solicitud === false || $id_solicitud <= 0) {
                responderJson(
                    false,
                    'El id de la solicitud no es válido.',
                    null,
                    400
                );
                return;
            }

            $datos = json_decode(
                file_get_contents('php://input'),
                true
            );

            if (
                !is_array($datos) ||
                !isset($datos['id_estado_solicitud'])
            ) {
                responderJson(
                    false,
                    'Debe indicar el estado de la solicitud.',
                    null,
                    400
                );
                return;
            }

            $id_estado_solicitud = filter_var(
                $datos['id_estado_solicitud'],
                FILTER_VALIDATE_INT
            );

            if (
                $id_estado_solicitud === false ||
                $id_estado_solicitud <= 0
            ) {
                responderJson(
                    false,
                    'El estado de la solicitud no es válido.',
                    null,
                    400
                );
                return;
            }

            $solicitud = cambiar_estado_solicitud(
                $id_solicitud,
                $id_estado_solicitud
            );

            responderJson(
                true,
                'Estado de la solicitud actualizado correctamente.',
                $solicitud,
                200
            );

            return;
        }

        // Cualquier otro método
        responderJson(
            false,
            'Método no permitido.',
            null,
            405
        );

    } catch (InvalidArgumentException $e) {

        responderJson(
            false,
            $e->getMessage(),
            null,
            400
        );

    } catch (Throwable $e) {

        responderJson(
            false,
            'Error al procesar la solicitud.',
            null,
            500
        );
    }
}