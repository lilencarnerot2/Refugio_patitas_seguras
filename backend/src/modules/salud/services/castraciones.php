<?php

require_once __DIR__ . '/../repositories/castraciones.php';
require_once __DIR__ . '/../repositories/fichas_medicas.php';

function buscar_castracion_por_ficha(int $id_ficha_medica): array|false
{
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

    return obtener_castracion_por_ficha($id_ficha_medica);
}


function registrar_castracion(array $datos): array
{
    if (empty($datos['id_ficha_medica'])) {
        throw new InvalidArgumentException(
            'Debe indicar el id de la ficha médica.'
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

    if (existe_castracion_para_ficha($id_ficha_medica)) {
        throw new InvalidArgumentException(
            'La ficha médica ya tiene una castración registrada.'
        );
    }

    $fecha_castracion = $datos['fecha_castracion'] ?? null;

    if ($fecha_castracion !== null && $fecha_castracion !== '') {

        $fecha = DateTime::createFromFormat(
            'Y-m-d',
            $fecha_castracion
        );

        if (
            !$fecha ||
            $fecha->format('Y-m-d') !== $fecha_castracion
        ) {
            throw new InvalidArgumentException(
                'La fecha de castración no es válida.'
            );
        }

        if ($fecha_castracion > date('Y-m-d')) {
            throw new InvalidArgumentException(
                'La fecha de castración no puede ser futura.'
            );
        }

    } else {
        $fecha_castracion = null;
    }

    return crear_castracion([
        'id_ficha_medica' => $id_ficha_medica,
        'fecha_castracion' => $fecha_castracion,
        'observaciones' => $datos['observaciones'] ?? null
    ]);
}