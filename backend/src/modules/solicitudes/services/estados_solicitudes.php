<?php

require_once __DIR__ . '/../repositories/estados_solicitudes.php';


function listar_estados_solicitudes(): array
{
    return obtener_estados_solicitudes();
}


function buscar_estado_solicitud_catalogo_por_id(
    int $id
): array|false{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del estado de solicitud no es válido.'
        );
    }

    return obtener_estado_solicitud_catalogo_por_id($id);
}


function registrar_estado_solicitud(
    array $datos
): array{
    $nombre_estado = trim(
        $datos['nombre_estado'] ?? ''
    );

    if ($nombre_estado === '') {
        throw new InvalidArgumentException(
            'El nombre del estado es obligatorio.'
        );
    }

    if (
        existe_estado_solicitud_por_nombre(
            $nombre_estado
        )
    ) {
        throw new InvalidArgumentException(
            'Ya existe un estado con ese nombre.'
        );
    }

    $estado = crear_estado_solicitud_catalogo(
        $nombre_estado
    );

    if (!$estado) {
        throw new RuntimeException(
            'No se pudo crear el estado de solicitud.'
        );
    }

    return $estado;
}


function modificar_estado_solicitud_catalogo(
    int $id,
    array $datos
): array{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del estado de solicitud no es válido.'
        );
    }

    $estado_actual =
        obtener_estado_solicitud_catalogo_por_id($id);

    if (!$estado_actual) {
        throw new RuntimeException(
            'Estado de solicitud no encontrado.'
        );
    }

    $nombre_estado = trim(
        $datos['nombre_estado'] ?? ''
    );

    if ($nombre_estado === '') {
        throw new InvalidArgumentException(
            'El nombre del estado es obligatorio.'
        );
    }

    if (
        existe_estado_solicitud_por_nombre(
            $nombre_estado,
            $id
        )
    ) {
        throw new InvalidArgumentException(
            'Ya existe otro estado con ese nombre.'
        );
    }

    $estado = actualizar_estado_solicitud_catalogo(
        $id,
        $nombre_estado
    );

    if (!$estado) {
        throw new RuntimeException(
            'No se pudo actualizar el estado de solicitud.'
        );
    }

    return $estado;
}


function eliminar_estado_solicitud_catalogo(
    int $id
): bool{
    if ($id <= 0) {
        throw new InvalidArgumentException(
            'El id del estado de solicitud no es válido.'
        );
    }

    $estado =
        obtener_estado_solicitud_catalogo_por_id($id);

    if (!$estado) {
        return false;
    }

    return desactivar_estado_solicitud_catalogo($id);
}