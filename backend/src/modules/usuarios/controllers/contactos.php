<?php

require_once __DIR__ . '/../services/contactos.php';
require_once __DIR__ . '/../../../utils/response.php';


function manejar_contactos()
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

    $id_usuario =
        (int) $_SESSION['usuario_id'];

    try {

        /*
         * GET
         */
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            return responderJson(
                true,
                null,
                listar_contactos_usuario(
                    $id_usuario
                ),
                200
            );
        }


        /*
         * POST
         */
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $datos = json_decode(
                file_get_contents('php://input'),
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

            $contacto =
                registrar_contacto_usuario(
                    $id_usuario,
                    $datos
                );

            return responderJson(
                true,
                'Contacto registrado correctamente.',
                $contacto,
                201
            );
        }


        /*
         * PUT
         */
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

            $id_contacto =
                (int) ($_GET['id'] ?? 0);

            if ($id_contacto <= 0) {
                throw new InvalidArgumentException(
                    'El id del contacto es obligatorio.'
                );
            }

            $datos = json_decode(
                file_get_contents('php://input'),
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

            $contacto =
                modificar_contacto_usuario(
                    $id_contacto,
                    $id_usuario,
                    $datos
                );

            if ($contacto === false) {
                return responderJson(
                    false,
                    'Contacto no encontrado.',
                    null,
                    404
                );
            }

            return responderJson(
                true,
                'Contacto actualizado correctamente.',
                $contacto,
                200
            );
        }


        /*
         * DELETE lógico
         */
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

            $id_contacto =
                (int) ($_GET['id'] ?? 0);

            if ($id_contacto <= 0) {
                throw new InvalidArgumentException(
                    'El id del contacto es obligatorio.'
                );
            }

            $eliminado =
                eliminar_contacto_usuario(
                    $id_contacto,
                    $id_usuario
                );

            if (!$eliminado) {
                return responderJson(
                    false,
                    'Contacto no encontrado.',
                    null,
                    404
                );
            }

            return responderJson(
                true,
                'Contacto eliminado correctamente.',
                null,
                200
            );
        }


        return responderJson(
            false,
            'Método no permitido',
            null,
            405
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
            'ERROR CONTACTOS: ' .
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