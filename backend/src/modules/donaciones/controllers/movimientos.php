<?php

require_once __DIR__ . '/../services/movimientos.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../../middleware/autenticacion.php';


function es_admin_actual_donaciones(): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario_rol'])) {
        return false;
    }

    return (int) $_SESSION['usuario_rol'] >= 2;
}



// CATÁLOGOS
function manejar_tipos_aportes(): void {
    $metodo = $_SERVER['REQUEST_METHOD'];
    try {
        if ($metodo === 'GET') {
            if (!requerir_autenticacion()) {
                return;
            }
            responderJson(
                true,
                null,
                listar_tipos_aportes(),
                200
            );
            return;
        }
        if (!requerir_roles([
            'Administrador',
            'Super Administrador'
        ])) {
            return;
        }
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
            responderJson(
                true,
                'Tipo de aporte creado correctamente.',
                registrar_tipo_aporte($datos),
                201
            );
            return;
        }
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );
        if ($id === false || $id <= 0) {
            responderJson(
                false,
                'El ID del tipo de aporte no es válido.',
                null,
                400
            );
            return;
        }
        if ($metodo === 'PUT') {
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

            responderJson(
                true,
                'Tipo de aporte actualizado correctamente.',
                modificar_tipo_aporte((int)$id, $datos),
                200
            );
            return;
        }

        if ($metodo === 'DELETE') {
            responderJson(
                true,
                'Tipo de aporte eliminado correctamente.',
                dar_baja_tipo_aporte((int)$id),
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
    } catch (Throwable $e) {
        error_log(
            'ERROR TIPOS APORTES: ' .
            $e->getMessage()
        );
        responderJson(
            false,
            'Error al procesar tipos de aportes.',
            null,
            500
        );
    }
}


function manejar_tipos_movimientos_aportes(): void {
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


function manejar_frecuencia_aportes(): void {
    $metodo = $_SERVER['REQUEST_METHOD'];
    try {
        if ($metodo === 'GET') {
            if (!requerir_autenticacion()) {
                return;
            }
            responderJson(
                true,
                null,
                listar_frecuencia_aportes(),
                200
            );
            return;
        }
        if (!requerir_roles([
            'Administrador',
            'Super Administrador'
        ])) {
            return;
        }
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
            responderJson(
                true,
                'Frecuencia creada correctamente.',
                registrar_frecuencia_aporte($datos),
                201
            );
            return;
        }
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );
        if ($id === false || $id <= 0) {
            responderJson(
                false,
                'El ID de frecuencia no es válido.',
                null,
                400
            );
            return;
        }

        if ($metodo === 'PUT') {
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
            responderJson(
                true,
                'Frecuencia actualizada correctamente.',
                modificar_frecuencia_aporte(
                    (int)$id,
                    $datos
                ),
                200
            );

            return;
        }
        if ($metodo === 'DELETE') {
            responderJson(
                true,
                'Frecuencia eliminada correctamente.',
                dar_baja_frecuencia_aporte((int)$id),
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

    } catch (Throwable $e) {
        error_log(
            'ERROR FRECUENCIAS: ' .
            $e->getMessage()
        );
        responderJson(
            false,
            'Error al procesar frecuencias.',
            null,
            500
        );
    }
}


function manejar_metodos_pago(): void {
    $metodo = $_SERVER['REQUEST_METHOD'];

    try {
        if ($metodo === 'GET') {
            if (!requerir_autenticacion()) {
                return;
            }
            responderJson(
                true,
                null,
                listar_metodos_pago(),
                200
            );
            return;
        }
        if (!requerir_roles([
            'Administrador',
            'Super Administrador'
        ])) {
            return;
        }

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
            responderJson(
                true,
                'Método de pago creado correctamente.',
                registrar_metodo_pago($datos),
                201
            );
            return;
        }
        $id = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );
        if ($id === false || $id <= 0) {
            responderJson(
                false,
                'El ID del método de pago no es válido.',
                null,
                400
            );
            return;
        }

        if ($metodo === 'PUT') {
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
            responderJson(
                true,
                'Método de pago actualizado correctamente.',
                modificar_metodo_pago(
                    (int)$id,
                    $datos
                ),
                200
            );

            return;
        }

        if ($metodo === 'DELETE') {
            responderJson(
                true,
                'Método de pago eliminado correctamente.',
                dar_baja_metodo_pago((int)$id),
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
    } catch (Throwable $e) {
        error_log(
            'ERROR METODOS PAGO: ' .
            $e->getMessage()
        );
        responderJson(
            false,
            'Error al procesar métodos de pago.',
            null,
            500
        );
    }
}


// MOVIMIENTOS
function manejar_movimientos(): void {
    $metodo = $_SERVER['REQUEST_METHOD'];
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $id_usuario_actual = isset($_SESSION['usuario_id'])
        ? (int) $_SESSION['usuario_id']
        : null;

    $es_admin = es_admin_actual_donaciones();

    try {
        // -------- GET --------
        if ($metodo === 'GET') {
            // Para consultar movimientos sí debe estar autenticado
            if (!requerir_autenticacion()) {
                return;
            }

            $id_usuario_actual = (int) $_SESSION['usuario_id'];
            $es_admin = es_admin_actual_donaciones();

            if (isset($_GET['id'])) {
                $id = filter_var(
                    $_GET['id'],
                    FILTER_VALIDATE_INT
                );
                if ($id === false || $id <= 0) {
                    responderJson(
                        false,
                        'El id del movimiento no es válido.',
                        null,
                        400
                    );
                    return;
                }
                $movimiento = buscar_movimiento_por_id(
                    $id,
                    $id_usuario_actual,
                    $es_admin
                );
                responderJson(
                    true,
                    null,
                    $movimiento,
                    200
                );

                return;
            }

            // Listado con filtros
            $filtros = [
                'id_tipo_aporte' =>
                    $_GET['id_tipo_aporte'] ?? null,

                'id_tipo_movimiento' =>
                    $_GET['id_tipo_movimiento'] ?? null,

                'id_metodo_pago' =>
                    $_GET['id_metodo_pago'] ?? null,

                'id_frecuencia_aporte' =>
                    $_GET['id_frecuencia_aporte'] ?? null,

                'fecha_desde' =>
                    $_GET['fecha_desde'] ?? null,

                'fecha_hasta' =>
                    $_GET['fecha_hasta'] ?? null,

                'busqueda' =>
                    $_GET['busqueda'] ?? null,
            ];

            $movimientos = listar_movimientos(
                $filtros,
                $id_usuario_actual,
                $es_admin
            );

            responderJson(
                true,
                null,
                $movimientos,
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
            // Puede recibir un usuario autenticado o null
            $movimiento = registrar_movimiento(
                $id_usuario_actual,
                $datos
            );

            responderJson(
                true,
                'Movimiento registrado correctamente.',
                $movimiento,
                201
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
            'ERROR MOVIMIENTOS DONACIONES: ' .
            $e->getMessage()
        );

        responderJson(
            false,
            'Error al procesar el movimiento.',
            null,
            500
        );
    }
}


// REPORTES
function manejar_reportes_movimientos(): void {
    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo !== 'GET') {
        responderJson(false, 'Método no permitido.', null, 405);
        return;
    }

    if (!requerir_autenticacion()) {
        return;
    }

    if (!es_admin_actual_donaciones()) {
        responderJson(false, 'No tenés permisos para ver reportes.', null, 403);
        return;
    }

    try {
        $filtros = [
            'id_tipo_movimiento' => $_GET['id_tipo_movimiento'] ?? null,
            'id_tipo_aporte' => $_GET['id_tipo_aporte'] ?? null,
            'id_metodo_pago' => $_GET['id_metodo_pago'] ?? null,
            'id_frecuencia_aporte' => $_GET['id_frecuencia_aporte'] ?? null,
            'fecha_desde' => $_GET['fecha_desde'] ?? null,
            'fecha_hasta' => $_GET['fecha_hasta'] ?? null,
        ];

        $agrupado_por = $_GET['agrupado_por'] ?? null;

        if ($agrupado_por === 'tipo_aporte') {
            $data = reporte_por_tipo_aporte($filtros);
        } elseif ($agrupado_por === 'metodo_pago') {
            $data = reporte_por_metodo_pago($filtros);
        } elseif ($agrupado_por === 'frecuencia') {
            $data = reporte_por_frecuencia($filtros);
        } elseif ($agrupado_por === null || $agrupado_por === '') {
            $data = resumen_movimientos($filtros);
        } else {
            responderJson(false, 'El valor de agrupado_por no es válido.', null, 400);
            return;
        }

        responderJson(true, null, $data, 200);

    } catch (InvalidArgumentException $e) {
        responderJson(false, $e->getMessage(), null, 400);
    } catch (Throwable $e) {
        error_log('ERROR REPORTES MOVIMIENTOS: ' . $e->getMessage());
        responderJson(false, 'Error al generar el reporte.', null, 500);
    }
}