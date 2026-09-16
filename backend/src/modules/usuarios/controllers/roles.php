<?php

require_once __DIR__ . '/../services/roles.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejar_roles(): void
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
                    'El id del rol no es válido.',
                    null,
                    400
                );
                return;
            }

            $rol = buscar_rol_por_id($id);

            if (!$rol) {
                responderJson(
                    false,
                    'Rol no encontrado.',
                    null,
                    404
                );
                return;
            }

            responderJson(
                true,
                null,
                $rol,
                200
            );

            return;
        }

        responderJson(
            true,
            null,
            listar_roles(),
            200
        );

    } catch (Throwable $e) {

        responderJson(
            false,
            'Error al obtener los roles.',
            null,
            500
        );
    }
}