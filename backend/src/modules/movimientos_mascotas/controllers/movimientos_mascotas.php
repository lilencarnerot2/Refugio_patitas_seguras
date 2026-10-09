<?php

require_once __DIR__ . '/../services/movimientos_mascotas.php';
require_once __DIR__ . '/../../../utils/response.php';

function manejar_movimientos_mascotas(): void
{
    $metodo = $_SERVER['REQUEST_METHOD'];

    try {

        // GET
        if ($metodo === 'GET') {

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

                $movimientos = listar_movimientos_por_mascota(
                    $id_mascota
                );

                responderJson(
                    true,
                    null,
                    $movimientos,
                    200
                );

                return;
            }

            responderJson(
                true,
                null,
                listar_movimientos_mascotas(),
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

            $movimiento = registrar_movimiento_mascota(
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

    } catch (Throwable $e) {

        responderJson(
            false,
            'Error al procesar el movimiento de la mascota.',
            null,
            500
        );
    }
}