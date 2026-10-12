<?php

require_once __DIR__ . '/../services/formularios_adopcion.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../../middleware/autenticacion.php';


function es_admin_actual(): bool
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


function manejar_compromisos_adopcion(): void
{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            responderJson(false, 'Método no permitido.', null, 405);
            return;
        }

        if (!requerir_autenticacion()) {
            return;
        }

        responderJson(true, null, listar_compromisos_adopcion(), 200);
    } catch (Throwable $e) {
        responderJson(false, 'Error al listar compromisos.', null, 500);
    }
}


function manejar_condiciones_especiales(): void
{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            responderJson(false, 'Método no permitido.', null, 405);
            return;
        }

        if (!requerir_autenticacion()) {
            return;
        }

        responderJson(true, null, listar_condiciones_especiales(), 200);
    } catch (Throwable $e) {
        responderJson(false, 'Error al listar condiciones especiales.', null, 500);
    }
}


function manejar_tipos_mascotas(): void
{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            responderJson(false, 'Método no permitido.', null, 405);
            return;
        }

        if (!requerir_autenticacion()) {
            return;
        }

        responderJson(true, null, listar_tipos_mascotas(), 200);
    } catch (Throwable $e) {
        responderJson(false, 'Error al listar tipos de mascotas.', null, 500);
    }
}


function manejar_formularios_adopcion(): void
{
    $metodo = $_SERVER['REQUEST_METHOD'];

    if (!requerir_autenticacion()) {
        return;
    }

    $id_usuario_actual = (int) $_SESSION['usuario_id'];
    $es_admin = es_admin_actual();

    try {
                // -------- GET --------
        if ($metodo === 'GET') {

            if (isset($_GET['id_solicitud'])) {
                $id_solicitud = filter_var($_GET['id_solicitud'], FILTER_VALIDATE_INT);

                if ($id_solicitud === false || $id_solicitud <= 0) {
                    responderJson(false, 'El id de la solicitud no es válido.', null, 400);
                    return;
                }

                $formulario = obtener_formulario_por_solicitud($id_solicitud, $id_usuario_actual, $es_admin);

                responderJson(true, null, $formulario, 200);
                return;
            }

            if (isset($_GET['id'])) {
                $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

                if ($id === false || $id <= 0) {
                    responderJson(false, 'El id del formulario no es válido.', null, 400);
                    return;
                }

                $formulario = obtener_formulario_adopcion($id, $id_usuario_actual, $es_admin);

                responderJson(true, null, $formulario, 200);
                return;
            }

            // Listado con filtros
            $filtros = [
                'estado' => $_GET['estado'] ?? null,
                'id_tipo_vivienda' => $_GET['id_tipo_vivienda'] ?? null,
                'id_condicion_vivienda' => $_GET['id_condicion_vivienda'] ?? null,
                'hay_ninos' => $_GET['hay_ninos'] ?? null,
                'tiene_mascotas' => $_GET['tiene_mascotas'] ?? null,
                'busqueda' => $_GET['busqueda'] ?? null,
            ];

            $formularios = listar_formularios_adopcion($filtros, $id_usuario_actual, $es_admin);

            responderJson(true, null, $formularios, 200);
            return;
           
       
        }

        // -------- POST --------
        if ($metodo === 'POST') {
            $datos = json_decode(file_get_contents('php://input'), true);

            if (!is_array($datos)) {
                responderJson(false, 'JSON inválido.', null, 400);
                return;
            }

            $formulario = registrar_formulario_adopcion($id_usuario_actual, $datos);

            responderJson(true, 'Formulario de adopción creado correctamente.', $formulario, 201);
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

            $formulario = modificar_formulario_adopcion($id, $id_usuario_actual, $datos);

            responderJson(true, 'Formulario de adopción actualizado correctamente.', $formulario, 200);
            return;
        }

        responderJson(false, 'Método no permitido.', null, 405);

    } catch (InvalidArgumentException $e) {
        responderJson(false, $e->getMessage(), null, 400);
    } catch (RuntimeException $e) {
        responderJson(false, $e->getMessage(), null, 404);
    } catch (Throwable $e) {
        error_log('ERROR FORMULARIOS ADOPCION: ' . $e->getMessage());
        responderJson(false, 'Error al procesar el formulario de adopción.', null, 500);
    }
}

function manejar_condiciones_vivienda(): void
{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            responderJson(false, 'Método no permitido.', null, 405);
            return;
        }

        if (!requerir_autenticacion()) {
            return;
        }

        responderJson(true, null, listar_condiciones_vivienda(), 200);
    } catch (Throwable $e) {
        responderJson(false, 'Error al listar condiciones de vivienda.', null, 500);
    }
}


function manejar_destinos_mascotas(): void
{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            responderJson(false, 'Método no permitido.', null, 405);
            return;
        }

        if (!requerir_autenticacion()) {
            return;
        }

        responderJson(true, null, listar_destinos_mascotas(), 200);
    } catch (Throwable $e) {
        responderJson(false, 'Error al listar destinos de mascotas.', null, 500);
    }
}