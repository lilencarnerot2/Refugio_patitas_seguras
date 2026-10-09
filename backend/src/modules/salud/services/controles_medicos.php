<?php

require_once __DIR__ . '/../repositories/controles_medicos.php';
require_once __DIR__ . '/../repositories/fichas_medicas.php';

function listar_controles_por_ficha(int $id_ficha_medica): array{
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

    return obtener_controles_por_ficha($id_ficha_medica);
}


function registrar_control_medico(array $datos): array{
    if (
        empty($datos['id_ficha_medica']) ||
        empty($datos['fecha_control'])
    ) {
        throw new InvalidArgumentException(
            'Faltan datos obligatorios.'
        );
    }

    $id_ficha_medica = filter_var(
        $datos['id_ficha_medica'],
        FILTER_VALIDATE_INT
    );

    if ($id_ficha_medica === false || $id_ficha_medica <= 0) {
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

    $fecha_control = $datos['fecha_control'];

    $fecha = DateTime::createFromFormat('Y-m-d', $fecha_control);

    if (
        !$fecha ||
        $fecha->format('Y-m-d') !== $fecha_control
    ) {
        throw new InvalidArgumentException(
            'La fecha del control no es válida.'
        );
    }

    if ($fecha_control > date('Y-m-d')) {
        throw new InvalidArgumentException(
            'La fecha del control no puede ser futura.'
        );
    }

    if (
        isset($datos['peso']) &&
        $datos['peso'] !== null &&
        $datos['peso'] !== ''
    ) {
        if (!is_numeric($datos['peso']) || $datos['peso'] <= 0) {
            throw new InvalidArgumentException(
                'El peso debe ser un número mayor a cero.'
            );
        }
    }

    $control_creado = crear_control_medico([
        'id_ficha_medica' => $id_ficha_medica,
        'fecha_control' => $fecha_control,
        'peso' => $datos['peso'] ?? null,
        'observaciones' => $datos['observaciones'] ?? null
    ]);

    return $control_creado;
}