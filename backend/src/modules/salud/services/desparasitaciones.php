<?php

require_once __DIR__ . '/../repositories/desparasitaciones.php';
require_once __DIR__ . '/../repositories/tipos_productos.php';
require_once __DIR__ . '/../repositories/fichas_medicas.php';
require_once __DIR__ . '/../../auditoria/services/auditorias.php';

function listar_desparasitaciones_por_ficha(int $id_ficha_medica): array{
    if ($id_ficha_medica <= 0) {
        throw new InvalidArgumentException(
            'El id de la ficha médica no es válido.'
        );
    }

    $ficha = obtener_ficha_medica_por_id($id_ficha_medica);

    if (!$ficha) {
        throw new InvalidArgumentException(
            'La ficha médica indicada no existe.'
        );
    }

    return obtener_desparasitaciones_por_ficha($id_ficha_medica);
}


function registrar_desparasitacion(array $datos): array{
    if (
        empty($datos['id_ficha_medica']) ||
        empty($datos['id_tipo_producto']) ||
        empty($datos['tipo_parasito']) ||
        empty($datos['fecha_aplicacion'])
    ) {
        throw new InvalidArgumentException(
            'Faltan datos obligatorios.'
        );
    }

    $id_ficha_medica = filter_var(
        $datos['id_ficha_medica'],
        FILTER_VALIDATE_INT
    );

    $id_tipo_producto = filter_var(
        $datos['id_tipo_producto'],
        FILTER_VALIDATE_INT
    );

    if (
        $id_ficha_medica === false ||
        $id_ficha_medica <= 0 ||
        $id_tipo_producto === false ||
        $id_tipo_producto <= 0
    ) {
        throw new InvalidArgumentException(
            'Los identificadores enviados no son válidos.'
        );
    }

    $ficha = obtener_ficha_medica_por_id($id_ficha_medica);

    if (!$ficha) {
        throw new InvalidArgumentException(
            'La ficha médica indicada no existe.'
        );
    }

    if (!existe_tipo_producto($id_tipo_producto)) {
        throw new InvalidArgumentException(
            'El tipo de producto indicado no existe.'
        );
    }

    $tipos_parasito_validos = [
        'interno',
        'externo',
        'ambos'
    ];

    if (!in_array(
        $datos['tipo_parasito'],
        $tipos_parasito_validos,
        true
    )) {
        throw new InvalidArgumentException(
            'El tipo de parásito no es válido.'
        );
    }

    $fecha_aplicacion = $datos['fecha_aplicacion'];

    $fecha = DateTime::createFromFormat(
        'Y-m-d',
        $fecha_aplicacion
    );

    if (
        !$fecha ||
        $fecha->format('Y-m-d') !== $fecha_aplicacion
    ) {
        throw new InvalidArgumentException(
            'La fecha de aplicación no es válida.'
        );
    }

    if ($fecha_aplicacion > date('Y-m-d')) {
        throw new InvalidArgumentException(
            'La fecha de aplicación no puede ser futura.'
        );
    }

    $proxima_dosis = $datos['proxima_dosis'] ?? null;

    if ($proxima_dosis !== null && $proxima_dosis !== '') {

        $fecha_proxima = DateTime::createFromFormat(
            'Y-m-d',
            $proxima_dosis
        );

        if (
            !$fecha_proxima ||
            $fecha_proxima->format('Y-m-d') !== $proxima_dosis
        ) {
            throw new InvalidArgumentException(
                'La fecha de próxima dosis no es válida.'
            );
        }

        if ($proxima_dosis < $fecha_aplicacion) {
            throw new InvalidArgumentException(
                'La próxima dosis no puede ser anterior a la fecha de aplicación.'
            );
        }
    } else {
        $proxima_dosis = null;
    }

    $desparasitacion_creada = crear_desparasitacion([
        'id_ficha_medica' => $id_ficha_medica,
        'id_tipo_producto' => $id_tipo_producto,
        'tipo_parasito' => $datos['tipo_parasito'],
        'fecha_aplicacion' => $fecha_aplicacion,
        'proxima_dosis' => $proxima_dosis,
        'observaciones' => $datos['observaciones'] ?? null
    ]);

    registrar_auditoria(
        'desparasitaciones',
        'INSERT',
        null,
        $desparasitacion_creada
    );

    return $desparasitacion_creada;
}