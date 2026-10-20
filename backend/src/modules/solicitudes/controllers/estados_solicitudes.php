<?php

require_once __DIR__ . '/../services/estados_solicitudes.php';
require_once __DIR__ . '/../../../utils/response.php';


function manejar_estados_solicitudes(): void{
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
                        'El id del estado de solicitud no es válido.',
                        null,
                        400
                    );
                    return;
                }

                $estado =
                    buscar_estado_solicitud_catalogo_por_id(
                        $id
                    );

                if (!$estado) {
                    responderJson(
                        false,
                        'Estado de solicitud no encontrado.',
                        null,
                        404
                    );
                    return;
                }

                responderJson(
                    true,
                    null,
                    $estado,
                    200
                );

                return;
            }

            responderJson(
                true,
                null,
                listar_estados_solicitudes(),
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

            $estado =
                registrar_estado_solicitud($datos);

            responderJson(
                true,
                'Estado de solicitud creado correctamente.',
                $estado,
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
                    'El id del estado de solicitud no es válido.',
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

            $estado =
                modificar_estado_solicitud_catalogo(
                    $id,
                    $datos
                );

            responderJson(
                true,
                'Estado de solicitud actualizado correctamente.',
                $estado,
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
                    'El id del estado de solicitud no es válido.',
                    null,
                    400
                );
                return;
            }

            $eliminado =
                eliminar_estado_solicitud_catalogo($id);

            if (!$eliminado) {
                responderJson(
                    false,
                    'Estado de solicitud no encontrado o ya deshabilitado.',
                    null,
                    404
                );
                return;
            }

            responderJson(
                true,
                'Estado de solicitud deshabilitado correctamente.',
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
            'ERROR DB ESTADOS SOLICITUDES: ' .
            $e->getMessage()
        );

        responderJson(
            false,
            'Error al procesar el estado de solicitud.',
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
            'ERROR ESTADOS SOLICITUDES: ' .
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