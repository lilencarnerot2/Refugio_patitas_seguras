<?php

require_once __DIR__ . '/../repositories/domicilios.php';


function registrar_domicilio_usuario(
    int $id_usuario,
    array $datos
): array {

    if ($id_usuario <= 0) {
        throw new InvalidArgumentException(
            'El usuario no es válido.'
        );
    }

    $id_tipo_vivienda =
        (int) ($datos['id_tipo_vivienda'] ?? 0);

    $id_localidad =
        (int) ($datos['id_localidad'] ?? 0);

    $nombre_barrio =
        trim($datos['nombre_barrio'] ?? '');

    if ($id_tipo_vivienda <= 0) {
        throw new InvalidArgumentException(
            'El tipo de vivienda es obligatorio.'
        );
    }

    if ($id_localidad <= 0) {
        throw new InvalidArgumentException(
            'La localidad es obligatoria.'
        );
    }

    if ($nombre_barrio === '') {
        throw new InvalidArgumentException(
            'El barrio o zona es obligatorio.'
        );
    }

    $datos['id_tipo_vivienda'] =
        $id_tipo_vivienda;

    $datos['id_localidad'] =
        $id_localidad;

    $datos['nombre_barrio'] =
        $nombre_barrio;

    return crear_domicilio_usuario(
        $id_usuario,
        $datos
    );
}

function buscar_domicilio_usuario(
    int $id_usuario
): array|false {

    if ($id_usuario <= 0) {
        throw new InvalidArgumentException(
            'El usuario no es válido.'
        );
    }

    return obtener_domicilio_por_usuario(
        $id_usuario
    );
}

function modificar_domicilio_usuario(
    int $id_usuario,
    array $datos
): array|false {

    if ($id_usuario <= 0) {
        throw new InvalidArgumentException(
            'El usuario no es válido.'
        );
    }

    $id_tipo_vivienda =
        (int) ($datos['id_tipo_vivienda'] ?? 0);

    $id_localidad =
        (int) ($datos['id_localidad'] ?? 0);

    $nombre_barrio =
        trim($datos['nombre_barrio'] ?? '');

    if ($id_tipo_vivienda <= 0) {
        throw new InvalidArgumentException(
            'El tipo de vivienda es obligatorio.'
        );
    }

    if ($id_localidad <= 0) {
        throw new InvalidArgumentException(
            'La localidad es obligatoria.'
        );
    }

    if ($nombre_barrio === '') {
        throw new InvalidArgumentException(
            'El barrio o zona es obligatorio.'
        );
    }

    $datos['id_tipo_vivienda'] =
        $id_tipo_vivienda;

    $datos['id_localidad'] =
        $id_localidad;

    $datos['nombre_barrio'] =
        $nombre_barrio;

    return actualizar_domicilio_usuario(
        $id_usuario,
        $datos
    );
}