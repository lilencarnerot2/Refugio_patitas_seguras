<?php

require_once __DIR__ . '/../services/formularios_transito.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../../middleware/autenticacion.php';


function es_admin_actual_transito(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario_rol'])) {
        return false;
    }

    // Roles: 1 = Usuario, 2 = Administrador, 3 = Super Administrador
    return (int) $_SESSION['usuario_rol'] >= 2;
}


function manejar_formularios_transito(): void
{
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
                $id_solicitud = filter_var($_GET['id_solicitud'], FILTER_VALIDATE_INT);

                if ($id_solicitud === false || $id_solicitud <= 0) {
                    responderJson(false, 'El id de la solicitud no es válido.', null, 400);
                    return;
                }

                $formulario = obtener_formulario_transito_por_solicitud($id_solicitud, $id_usuario_actual, $es_admin);

                responderJson(true, null, $formulario, 200);
                return;
            }

            if (isset($_GET['id'])) {
                $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

                if ($id === false || $id <= 0) {
                    responderJson(false, 'El id del formulario no es válido.', null, 400);
                    return;
                }

                $formulario = obtener_formulario_transito($id, $id_usuario_actual, $es_admin);

                responderJson(true, null, $formulario, 200);
                return;
            }

            responderJson(false, 'Debe indicar id o id_solicitud.', null, 400);
            return;
        }

        // -------- POST --------
        if ($metodo === 'POST') {
            $datos = json_decode(file_get_contents('php://input'), true);

            if (!is_array($datos)) {
                responderJson(false, 'JSON inválido.', null, 400);
                return;
            }

            $formulario = registrar_formulario_transito($id_usuario_actual, $datos);

            responderJson(true, 'Formulario de tránsito creado correctamente.', $formulario, 201);
            return;
        }

        // -------- PUT --------
        if ($metodo === 'PUT') {
            $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

            if ($id === false || $id <= 0) {
                responderJson(false, 'El id del formulario no es válido.', null, 400);
                return;
            }

            $datos = json_decode(file_get_contents('php://input'), true);

            if (!is_array($datos)) {
                responderJson(false, 'JSON inválido.', null, 400);
                return;
            }

            $formulario = modificar_formulario_transito($id, $id_usuario_actual, $datos);

            responderJson(true, 'Formulario de tránsito actualizado correctamente.', $formulario, 200);
            return;
        }

        responderJson(false, 'Método no permitido.', null, 405);

    } catch (InvalidArgumentException $e) {
        responderJson(false, $e->getMessage(), null, 400);
    } catch (RuntimeException $e) {
        responderJson(false, $e->getMessage(), null, 404);
    } catch (Throwable $e) {
        error_log('ERROR FORMULARIOS TRANSITO: ' . $e->getMessage());
        responderJson(false, 'Error al procesar el formulario de tránsito.', null, 500);
    }
}