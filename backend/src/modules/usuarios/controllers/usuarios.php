<?php

require_once __DIR__ . '/../services/usuarios.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejar_usuarios(): void
{
    $metodo = $_SERVER['REQUEST_METHOD'];

    if ($metodo !== 'GET') {
        responderJson(
            false,
            'Método no permitido.',
            null,
            405
        );
        return;
    }

    try {

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

    } catch (InvalidArgumentException $e) {

        responderJson(
            false,
            $e->getMessage(),
            null,
            400
        );

    } catch (Throwable $e) {

    error_log(
        'ERROR REGISTRO: ' .
        $e->getMessage() .
        ' | Archivo: ' .
        $e->getFile() .
        ' | Línea: ' .
        $e->getLine()
    );

    responderJson(
        false,
        'Error interno',
        null,
        500
    );

    return;
}
}