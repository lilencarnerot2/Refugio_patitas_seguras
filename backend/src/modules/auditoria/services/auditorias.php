<?php

require_once __DIR__ . '/../repositories/auditorias.php';


function listar_auditorias(array $filtros = []): array {
    $filtros_limpios = [];

    if (isset($filtros['id_usuario']) && $filtros['id_usuario'] !== '') {
        $id_usuario = filter_var(
            $filtros['id_usuario'],
            FILTER_VALIDATE_INT
        );

        if ($id_usuario === false || $id_usuario <= 0) {
            throw new InvalidArgumentException(
                'El id del usuario no es válido.'
            );
        }

        $filtros_limpios['id_usuario'] = $id_usuario;
    }

    if (
        isset($filtros['nombre_tabla']) &&
        trim($filtros['nombre_tabla']) !== ''
    ) {
        $nombre_tabla = trim($filtros['nombre_tabla']);

        if (mb_strlen($nombre_tabla) > 50) {
            throw new InvalidArgumentException(
                'El nombre de la tabla no es válido.'
            );
        }

        $filtros_limpios['nombre_tabla'] = $nombre_tabla;
    }

    if (isset($filtros['accion']) && $filtros['accion'] !== '') {
        $accion = strtoupper(
            trim($filtros['accion'])
        );

        if (!in_array(
            $accion,
            ['INSERT', 'UPDATE', 'DELETE'],
            true
        )) {
            throw new InvalidArgumentException(
                'La acción de auditoría no es válida.'
            );
        }

        $filtros_limpios['accion'] = $accion;
    }

    if (
        isset($filtros['fecha_desde']) &&
        $filtros['fecha_desde'] !== ''
    ) {
        validar_fecha_auditoria(
            $filtros['fecha_desde'],
            'fecha_desde'
        );

        $filtros_limpios['fecha_desde'] =
            $filtros['fecha_desde'];
    }

    if (
        isset($filtros['fecha_hasta']) &&
        $filtros['fecha_hasta'] !== ''
    ) {
        validar_fecha_auditoria(
            $filtros['fecha_hasta'],
            'fecha_hasta'
        );

        $filtros_limpios['fecha_hasta'] =
            $filtros['fecha_hasta'];
    }

    if (
        isset($filtros_limpios['fecha_desde']) &&
        isset($filtros_limpios['fecha_hasta']) &&
        $filtros_limpios['fecha_desde'] >
        $filtros_limpios['fecha_hasta']
    ) {
        throw new InvalidArgumentException(
            'La fecha desde no puede ser posterior a la fecha hasta.'
        );
    }

    return obtener_auditorias(
        $filtros_limpios
    );
}


function buscar_auditoria_por_id(
    int $id
): array|false {

    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id de auditoría no es válido.'
        );
    }

    return obtener_auditoria_por_id($id);
}


function validar_fecha_auditoria(
    string $fecha,
    string $campo
): void {

    $objeto_fecha = DateTime::createFromFormat(
        'Y-m-d',
        $fecha
    );

    if (
        !$objeto_fecha ||
        $objeto_fecha->format('Y-m-d') !== $fecha
    ) {
        throw new InvalidArgumentException(
            "El campo $campo no contiene una fecha válida."
        );
    }
}