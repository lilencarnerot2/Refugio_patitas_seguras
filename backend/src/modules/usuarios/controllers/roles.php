<?php

require_once __DIR__ . '/../services/roles.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejar_roles()
{
    try {

        /*
         * GET
         */
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $id = isset($_GET['id'])
                ? (int) $_GET['id']
                : null;

            if ($id !== null) {

                $rol = buscar_rol_por_id($id);

                if (!$rol) {
                    return responderJson(
                        false,
                        'Rol no encontrado.',
                        null,
                        404
                    );
                }

                return responderJson(
                    true,
                    null,
                    $rol,
                    200
                );
            }

            return responderJson(
                true,
                null,
                listar_roles(),
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

            $rol = registrar_rol($datos);

            return responderJson(
                true,
                'Rol creado correctamente.',
                $rol,
                201
            );
        }


        /*
         * PUT
         */
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {

            $id = (int) ($_GET['id'] ?? 0);

            if ($id <= 0) {
                throw new InvalidArgumentException(
                    'El id del rol es obligatorio.'
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

            $rol = modificar_rol(
                $id,
                $datos
            );

            if (!$rol) {
                return responderJson(
                    false,
                    'Rol no encontrado.',
                    null,
                    404
                );
            }

            return responderJson(
                true,
                'Rol actualizado correctamente.',
                $rol,
                200
            );
        }


        /*
         * DELETE lógico
         */
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

            $id = (int) ($_GET['id'] ?? 0);

            if ($id <= 0) {
                throw new InvalidArgumentException(
                    'El id del rol es obligatorio.'
                );
            }

            $eliminado = eliminar_rol($id);

            if (!$eliminado) {
                return responderJson(
                    false,
                    'Rol no encontrado.',
                    null,
                    404
                );
            }

            return responderJson(
                true,
                'Rol deshabilitado correctamente.',
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
            'ERROR ROLES: ' .
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