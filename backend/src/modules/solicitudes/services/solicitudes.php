<?php

require_once __DIR__ . '/../repositories/solicitudes.php';
require_once __DIR__ . '/../../mail/services/mail.php';
require_once __DIR__ . '/../../notificaciones_wsp/services/whatsapp.php';
require_once __DIR__ . '/../../usuarios/services/contactos.php';


function listar_solicitudes(int $id_usuario_actual = 0, bool $es_admin = false): array {
    if ($es_admin || $id_usuario_actual <= 0) {
        return obtener_solicitudes();
    }

    return obtener_solicitudes_por_usuario($id_usuario_actual);
}


function buscar_solicitud_por_id(int $id): array|false {
    if ($id <= 0) {
        return false;
    }

    return obtener_solicitud_por_id($id);
}


function cambiar_estado_solicitud(
    int $id_solicitud,
    int $id_estado_solicitud
 ): array {

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

    $estado_finalizado = obtener_estado_solicitud_por_nombre('Finalizado');
    $estado_aprobado = obtener_estado_solicitud_por_nombre('Aprobado');
    $estado_rechazado = obtener_estado_solicitud_por_nombre('Rechazado');

    if ($estado_finalizado && $id_estado_solicitud === (int) $estado_finalizado['id']) {
        // Si quiere pasar a Finalizado, el estado actual debe ser Aprobado o Rechazado
        $es_aprobado = $estado_aprobado && $estado_actual_id === (int) $estado_aprobado['id'];
        $es_rechazado = $estado_rechazado && $estado_actual_id === (int) $estado_rechazado['id'];

        if (!$es_aprobado && !$es_rechazado) {
            throw new InvalidArgumentException(
                'Solo se puede finalizar una solicitud que haya sido aprobada o rechazada previamente.'
            );
        }
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

    $nombreAdoptante =
        $solicitudCompleta['nombre_usuario'] ?? 'adoptante';

    $nombreMascota =
        $solicitudCompleta['nombre_mascota'] ?? 'la mascota';

    $estadoNuevo =
        $solicitudCompleta['estado_solicitud'] ?? 'actualizado';


    // Buscar el email del adoptante en el Padrón
    require_once __DIR__ . '/../../auth/services/integracion_registro.php';
    require_once __DIR__ . '/../../usuarios/services/usuarios.php';

    $usuarioLocal = buscar_usuario_por_id(
        (int) $solicitudCompleta['id_usuario']
    );


    // WHATSAPP
    $contactos = listar_contactos_usuario(
        (int) $solicitudCompleta['id_usuario']
    );

    $telefono_whatsapp = null;

    foreach ($contactos as $contacto) {

        if (
            isset($contacto['tipo_contacto']) &&
            $contacto['tipo_contacto'] === 'WhatsApp'
        ) {
            $telefono_whatsapp = $contacto['valor'];
            break;
        }
    }

    $estado_normalizado = strtolower(
        trim($estadoNuevo)
    );

    $tipo_notificacion = null;

    if ($estado_normalizado === 'aprobado') {
        $tipo_notificacion = 'solicitud_aprobada';
    }

    if ($estado_normalizado === 'rechazado') {
        $tipo_notificacion = 'solicitud_rechazada';
    }

    if (
        $tipo_notificacion !== null &&
        !empty($telefono_whatsapp)
    ) {

        try {

            enviar_notificacion_whatsapp(
                $telefono_whatsapp,
                $tipo_notificacion,
                $nombreAdoptante,
                $nombreMascota
            );

        } catch (Throwable $e) {

            error_log(
                'ERROR NOTIFICACION WHATSAPP: ' .
                $e->getMessage()
            );
        }
    }


    // MAIL
    if ($usuarioLocal && !empty($usuarioLocal['uid_pp2'])) {

        $emailAdoptante = buscarEmailEnPadron(
            (string) $usuarioLocal['uid_pp2']
        );

        if ($emailAdoptante) {

            enviarMail(
                $emailAdoptante,
                "Tu solicitud para $nombreMascota cambió de estado",
                "Hola $nombreAdoptante, tu solicitud para adoptar a $nombreMascota pasó al estado: $estadoNuevo.",
                null,
                'solicitud-' .
                    $id_solicitud .
                    '-estado-' .
                    $id_estado_solicitud
            );
        }
    }

    return $solicitudCompleta;
}


function registrar_solicitud(
    int $id_usuario,
    array $datos
): array {

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

    $solicitud_completa = obtener_solicitud_por_id(
        (int) $solicitud['id']
    );

    if (!$solicitud_completa) {
        throw new RuntimeException(
            'No se pudo recuperar la solicitud creada.'
        );
    }

    return $solicitud_completa;
}