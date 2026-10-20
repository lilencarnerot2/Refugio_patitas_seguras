<?php

require_once __DIR__ . '/../services/auditorias.php';

function manejar_auditorias(): void
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
                    'El id de auditoría no es válido.',
                    null,
                    400
                );
                return;
            }

            $auditoria = buscar_auditoria_por_id(
                $id
            );

            if (!$auditoria) {
                responderJson(
                    false,
                    'Auditoría no encontrada.',
                    null,
                    404
                );
                return;
            }

            responderJson(
                true,
                'Auditoría encontrada.',
                $auditoria,
                200
            );

            return;
        }

        $filtros = [
            'id_usuario' =>
                $_GET['id_usuario'] ?? null,

            'nombre_tabla' =>
                $_GET['nombre_tabla'] ?? null,

            'accion' =>
                $_GET['accion'] ?? null,

            'fecha_desde' =>
                $_GET['fecha_desde'] ?? null,

            'fecha_hasta' =>
                $_GET['fecha_hasta'] ?? null
        ];

        $auditorias = listar_auditorias(
            $filtros
        );

        responderJson(
            true,
            'Auditorías obtenidas correctamente.',
            $auditorias,
            200
        );

    } catch (InvalidArgumentException $e) {

        responderJson(
            false,
            $e->getMessage(),
            null,
            400
        );

    } catch (PDOException $e) {

        error_log(
            'ERROR AUDITORIAS DB: ' .
            $e->getMessage()
        );

        responderJson(
            false,
            'Error interno.',
            null,
            500
        );

    } catch (Throwable $e) {

        error_log(
            'ERROR AUDITORIAS: ' .
            $e->getMessage()
        );

        responderJson(
            false,
            'Error interno.',
            null,
            500
        );
    }
}