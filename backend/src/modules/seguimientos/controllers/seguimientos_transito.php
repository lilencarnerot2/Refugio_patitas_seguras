<?php

require_once __DIR__ . '/../services/seguimientos_transito.php';


function manejar_seguimientos_transito(): void {
    $metodo = $_SERVER['REQUEST_METHOD'];

    try {
        switch ($metodo) {

            case 'GET':

                if (isset($_GET['id'])) {
                    $id = (int) $_GET['id'];

                    $seguimiento =
                        buscar_seguimiento_transito_por_id($id);

                    responderJson(
                        true,
                        'Seguimiento obtenido correctamente.',
                        $seguimiento,
                        200
                    );

                    break;
                }

                $filtros = [];

                if (isset($_GET['estado'])) {
                    $filtros['estado'] = $_GET['estado'];
                }

                if (isset($_GET['id_tipo_seguimiento'])) {
                    $filtros['id_tipo_seguimiento'] =
                        (int) $_GET['id_tipo_seguimiento'];
                }

                if (isset($_GET['fecha_desde'])) {
                    $filtros['fecha_desde'] =
                        $_GET['fecha_desde'];
                }

                if (isset($_GET['fecha_hasta'])) {
                    $filtros['fecha_hasta'] =
                        $_GET['fecha_hasta'];
                }

                $seguimientos =
                    listar_seguimientos_transito($filtros);

                responderJson(
                    true,
                    'Seguimientos obtenidos correctamente.',
                    $seguimientos,
                    200
                );

                break;


            case 'POST':

                $datos = json_decode(
                    file_get_contents('php://input'),
                    true
                );

                if (!is_array($datos)) {
                    throw new Exception(
                        'El cuerpo de la solicitud no es válido.'
                    );
                }

                $id =
                    registrar_seguimiento_transito($datos);

                responderJson(
                    true,
                    'Seguimiento creado correctamente.',
                    [
                        'id' => $id
                    ],
                    201
                );

                break;


            case 'PUT':

                if (!isset($_GET['id'])) {
                    throw new Exception(
                        'El id del seguimiento es obligatorio.'
                    );
                }

                $id = (int) $_GET['id'];

                $datos = json_decode(
                    file_get_contents('php://input'),
                    true
                );

                if (!is_array($datos)) {
                    throw new Exception(
                        'El cuerpo de la solicitud no es válido.'
                    );
                }

                if (!isset($datos['accion'])) {
                    throw new Exception(
                        'La acción es obligatoria.'
                    );
                }

                if ($datos['accion'] === 'realizar') {

                    realizar_seguimiento_transito(
                        $id,
                        $datos
                    );

                    responderJson(
                        true,
                        'Seguimiento registrado como realizado.',
                        null,
                        200
                    );

                    break;
                }

                if ($datos['accion'] === 'reprogramar') {

                    reprogramar_seguimiento_transito_service(
                        $id,
                        $datos
                    );

                    responderJson(
                        true,
                        'Seguimiento reprogramado correctamente.',
                        null,
                        200
                    );

                    break;
                }

                throw new Exception(
                    'Acción no válida.'
                );


            default:

                responderJson(
                    false,
                    'Método no permitido.',
                    null,
                    405
                );

                break;
        }

    } catch (Exception $e) {

        responderJson(
            false,
            $e->getMessage(),
            null,
            400
        );
    }
}