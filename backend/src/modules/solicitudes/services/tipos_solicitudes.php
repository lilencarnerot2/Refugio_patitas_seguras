<?php

require_once __DIR__ . '/../repositories/tipos_solicitudes.php';


function listar_tipos_solicitudes(): array
{
    return obtener_tipos_solicitudes();
}


function buscar_tipo_solicitud_por_id(
    int $id
): array|false
{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del tipo de solicitud no es válido.'
        );
    }

    return obtener_tipo_solicitud_por_id($id);
}


function registrar_tipo_solicitud(
    array $datos
): array
{
    $nombre_tipo = trim(
        $datos['nombre_tipo'] ?? ''
    );

    if ($nombre_tipo === '') {
        throw new InvalidArgumentException(
            'El nombre del tipo de solicitud es obligatorio.'
        );
    }

    if (mb_strlen($nombre_tipo) > 30) {
        throw new InvalidArgumentException(
            'El nombre del tipo de solicitud no puede superar los 30 caracteres.'
        );
    }

    if (
        existe_tipo_solicitud_por_nombre(
            $nombre_tipo
        )
    ) {
        throw new InvalidArgumentException(
            'Ya existe un tipo de solicitud con ese nombre.'
        );
    }

    $tipo = crear_tipo_solicitud(
        $nombre_tipo
    );

    if (!$tipo) {
        throw new RuntimeException(
            'No se pudo crear el tipo de solicitud.'
        );
    }

    return $tipo;
}


function modificar_tipo_solicitud(
    int $id,
    array $datos
): array
{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del tipo de solicitud no es válido.'
        );
    }

    $tipo_actual =
        obtener_tipo_solicitud_por_id($id);

    if (!$tipo_actual) {
        throw new RuntimeException(
            'Tipo de solicitud no encontrado.'
        );
    }

    $nombre_tipo = trim(
        $datos['nombre_tipo'] ?? ''
    );

    if ($nombre_tipo === '') {
        throw new InvalidArgumentException(
            'El nombre del tipo de solicitud es obligatorio.'
        );
    }

    if (mb_strlen($nombre_tipo) > 30) {
        throw new InvalidArgumentException(
            'El nombre del tipo de solicitud no puede superar los 30 caracteres.'
        );
    }

    if (
        existe_tipo_solicitud_por_nombre(
            $nombre_tipo,
            $id
        )
    ) {
        throw new InvalidArgumentException(
            'Ya existe otro tipo de solicitud con ese nombre.'
        );
    }

    $tipo = actualizar_tipo_solicitud(
        $id,
        $nombre_tipo
    );

    if (!$tipo) {
        throw new RuntimeException(
            'No se pudo actualizar el tipo de solicitud.'
        );
    }

    return $tipo;
}


function eliminar_tipo_solicitud(
    int $id
): bool
{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del tipo de solicitud no es válido.'
        );
    }

    $tipo = obtener_tipo_solicitud_por_id($id);

    if (!$tipo) {
        return false;
    }

    return desactivar_tipo_solicitud($id);
}