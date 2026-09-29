<?php

require_once __DIR__ . '/../services/domicilios.php';
require_once __DIR__ . '/../../../utils/response.php';


function manejar_domicilios()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario_id'])) {
        return responderJson(
            false,
            'Debés iniciar sesión.',
            null,
            401
        );
    }

    $id_usuario = (int) $_SESSION['usuario_id'];


    /*
     * GET
     * Obtener domicilio del usuario autenticado
     */
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        try {

            $domicilio =
                buscar_domicilio_usuario(
                    $id_usuario
                );

            if ($domicilio === false) {
                return responderJson(
                    false,
                    'El usuario todavía no tiene un domicilio registrado.',
                    null,
                    404
                );
            }

            return responderJson(
                true,
                null,
                $domicilio,
                200
            );

        } catch (InvalidArgumentException $e) {

            return responderJson(
                false,
                $e->getMessage(),
                null,
                400
            );

        } catch (Throwable $e) {

            error_log(
                'ERROR GET DOMICILIO: ' .
                $e->getMessage()
            );

            return responderJson(
                false,
                'Error interno',
                null,
                500
            );
        }
    }


    /*
     * POST
     * Crear domicilio del usuario autenticado
     */
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $body = file_get_contents(
            'php://input'
        );

        $datos = json_decode(
            $body,
            true
        );

        if (!is_array($datos)) {
            return responderJson(
                false,
                'JSON inválido',
                null,
                400
            );
        }

        try {

            $domicilio =
                registrar_domicilio_usuario(
                    $id_usuario,
                    $datos
                );

            return responderJson(
                true,
                'Domicilio registrado correctamente.',
                $domicilio,
                201
            );

        } catch (InvalidArgumentException $e) {

            return responderJson(
                false,
                $e->getMessage(),
                null,
                400
            );

        } catch (Throwable $e) {

            error_log(
                'ERROR POST DOMICILIO: ' .
                $e->getMessage()
            );

            return responderJson(
                false,
                'Error interno',
                null,
                500
            );
        }
    }

    /*
    * PUT
    * Actualizar domicilio del usuario autenticado
    */
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

        $body = file_get_contents(
        'php://input'
        );

        $datos = json_decode(
        $body,
        true
        );

    if (!is_array($datos)) {
        return responderJson(
            false,
            'JSON inválido',
            null,
            400
        );
    }

    try {

        $domicilio =
            modificar_domicilio_usuario(
                $id_usuario,
                $datos
            );

        if ($domicilio === false) {
            return responderJson(
                false,
                'El usuario todavía no tiene un domicilio registrado.',
                null,
                404
            );
        }

        return responderJson(
            true,
            'Domicilio actualizado correctamente.',
            $domicilio,
            200
        );

    } catch (InvalidArgumentException $e) {

        return responderJson(
            false,
            $e->getMessage(),
            null,
            400
        );

    } catch (Throwable $e) {

        error_log(
            'ERROR PUT DOMICILIO: ' .
            $e->getMessage()
        );

        return responderJson(
            false,
            'Error interno',
            null,
            500
        );
    }
}

    


    return responderJson(
        false,
        'Método no permitido',
        null,
        405
    );
}