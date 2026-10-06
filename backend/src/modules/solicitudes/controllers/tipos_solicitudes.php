<?php

require_once __DIR__ . '/../services/tipos_solicitudes.php';
require_once __DIR__ . '/../../../utils/response.php';


function manejar_tipos_solicitudes(): void
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
                        'El id del tipo de solicitud no es válido.',
                        null,
                        400
                    );
                    return;
                }

                $tipo =
                    buscar_tipo_solicitud_por_id($id);

                if (!$tipo) {
                    responderJson(
                        false,
                        'Tipo de solicitud no encontrado.',
                        null,
                        404
                    );
                    return;
                }

                responderJson(
                    true,
                    null,
                    $tipo,
                    200
                );

                return;
            }

            responderJson(
                true,
                null,
                listar_tipos_solicitudes(),
                200
            );

            return;
        }


        // POST
        if ($metodo === 'POST') {

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

            $tipo =
                registrar_tipo_solicitud($datos);

            responderJson(
                true,
                'Tipo de solicitud creado correctamente.',
                $tipo,
                201
            );

            return;
        }


        // PUT
        if ($metodo === 'PUT') {

            $id = filter_var(
                $_GET['id'] ?? null,
                FILTER_VALIDATE_INT
            );

            if ($id === false || $id <= 0) {
                responderJson(
                    false,
                    'El id del tipo de solicitud no es válido.',
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

            $tipo =
                modificar_tipo_solicitud(
                    $id,
                    $datos
                );

            responderJson(
                true,
                'Tipo de solicitud actualizado correctamente.',
                $tipo,
                200
            );

            return;
        }


        // DELETE lógico
        if ($metodo === 'DELETE') {

            $id = filter_var(
                $_GET['id'] ?? null,
                FILTER_VALIDATE_INT
            );

            if ($id === false || $id <= 0) {
                responderJson(
                    false,
                    'El id del tipo de solicitud no es válido.',
                    null,
                    400
                );
                return;
            }

            $eliminado =
                eliminar_tipo_solicitud($id);

            if (!$eliminado) {
                responderJson(
                    false,
                    'Tipo de solicitud no encontrado o ya deshabilitado.',
                    null,
                    404
                );
                return;
            }

            responderJson(
                true,
                'Tipo de solicitud deshabilitado correctamente.',
                null,
                200
            );

            return;
        }


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

    } catch (PDOException $e) {

        error_log(
            'ERROR DB TIPOS SOLICITUDES: ' .
            $e->getMessage()
        );

        responderJson(
            false,
            'Error al procesar el tipo de solicitud.',
            null,
            500
        );

    } catch (RuntimeException $e) {

        responderJson(
            false,
            $e->getMessage(),
            null,
            404
        );

    } catch (Throwable $e) {

        error_log(
            'ERROR TIPOS SOLICITUDES: ' .
            $e->getMessage()
        );

        responderJson(
            false,
            'Error interno.',
            null,
            500
        );
    }
}