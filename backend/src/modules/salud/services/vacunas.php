<?php

require_once __DIR__ . '/../repositories/vacunas.php';
require_once __DIR__ . '/../repositories/tipos_vacunas.php';
require_once __DIR__ . '/../repositories/fichas_medicas.php';

function listar_vacunas_por_ficha(int $id_ficha_medica): array{
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

    return obtener_vacunas_por_ficha($id_ficha_medica);
}


function registrar_vacuna(array $datos): array{
    if (
        empty($datos['id_ficha_medica']) ||
        empty($datos['id_tipo_vacuna']) ||
        empty($datos['numero_dosis']) ||
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

    $id_tipo_vacuna = filter_var(
        $datos['id_tipo_vacuna'],
        FILTER_VALIDATE_INT
    );

    $numero_dosis = filter_var(
        $datos['numero_dosis'],
        FILTER_VALIDATE_INT
    );

    if (
        $id_ficha_medica === false ||
        $id_ficha_medica <= 0 ||
        $id_tipo_vacuna === false ||
        $id_tipo_vacuna <= 0 ||
        $numero_dosis === false ||
        $numero_dosis <= 0
    ) {
        throw new InvalidArgumentException(
            'Los identificadores o número de dosis no son válidos.'
        );
    }

    $ficha = obtener_ficha_medica_por_id($id_ficha_medica);

    if (!$ficha) {
        throw new InvalidArgumentException(
            'La ficha médica indicada no existe.'
        );
    }

    if (!existe_tipo_vacuna($id_tipo_vacuna)) {
        throw new InvalidArgumentException(
            'El tipo de vacuna indicado no existe.'
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

    $vacuna_creada = crear_vacuna([
        'id_ficha_medica' => $id_ficha_medica,
        'id_tipo_vacuna' => $id_tipo_vacuna,
        'numero_dosis' => $numero_dosis,
        'fecha_aplicacion' => $fecha_aplicacion,
        'proxima_dosis' => $proxima_dosis,
        'observaciones' => $datos['observaciones'] ?? null
    ]);

    return $vacuna_creada;
}