<?php

require_once __DIR__ . '/../services/desparasitaciones.php';
require_once __DIR__ . '/../utils/response.php';

function manejar_desparasitaciones(): void
{
    $metodo = $_SERVER['REQUEST_METHOD'];

    try {

        if ($metodo === 'GET') {

            if (!isset($_GET['id_ficha_medica'])) {
                responderJson(
                    false,
                    'Debe indicar id_ficha_medica.',
                    null,
                    400
                );
                return;
            }

            $id_ficha_medica = filter_var(
                $_GET['id_ficha_medica'],
                FILTER_VALIDATE_INT
            );

            if (
                $id_ficha_medica === false ||
                $id_ficha_medica <= 0
            ) {
                responderJson(
                    false,
                    'El id de la ficha médica no es válido.',
                    null,
                    400
                );
                return;
            }

            $desparasitaciones =
                listar_desparasitaciones_por_ficha(
                    $id_ficha_medica
                );

            responderJson(
                true,
                null,
                $desparasitaciones,
                200
            );

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
                    'El cuerpo de la solicitud no es válido.',
                    null,
                    400
                );
                return;
            }

            $desparasitacion =
                registrar_desparasitacion($datos);

            responderJson(
                true,
                'Desparasitación registrada correctamente.',
                $desparasitacion,
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

    } catch (Throwable $e) {

        responderJson(
            false,
            'Error al procesar la desparasitación.',
            null,
            500
        );
    }
}