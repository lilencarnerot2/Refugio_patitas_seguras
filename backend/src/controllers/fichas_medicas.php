<?php

require_once __DIR__ . '/../services/fichas_medicas.php';
require_once __DIR__ . '/../utils/response.php';

function manejar_fichas_medicas(): void
{
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
                        'El id de la ficha médica no es válido.',
                        null,
                        400
                    );
                    return;
                }

                $ficha = buscar_ficha_medica_por_id($id);

                if (!$ficha) {
                    responderJson(
                        false,
                        'Ficha médica no encontrada.',
                        null,
                        404
                    );
                    return;
                }

                responderJson(
                    true,
                    null,
                    $ficha,
                    200
                );

                return;
            }

            if (isset($_GET['id_mascota'])) {

                $id_mascota = filter_var(
                    $_GET['id_mascota'],
                    FILTER_VALIDATE_INT
                );

                if ($id_mascota === false || $id_mascota <= 0) {
                    responderJson(
                        false,
                        'El id de la mascota no es válido.',
                        null,
                        400
                    );
                    return;
                }

                $ficha = buscar_ficha_medica_por_mascota(
                    $id_mascota
                );

                if (!$ficha) {
                    responderJson(
                        false,
                        'La mascota no tiene una ficha médica.',
                        null,
                        404
                    );
                    return;
                }

                responderJson(
                    true,
                    null,
                    $ficha,
                    200
                );

                return;
            }

            responderJson(
                false,
                'Debe indicar id o id_mascota.',
                null,
                400
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

            $ficha = registrar_ficha_medica($datos);

            responderJson(
                true,
                'Ficha médica creada correctamente.',
                $ficha,
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
            'Error al procesar la ficha médica.',
            null,
            500
        );
    }
}