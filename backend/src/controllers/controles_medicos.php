<?php

require_once __DIR__ . '/../services/controles_medicos.php';
require_once __DIR__ . '/../utils/response.php';

function manejar_controles_medicos(): void
{
    $metodo = $_SERVER['REQUEST_METHOD'];

    try {

        // GET
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

            $controles = listar_controles_por_ficha(
                $id_ficha_medica
            );

            responderJson(
                true,
                null,
                $controles,
                200
            );

            return;
        }

        // POST
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

            $control = registrar_control_medico($datos);

            responderJson(
                true,
                'Control médico registrado correctamente.',
                $control,
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
            'Error al procesar el control médico.',
            null,
            500
        );
    }
}