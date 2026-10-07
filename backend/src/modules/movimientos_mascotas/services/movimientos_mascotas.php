<?php

require_once __DIR__ . '/../repositories/movimientos_mascotas.php';
require_once __DIR__ . '/../../auditoria/services/auditorias.php';

function listar_movimientos_mascotas(): array{
    return obtener_movimientos_mascotas();
}


function listar_movimientos_por_mascota(int $id_mascota): array{
    if ($id_mascota <= 0) {
        throw new InvalidArgumentException(
            'El id de la mascota no es válido.'
        );
    }

    return obtener_movimientos_por_mascota($id_mascota);
}

function registrar_movimiento_mascota(array $datos): array{
    if (
        empty($datos['id_mascota']) ||
        empty($datos['id_tipo_movimiento'])
    ) {
        throw new InvalidArgumentException(
            'Faltan datos obligatorios.'
        );
    }

    $id_mascota = filter_var(
        $datos['id_mascota'],
        FILTER_VALIDATE_INT
    );

    $id_tipo_movimiento = filter_var(
        $datos['id_tipo_movimiento'],
        FILTER_VALIDATE_INT
    );


    if (
        $id_mascota === false ||
        $id_mascota <= 0 ||
        $id_tipo_movimiento === false ||
        $id_tipo_movimiento <= 0
    ) {
        throw new InvalidArgumentException(
            'Los identificadores enviados no son válidos.'
        );
    }

    if (!existe_mascota($id_mascota)) {
        throw new InvalidArgumentException(
            'La mascota indicada no existe.'
        );
    }

    if (!existe_tipo_movimiento_mascota($id_tipo_movimiento)) {
        throw new InvalidArgumentException(
            'El tipo de movimiento no es válido.'
        );
    }

    $id_solicitud = null;

    if (
        isset($datos['id_solicitud']) &&
        $datos['id_solicitud'] !== null
    ) {
        $id_solicitud = filter_var(
            $datos['id_solicitud'],
            FILTER_VALIDATE_INT
        );

        if ($id_solicitud === false || $id_solicitud <= 0) {
            throw new InvalidArgumentException(
                'El id de la solicitud no es válido.'
            );
        }

        if (!existe_solicitud($id_solicitud)) {
        throw new InvalidArgumentException(
            'La solicitud indicada no existe.'
        );
    }
    }

    $observaciones = null;

    if (isset($datos['observaciones'])) {
        $observaciones = trim($datos['observaciones']);

        if ($observaciones === '') {
            $observaciones = null;
        }
    }

    $movimiento_creado = crear_movimiento_mascota(
    $id_mascota,
    $id_tipo_movimiento,
    $id_solicitud,
    $observaciones
    );

    if (!$movimiento_creado) {
        throw new RuntimeException(
            'No se pudo registrar el movimiento de la mascota.'
        );
    }

    registrar_auditoria(
        'movimientos_mascotas',
        'INSERT',
        null,
        $movimiento_creado
    );

    return $movimiento_creado;
}