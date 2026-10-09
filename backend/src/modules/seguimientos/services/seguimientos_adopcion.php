<?php

require_once __DIR__ . '/../repositories/seguimientos_adopcion.php';

/**
 * Lista los seguimientos de adopción según los filtros proporcionados.
 *
 * @param array $filtros Los filtros para la consulta.
 *
 * @return array La lista de seguimientos de adopción.
 * 
 * Qué hace este service
 * validación de IDs;
 * validación de fechas;
 * filtros;
 * creación manual de seguimiento;
 * consulta por ID;
 * marcarlo como realizado;
 * guardar fecha_realizada;
 * observaciones;
 * reprogramación;
 * impedir reprogramar un seguimiento ya finalizado.
 */

function listar_seguimientos_adopcion(array $filtros = []): array {
    if (isset($filtros['estado'])) {
        if ($filtros['estado'] === 'true') {
            $filtros['estado'] = true;
        } elseif ($filtros['estado'] === 'false') {
            $filtros['estado'] = false;
        } else {
            throw new Exception(
                'El estado debe ser true o false.'
            );
        }
    }

    if (!empty($filtros['fecha_desde'])) {
        validar_fecha_seguimiento($filtros['fecha_desde']);
    }

    if (!empty($filtros['fecha_hasta'])) {
        validar_fecha_seguimiento($filtros['fecha_hasta']);
    }

    if (
        !empty($filtros['fecha_desde']) &&
        !empty($filtros['fecha_hasta']) &&
        $filtros['fecha_desde'] > $filtros['fecha_hasta']
    ) {
        throw new Exception(
            'La fecha desde no puede ser mayor que la fecha hasta.'
        );
    }

    return obtener_seguimientos_adopcion($filtros);
}


function buscar_seguimiento_adopcion_por_id(int $id): array {
    if ($id <= 0) {
        throw new Exception(
            'El id del seguimiento no es válido.'
        );
    }

    $seguimiento = obtener_seguimiento_adopcion_por_id($id);

    if (!$seguimiento) {
        throw new Exception(
            'Seguimiento de adopción no encontrado.'
        );
    }

    return $seguimiento;
}


function registrar_seguimiento_adopcion(array $datos): int {
    if (empty($datos['id_solicitud'])) {
        throw new Exception(
            'El campo id_solicitud es obligatorio.'
        );
    }

    if (empty($datos['fecha_programada'])) {
        throw new Exception(
            'El campo fecha_programada es obligatorio.'
        );
    }

    if (empty($datos['id_tipo_seguimiento'])) {
        throw new Exception(
            'El campo id_tipo_seguimiento es obligatorio.'
        );
    }

    validar_fecha_seguimiento(
        $datos['fecha_programada']
    );

    $observaciones = $datos['observaciones'] ?? null;

    return crear_seguimiento_adopcion(
        (int) $datos['id_solicitud'],
        $datos['fecha_programada'],
        (int) $datos['id_tipo_seguimiento'],
        $observaciones
    );
}


function realizar_seguimiento_adopcion(
    int $id,
    array $datos
): bool {

    if ($id <= 0) {
        throw new Exception(
            'El id del seguimiento no es válido.'
        );
    }

    $seguimiento = buscar_seguimiento_adopcion_por_id($id);

    if (!seguimiento_esta_activo($seguimiento['estado'])) {
        throw new Exception(
            'El seguimiento ya fue realizado.'
        );
    }

    $fecha_realizada =
        $datos['fecha_realizada'] ?? date('Y-m-d');

    validar_fecha_seguimiento($fecha_realizada);

    $observaciones =
        $datos['observaciones'] ?? null;

    $resultado =
        marcar_seguimiento_adopcion_realizado(
            $id,
            $fecha_realizada,
            $observaciones
        );

    if (!$resultado) {
        throw new Exception(
            'No se pudo registrar el seguimiento.'
        );
    }

    generar_proximo_seguimiento_adopcion(
        (int) $seguimiento['id_solicitud'],
        (int) $seguimiento['id_tipo_seguimiento']
    );

    return true;
}


function reprogramar_seguimiento_adopcion_service(
    int $id,
    array $datos
): bool {

    if ($id <= 0) {
        throw new Exception(
            'El id del seguimiento no es válido.'
        );
    }

    if (empty($datos['fecha_programada'])) {
        throw new Exception(
            'La nueva fecha programada es obligatoria.'
        );
    }

    validar_fecha_seguimiento(
        $datos['fecha_programada']
    );

    $seguimiento = buscar_seguimiento_adopcion_por_id($id);

    if (!seguimiento_esta_activo($seguimiento['estado'])) {
        throw new Exception(
            'No se puede reprogramar un seguimiento realizado.'
        );
    }

    return reprogramar_seguimiento_adopcion(
        $id,
        $datos['fecha_programada']
    );
}


function validar_fecha_seguimiento(string $fecha): void {
    $fecha_objeto = DateTime::createFromFormat(
        'Y-m-d',
        $fecha
    );

    if (
        !$fecha_objeto ||
        $fecha_objeto->format('Y-m-d') !== $fecha
    ) {
        throw new Exception(
            'La fecha debe tener formato YYYY-MM-DD.'
        );
    }
}

function seguimiento_esta_activo($estado): bool {
    return filter_var(
        $estado,
        FILTER_VALIDATE_BOOLEAN
    );
}

function generar_proximo_seguimiento_adopcion(
    int $id_solicitud,
    int $id_tipo_seguimiento
): int {

    if ($id_solicitud <= 0) {
        throw new Exception(
            'El id de la solicitud no es válido.'
        );
    }

    if ($id_tipo_seguimiento <= 0) {
        throw new Exception(
            'El tipo de seguimiento no es válido.'
        );
    }

    $cantidad_seguimientos =
        contar_seguimientos_adopcion_por_solicitud(
            $id_solicitud
        );

    $ultimo_seguimiento =
        obtener_ultimo_seguimiento_adopcion_por_solicitud(
            $id_solicitud
        );

    if (!$ultimo_seguimiento) {
        throw new Exception(
            'No existe un seguimiento previo para calcular el siguiente.'
        );
    }

    $fecha_base = new DateTime(
        $ultimo_seguimiento['fecha_programada']
    );

    if ($cantidad_seguimientos === 1) {

        // Segundo seguimiento:
        // 6 meses después del primero.
        $fecha_base->modify('+6 months');

    } else {

        // Desde el tercer seguimiento:
        // uno por año.
        $fecha_base->modify('+1 year');
    }

    $fecha_programada =
        $fecha_base->format('Y-m-d');

    return crear_seguimiento_adopcion(
        $id_solicitud,
        $fecha_programada,
        $id_tipo_seguimiento,
        'Seguimiento generado automáticamente'
    );
}