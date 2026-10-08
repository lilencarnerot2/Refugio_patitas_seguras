<?php

require_once __DIR__ . '/../repositories/fichas_medicas.php';

function buscar_ficha_medica_por_mascota(int $id_mascota): array|false{
    if ($id_mascota <= 0) {
        throw new InvalidArgumentException(
            'El id de la mascota no es válido.'
        );
    }

    return obtener_ficha_medica_por_mascota($id_mascota);
}


function buscar_ficha_medica_por_id(int $id): array|false{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id de la ficha médica no es válido.'
        );
    }

    return obtener_ficha_medica_por_id($id);
}

function registrar_ficha_medica(array $datos): array{
    if (empty($datos['id_mascota'])) {
        throw new InvalidArgumentException(
            'Debe indicar el id de la mascota.'
        );
    }

    $id_mascota = filter_var(
        $datos['id_mascota'],
        FILTER_VALIDATE_INT
    );

    if ($id_mascota === false || $id_mascota <= 0) {
        throw new InvalidArgumentException(
            'El id de la mascota no es válido.'
        );
    }

    if (existe_ficha_medica_para_mascota($id_mascota)) {
        throw new InvalidArgumentException(
            'La mascota ya tiene una ficha médica.'
        );
    }

    $ficha_creada = crear_ficha_medica([
        'id_mascota' => $id_mascota,
        'observaciones' => $datos['observaciones'] ?? null,
        'fecha_actualizacion' => date('Y-m-d')
    ]);

    return $ficha_creada;
}