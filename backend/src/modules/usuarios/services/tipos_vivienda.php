<?php

require_once __DIR__ . '/../repositories/tipos_vivienda.php';

function listar_tipos_vivienda(): array {
    return obtener_tipos_vivienda();
}


function registrar_tipo_vivienda(array $datos): array{
    $nombre = trim(
        $datos['nombre_tipo'] ?? ''
    );
    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre del tipo de vivienda es obligatorio.'
        );
    }
    if (existe_tipo_vivienda_por_nombre($nombre)) {
        throw new InvalidArgumentException(
            'Ya existe un tipo de vivienda con ese nombre.'
        );
    }
    $tipo = crear_tipo_vivienda($nombre);
    if (!$tipo) {
        throw new RuntimeException(
            'No se pudo crear el tipo de vivienda.'
        );
    }
    return $tipo;
}


function modificar_tipo_vivienda(
    int $id,
    array $datos
): array {
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El ID del tipo de vivienda no es válido.'
        );
    }
    if (!existe_tipo_vivienda($id)) {
        throw new InvalidArgumentException(
            'El tipo de vivienda indicado no existe.'
        );
    }
    $nombre = trim(
        $datos['nombre_tipo'] ?? ''
    );
    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre del tipo de vivienda es obligatorio.'
        );
    }
    if (
        existe_tipo_vivienda_por_nombre(
            $nombre,
            $id
        )
    ) {
        throw new InvalidArgumentException(
            'Ya existe un tipo de vivienda con ese nombre.'
        );
    }
    $tipo = actualizar_tipo_vivienda(
        $id,
        $nombre
    );

    if (!$tipo) {
        throw new RuntimeException(
            'No se pudo actualizar el tipo de vivienda.'
        );
    }

    return $tipo;
}


function dar_baja_tipo_vivienda(int $id): array {
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El ID del tipo de vivienda no es válido.'
        );
    }
    if (!existe_tipo_vivienda($id)) {
        throw new InvalidArgumentException(
            'El tipo de vivienda indicado no existe.'
        );
    }
    $tipo = eliminar_tipo_vivienda($id);
    if (!$tipo) {
        throw new RuntimeException(
            'No se pudo eliminar el tipo de vivienda.'
        );
    }
    return $tipo;
}