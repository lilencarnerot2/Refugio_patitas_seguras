<?php

require_once __DIR__ . '/../services/formularios_transito.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../../middleware/autenticacion.php';


function es_admin_actual_transito(): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario_rol'])) {
        return false;
    }

    // Roles: 1 = Usuario, 2 = Administrador, 3 = Super Administrador
    return (int) $_SESSION['usuario_rol'] >= 2;
}


function manejar_formularios_transito(): void {
    $metodo = $_SERVER['REQUEST_METHOD'];

    if (!requerir_autenticacion()) {
        return;
    }

    $id_usuario_actual = (int) $_SESSION['usuario_id'];
    $es_admin = es_admin_actual_transito();

    try {

        // -------- GET --------
        if ($metodo === 'GET') {

            if (isset($_GET['id_solicitud'])) {
                $id_solicitud = filter_var(
                    $_GET['id_solicitud'],
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

                $formulario = obtener_formulario_transito_por_solicitud(
                    $id_solicitud,
                    $id_usuario_actual,
                    $es_admin
                );

                responderJson(
                    true,
                    null,
                    $formulario,
                    200
                );
                return;
            }

            if (isset($_GET['id'])) {
                $id = filter_var(
                    $_GET['id'],
                    FILTER_VALIDATE_INT
                );

                if ($id === false || $id <= 0) {
                    responderJson(
                        false,
                        'El id del formulario no es válido.',
                        null,
                        400
                    );
                    return;
                }

                $formulario = obtener_formulario_transito(
                    $id,
                    $id_usuario_actual,
                    $es_admin
                );

                responderJson(
                    true,
                    null,
                    $formulario,
                    200
                );
                return;
            }

            // Listado con filtros
            $filtros = [
                'estado' => $_GET['estado'] ?? null,
                'id_tipo_vivienda' => $_GET['id_tipo_vivienda'] ?? null,
                'disponibilidad_desde' => $_GET['disponibilidad_desde'] ?? null,
                'disponibilidad_hasta' => $_GET['disponibilidad_hasta'] ?? null,
                'busqueda' => $_GET['busqueda'] ?? null,
            ];

            $formularios = listar_formularios_transito(
                $filtros,
                $id_usuario_actual,
                $es_admin
            );

            responderJson(
                true,
                null,
                $formularios,
                200
            );
            return;
        }


        // -------- POST --------
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

            $formulario = registrar_formulario_transito(
                $id_usuario_actual,
                $datos
            );

            responderJson(
                true,
                'Formulario de tránsito creado correctamente.',
                $formulario,
                201
            );
            return;
        }


        // -------- PUT --------
        if ($metodo === 'PUT') {

            $accion = $_GET['accion'] ?? null;

            // Finalizar tránsito
            if ($accion === 'finalizar') {

                if (!$es_admin) {
                    responderJson(
                        false,
                        'No tenés permisos para finalizar un tránsito.',
                        null,
                        403
                    );
                    return;
                }

                $id_solicitud = filter_var(
                    $_GET['id_solicitud'] ?? null,
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

                finalizar_transito($id_solicitud);

                responderJson(
                    true,
                    'Tránsito finalizado correctamente.',
                    null,
                    200
                );
                return;
            }

            // Modificación normal del formulario
            $id = filter_var(
                $_GET['id'] ?? null,
                FILTER_VALIDATE_INT
            );

            if ($id === false || $id <= 0) {
                responderJson(
                    false,
                    'El id del formulario no es válido.',
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

            $formulario = modificar_formulario_transito(
                $id,
                $id_usuario_actual,
                $datos
            );

            responderJson(
                true,
                'Formulario de tránsito actualizado correctamente.',
                $formulario,
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

    } catch (RuntimeException $e) {

        responderJson(
            false,
            $e->getMessage(),
            null,
            404
        );

    } catch (Throwable $e) {

        error_log(
            'ERROR FORMULARIOS TRANSITO: ' . $e->getMessage()
        );

        responderJson(
            false,
            'Error al procesar el formulario de tránsito.',
            null,
            500
        );
    }
}