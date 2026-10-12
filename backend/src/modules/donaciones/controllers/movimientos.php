<?php

require_once __DIR__ . '/../services/movimientos.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../../middleware/autenticacion.php';


function es_admin_actual_donaciones(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario_rol'])) {
        return false;
    }

    return (int) $_SESSION['usuario_rol'] >= 2;
}


// ============================================================
// CATÁLOGOS
// ============================================================

function manejar_tipos_aportes(): void
{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            responderJson(false, 'Método no permitido.', null, 405);
            return;
        }
        if (!requerir_autenticacion()) {
            return;
        }
        responderJson(true, null, listar_tipos_aportes(), 200);
    } catch (Throwable $e) {
        responderJson(false, 'Error al listar tipos de aportes.', null, 500);
    }
}


function manejar_tipos_movimientos_aportes(): void
{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            responderJson(false, 'Método no permitido.', null, 405);
            return;
        }
        if (!requerir_autenticacion()) {
            return;
        }
        responderJson(true, null, listar_tipos_movimientos_aportes(), 200);
    } catch (Throwable $e) {
        responderJson(false, 'Error al listar tipos de movimientos.', null, 500);
    }
}


function manejar_frecuencia_aportes(): void
{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            responderJson(false, 'Método no permitido.', null, 405);
            return;
        }
        if (!requerir_autenticacion()) {
            return;
        }
        responderJson(true, null, listar_frecuencia_aportes(), 200);
    } catch (Throwable $e) {
        responderJson(false, 'Error al listar frecuencias de aportes.', null, 500);
    }
}


function manejar_metodos_pago(): void
{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            responderJson(false, 'Método no permitido.', null, 405);
            return;
        }
        if (!requerir_autenticacion()) {
            return;
        }
        responderJson(true, null, listar_metodos_pago(), 200);
    } catch (Throwable $e) {
        responderJson(false, 'Error al listar métodos de pago.', null, 500);
    }
}


// ============================================================
// MOVIMIENTOS
// ============================================================

function manejar_movimientos(): void
{
    $metodo = $_SERVER['REQUEST_METHOD'];

    if (!requerir_autenticacion()) {
        return;
    }

    $id_usuario_actual = (int) $_SESSION['usuario_id'];
    $es_admin = es_admin_actual_donaciones();

    try {
        // -------- GET --------
        if ($metodo === 'GET') {

            if (isset($_GET['id'])) {
                $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

                if ($id === false || $id <= 0) {
                    responderJson(false, 'El id del movimiento no es válido.', null, 400);
                    return;
                }

                $movimiento = buscar_movimiento_por_id($id, $id_usuario_actual, $es_admin);

                responderJson(true, null, $movimiento, 200);
                return;
            }

            // Listado con filtros
            $filtros = [
                'id_tipo_aporte' => $_GET['id_tipo_aporte'] ?? null,
                'id_tipo_movimiento' => $_GET['id_tipo_movimiento'] ?? null,
                'id_metodo_pago' => $_GET['id_metodo_pago'] ?? null,
                'id_frecuencia_aporte' => $_GET['id_frecuencia_aporte'] ?? null,
                'fecha_desde' => $_GET['fecha_desde'] ?? null,
                'fecha_hasta' => $_GET['fecha_hasta'] ?? null,
                'busqueda' => $_GET['busqueda'] ?? null,
            ];

            $movimientos = listar_movimientos($filtros, $id_usuario_actual, $es_admin);

            responderJson(true, null, $movimientos, 200);
            return;
        }

        // -------- POST --------
        if ($metodo === 'POST') {
            $datos = json_decode(file_get_contents('php://input'), true);

            if (!is_array($datos)) {
                responderJson(false, 'JSON inválido.', null, 400);
                return;
            }

            $movimiento = registrar_movimiento($id_usuario_actual, $datos);

            responderJson(true, 'Movimiento registrado correctamente.', $movimiento, 201);
            return;
        }

        responderJson(false, 'Método no permitido.', null, 405);

    } catch (InvalidArgumentException $e) {
        responderJson(false, $e->getMessage(), null, 400);
    } catch (RuntimeException $e) {
        responderJson(false, $e->getMessage(), null, 404);
    } catch (Throwable $e) {
        error_log('ERROR MOVIMIENTOS DONACIONES: ' . $e->getMessage());
        responderJson(false, 'Error al procesar el movimiento.', null, 500);
    }
}