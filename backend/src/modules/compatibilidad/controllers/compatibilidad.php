<?php

require_once __DIR__ . '/../services/compatibilidad.php';
require_once __DIR__ . '/../../../utils/response.php';
require_once __DIR__ . '/../../../middleware/autenticacion.php';


function manejar_compatibilidad(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        responderJson(false, 'Método no permitido.', null, 405);
        return;
    }

    if (!requerir_autenticacion()) {
        return;
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $rol_actual = (int) ($_SESSION['usuario_rol'] ?? 0);

    if ($rol_actual < 2) {
        responderJson(false, 'No tenés permisos para ver la compatibilidad.', null, 403);
        return;
    }

    $id_solicitud = filter_var($_GET['id_solicitud'] ?? null, FILTER_VALIDATE_INT);

    if ($id_solicitud === false || $id_solicitud <= 0) {
        responderJson(false, 'El id de la solicitud no es válido.', null, 400);
        return;
    }

    try {
        $resultado = calcular_compatibilidad_solicitud($id_solicitud);
        responderJson(true, null, $resultado, 200);

    } catch (InvalidArgumentException $e) {
        responderJson(false, $e->getMessage(), null, 400);
    } catch (RuntimeException $e) {
        responderJson(false, $e->getMessage(), null, 404);
    } catch (Throwable $e) {
        error_log('ERROR COMPATIBILIDAD: ' . $e->getMessage());
        responderJson(false, 'Error al calcular la compatibilidad.', null, 500);
    }
}