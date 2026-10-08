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

    $estado_destino = obtener_estado_solicitud_por_id(
        $id_estado_solicitud
    );

    if (!$estado_destino) {
        throw new InvalidArgumentException(
            'El estado de solicitud no es válido.'
        );
    }

    $estado_actual_id = (int) $solicitud['id_estado_solicitud'];

    if ($estado_actual_id === $id_estado_solicitud) {
        throw new InvalidArgumentException(
            'La solicitud ya tiene ese estado.'
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

    // Recargamos la solicitud con TODOS los datos
    $solicitudCompleta = obtener_solicitud_por_id($id_solicitud);

    // Buscar el email del adoptante en el Padrón
    require_once __DIR__ . '/../../auth/services/integracion_registro.php';
    require_once __DIR__ . '/../../usuarios/services/usuarios.php';

    $usuarioLocal = buscar_usuario_por_id(
        (int) $solicitudCompleta['id_usuario']
    );

    if ($usuarioLocal && !empty($usuarioLocal['uid_pp2'])) {
        $emailAdoptante = buscarEmailEnPadron(
            (string) $usuarioLocal['uid_pp2']
        );

        if ($emailAdoptante) {
            $nombreAdoptante =
                $solicitudCompleta['nombre_usuario'] ?? 'adoptante';
            $nombreMascota =
                $solicitudCompleta['nombre_mascota'] ?? 'la mascota';
            $estadoNuevo =
                $solicitudCompleta['estado_solicitud'] ?? 'actualizado';

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

function registrar_solicitud(
    int $id_usuario,
    array $datos
): array
{
    if ($id_usuario <= 0) {
        throw new InvalidArgumentException(
            'El usuario autenticado no es válido.'
        );
    }

    $id_mascota = filter_var(
        $datos['id_mascota'] ?? null,
        FILTER_VALIDATE_INT
    );

    if ($id_mascota === false || $id_mascota <= 0) {
        throw new InvalidArgumentException(
            'La mascota seleccionada no es válida.'
        );
    }

    $id_tipo_solicitud = filter_var(
        $datos['id_tipo_solicitud'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (
        $id_tipo_solicitud === false ||
        $id_tipo_solicitud <= 0
    ) {
        throw new InvalidArgumentException(
            'El tipo de solicitud no es válido.'
        );
    }

    if (!existe_mascota_solicitud($id_mascota)) {
        throw new InvalidArgumentException(
            'La mascota seleccionada no existe.'
        );
    }

    if (!existe_tipo_solicitud($id_tipo_solicitud)) {
        throw new InvalidArgumentException(
            'El tipo de solicitud no existe o está deshabilitado.'
        );
    }

    $estado_pendiente =
        obtener_estado_solicitud_por_nombre(
            'Pendiente'
        );

    if (!$estado_pendiente) {
        throw new RuntimeException(
            'No se encuentra configurado el estado Pendiente.'
        );
    }

    $id_estado_pendiente =
        (int) $estado_pendiente['id'];

    if (
        existe_solicitud_pendiente(
            $id_usuario,
            $id_mascota,
            $id_tipo_solicitud,
            $id_estado_pendiente
        )
    ) {
        throw new InvalidArgumentException(
            'Ya tenés una solicitud pendiente de este tipo para esta mascota.'
        );
    }

    $observaciones = null;

    if (isset($datos['observaciones'])) {
        $observaciones = trim(
            (string) $datos['observaciones']
        );

        if ($observaciones === '') {
            $observaciones = null;
        }
    }

    $solicitud = crear_solicitud(
        $id_usuario,
        $id_mascota,
        $id_tipo_solicitud,
        $id_estado_pendiente,
        $observaciones
    );

    if (!$solicitud) {
        throw new RuntimeException(
            'No se pudo crear la solicitud.'
        );
    }

    return obtener_solicitud_por_id(
        (int) $solicitud['id']
    );
}