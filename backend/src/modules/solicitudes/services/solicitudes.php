<?php

require_once __DIR__ . '/../repositories/solicitudes.php';

function listar_solicitudes(): array
{
    return obtener_solicitudes();
}


function buscar_solicitud_por_id(int $id): array|false
{
    if ($id <= 0) {
        return false;
    }

    return obtener_solicitud_por_id($id);
}

function cambiar_estado_solicitud(
    int $id_solicitud,
    int $id_estado_solicitud
): array
{
    if ($id_solicitud <= 0 || $id_estado_solicitud <= 0) {
        throw new InvalidArgumentException(
            'Los identificadores no son válidos.'
        );
    }

    $solicitud = obtener_solicitud_por_id($id_solicitud);

    if (!$solicitud) {
        throw new RuntimeException(
            'Solicitud no encontrada.'
        );
    }

    if (!existe_estado_solicitud($id_estado_solicitud)) {
        throw new InvalidArgumentException(
            'El estado de solicitud no es válido.'
        );
    }

    $solicitud_actualizada = actualizar_estado_solicitud(
        $id_solicitud,
        $id_estado_solicitud
    );

    if (!$solicitud_actualizada) {
        throw new RuntimeException(
            'No se pudo actualizar la solicitud.'
        );
    }

    return $solicitud_actualizada;
}