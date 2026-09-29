<?php

require_once __DIR__ . '/../repositories/solicitudes.php';
require_once __DIR__ . '/../../mail/services/mail.php';

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

        // Recargamos la solicitud con TODOS los datos (nombres, mascota, etc.)
    $solicitudCompleta = obtener_solicitud_por_id($id_solicitud);

    // Buscar el email del adoptante en el Padrón
    require_once __DIR__ . '/../../auth/services/integracion_registro.php';
    require_once __DIR__ . '/../../usuarios/services/usuarios.php';

    $usuarioLocal = buscar_usuario_por_id((int) $solicitudCompleta['id_usuario']);

    if ($usuarioLocal && !empty($usuarioLocal['uid_pp2'])) {
        $emailAdoptante = buscarEmailEnPadron((string) $usuarioLocal['uid_pp2']);

        if ($emailAdoptante) {
            $nombreAdoptante = $solicitudCompleta['nombre_usuario'] ?? 'adoptante';
            $nombreMascota   = $solicitudCompleta['nombre_mascota'] ?? 'la mascota';
            $estadoNuevo     = $solicitudCompleta['estado_solicitud'] ?? 'actualizado';

            enviarMail(
                $emailAdoptante,
                "Tu solicitud para $nombreMascota cambió de estado",
                "Hola $nombreAdoptante, tu solicitud para adoptar a $nombreMascota pasó al estado: $estadoNuevo.",
                null,
                'solicitud-' . $id_solicitud . '-estado-' . $id_estado_solicitud
            );
        }
    }

    return $solicitudCompleta;
}