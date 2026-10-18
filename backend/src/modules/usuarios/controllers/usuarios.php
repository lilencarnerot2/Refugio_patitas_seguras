<?php

require_once __DIR__ . '/../services/usuarios.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejar_usuarios(): void{
    $metodo = $_SERVER['REQUEST_METHOD'];
    try {
        if ($metodo === 'GET') {
            if (isset($_GET['id'])) {
                $id = filter_var(
                    $_GET['id'],
                    FILTER_VALIDATE_INT
                );
                if ($id === false || $id <= 0) {
                    responderJson(
                        false,
                        'El id del usuario no es válido.',
                        null,
                        400
                    );
                    return;
                }
                $usuario = buscar_usuario_por_id($id);
                if (!$usuario) {
                    responderJson(
                        false,
                        'Usuario no encontrado.',
                        null,
                        404
                    );
                    return;
                }
                responderJson(
                    true,
                    null,
                    $usuario,
                    200
                );
                return;
            }
            if (isset($_GET['uid_pp2'])) {
                $uid_pp2 = trim($_GET['uid_pp2']);
                if ($uid_pp2 === '') {
                    responderJson(
                        false,
                        'El uid_pp2 no es válido.',
                        null,
                        400
                    );
                    return;
                }
                $usuario = buscar_usuario_por_uid_pp2(
                    $uid_pp2
                );
                if (!$usuario) {
                    responderJson(
                        false,
                        'Usuario no encontrado.',
                        null,
                        404
                    );
                    return;
                }
                responderJson(
                    true,
                    null,
                    $usuario,
                    200
                );
                return;
            }

            responderJson(
                true,
                null,
                listar_usuarios(),
                200
            );
            return;
        }


        if ($metodo === 'PUT') {
            $id = filter_var(
                $_GET['id'] ?? null,
                FILTER_VALIDATE_INT
            );
            if ($id === false || $id <= 0) {
                responderJson(
                    false,
                    'El id del usuario no es válido.',
                    null,
                    400
                );
                return;
            }

            $accion = $_GET['accion'] ?? '';

            if ($accion === 'activar') {
                $id_usuario_actual = (int) $_SESSION['usuario_id'];

            $usuario_actual =
            buscar_usuario_por_id($id_usuario_actual);

            if (!$usuario_actual) {
                responderJson(
                    false,
                    'Usuario autenticado no encontrado.',
                    null,
                    401
                );
                return;
            }

            $activado = habilitar_usuario(
            $id,
            $id_usuario_actual,
            $usuario_actual['nombre_rol']
            );

                if (!$activado) {
                    responderJson(
                        false,
                        'Usuario no encontrado o ya habilitado.',
                        null,
                        404
                    );
                    return;
                }
                responderJson(
                    true,
                    'Usuario activado correctamente.',
                    null,
                    200
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
            $usuario = modificar_usuario(
                $id,
                $datos
            );
            if (!$usuario) {
                responderJson(
                    false,
                    'Usuario no encontrado.',
                    null,
                    404
                );
                return;
            }
            responderJson(
                true,
                'Usuario actualizado correctamente.',
                $usuario,
                200
            );
            return;
        }


        if ($metodo === 'DELETE') {
            $id = filter_var(
                $_GET['id'] ?? null,
                FILTER_VALIDATE_INT
            );
            if ($id === false || $id <= 0) {
                responderJson(
                    false,
                    'El id del usuario no es válido.',
                    null,
                    400
                );
                return;
            }

            $id_usuario_actual =
                (int) $_SESSION['usuario_id'];
            $usuario_actual =
                buscar_usuario_por_id(
                    $id_usuario_actual
                );
            if (!$usuario_actual) {
                responderJson(
                    false,
                    'Usuario autenticado no encontrado.',
                    null,
                    401
                );
                return;
            }
            $eliminado = eliminar_usuario(
                $id,
                $id_usuario_actual,
                $usuario_actual['nombre_rol']
            );
            if (!$eliminado) {
                responderJson(
                    false,
                    'Usuario no encontrado o ya deshabilitado.',
                    null,
                    404
                );
                return;
            }
            responderJson(
                true,
                'Usuario deshabilitado correctamente.',
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
    } catch (Throwable $e) {
        error_log(
            'ERROR USUARIOS: ' .
            $e->getMessage() .
            ' | Archivo: ' .
            $e->getFile() .
            ' | Línea: ' .
            $e->getLine()
        );
        responderJson(
            false,
            'Error interno.',
            null,
            500
        );
    }
}


function manejar_rol_usuario(): void{
    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            responderJson(
                false,
                'Método no permitido.',
                null,
                405
            );
            return;
        }
        $id_usuario = filter_var(
            $_GET['id'] ?? null,
            FILTER_VALIDATE_INT
        );
        if (
            $id_usuario === false ||
            $id_usuario <= 0
        ) {
            responderJson(
                false,
                'El id del usuario no es válido.',
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
        $id_rol = filter_var(
            $datos['id_rol'] ?? null,
            FILTER_VALIDATE_INT
        );
        if ($id_rol === false || $id_rol <= 0) {
            responderJson(
                false,
                'El id del rol no es válido.',
                null,
                400
            );
            return;
        }
        $actualizado = cambiar_rol_usuario(
            $id_usuario,
            $id_rol,
            (int) $_SESSION['usuario_id']
        );
        if (!$actualizado) {
            responderJson(
                false,
                'Usuario no encontrado.',
                null,
                404
            );
            return;
        }
        responderJson(
            true,
            'Rol del usuario actualizado correctamente.',
            null,
            200
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
            'ERROR ROL USUARIO: ' .
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