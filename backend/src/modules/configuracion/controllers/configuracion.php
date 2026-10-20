<?php

require_once __DIR__ . '/../services/configuracion.php';
require_once __DIR__ . '/../../../utils/response.php';


function manejarConfiguracion(): void {
    $metodo = $_SERVER['REQUEST_METHOD'];
    try {
        if ($metodo === 'GET') {
            responderJson(
                true,
                null,
                consultarConfiguracion(),
                200
            );

            return;
        }
        if ($metodo === 'PUT') {
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
            responderJson(
                true,
                'Configuración actualizada correctamente.',
                modificarConfiguracion($datos),
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
    } catch (RuntimeException $e) {
        responderJson(
            false,
            $e->getMessage(),
            null,
            404
        );
    } catch (Throwable $e) {
        error_log(
            'ERROR CONFIGURACION: ' .
            $e->getMessage()
        );
        responderJson(
            false,
            'Error al procesar la configuración.',
            null,
            500
        );
    }
}