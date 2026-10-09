<?php

require_once __DIR__ . '/../../../config/database.php';

/**
 * Crea un nuevo seguimiento de adopción.
 *
 * @param int $id_solicitud El ID de la solicitud de adopción.
 * @param string $fecha_programada La fecha programada para el seguimiento (formato 'YYYY-MM-DD').
 * @param int $id_tipo_seguimiento El ID del tipo de seguimiento.
 * @param string|null $observaciones Observaciones adicionales (opcional).
 *
 * @return int El ID del seguimiento de adopción creado.
 * 
 * crear seguimiento
 * listar
 * consultar por ID
 * filtrar por estado
 * filtrar por tipo
 * filtrar por fechas
 * marcar realizado
 * reprogramar
 * mostrar mascota
 * mostrar responsable
 * calcular pendiente/vencido/realizado
 */

function crear_seguimiento_adopcion(
    int $id_solicitud,
    string $fecha_programada,
    int $id_tipo_seguimiento,
    ?string $observaciones = null
): int {

    $conexion = conectarDB();

    $sql = "
        INSERT INTO seguimientos_adopcion (
            id_solicitud,
            fecha_programada,
            fecha_realizada,
            id_tipo_seguimiento,
            observaciones,
            estado
        )
        VALUES (
            :id_solicitud,
            :fecha_programada,
            NULL,
            :id_tipo_seguimiento,
            :observaciones,
            TRUE
        )
        RETURNING id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_solicitud' => $id_solicitud,
        ':fecha_programada' => $fecha_programada,
        ':id_tipo_seguimiento' => $id_tipo_seguimiento,
        ':observaciones' => $observaciones
    ]);

    return (int) $consulta->fetchColumn();
}


function obtener_seguimiento_adopcion_por_id(int $id): ?array {
    $conexion = conectarDB();

    $sql = "
        SELECT
            sa.id,
            sa.id_solicitud,
            sa.fecha_programada,
            sa.fecha_realizada,
            sa.id_tipo_seguimiento,
            ts.descripcion AS tipo_seguimiento,
            sa.observaciones,
            sa.estado,

            CASE
                WHEN sa.estado = FALSE THEN 'realizado'
                WHEN sa.fecha_programada < CURRENT_DATE THEN 'vencido'
                ELSE 'pendiente'
            END AS situacion

        FROM seguimientos_adopcion sa

        JOIN tipos_seguimientos ts
            ON ts.id = sa.id_tipo_seguimiento

        WHERE sa.id = :id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    $seguimiento = $consulta->fetch(PDO::FETCH_ASSOC);

    return $seguimiento ?: null;
}


function obtener_seguimientos_adopcion(array $filtros = []): array {
    $conexion = conectarDB();

    $sql = "
        SELECT
            sa.id,
            sa.id_solicitud,
            sa.fecha_programada,
            sa.fecha_realizada,
            sa.id_tipo_seguimiento,
            ts.descripcion AS tipo_seguimiento,
            sa.observaciones,
            sa.estado,

            s.id_usuario,
            s.id_mascota,

            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,

            m.nombre AS nombre_mascota,

            CASE
                WHEN sa.estado = FALSE THEN 'realizado'
                WHEN sa.fecha_programada < CURRENT_DATE THEN 'vencido'
                ELSE 'pendiente'
            END AS situacion

        FROM seguimientos_adopcion sa

        JOIN tipos_seguimientos ts
            ON ts.id = sa.id_tipo_seguimiento

        JOIN solicitudes s
            ON s.id = sa.id_solicitud

        JOIN usuarios u
            ON u.id = s.id_usuario

        JOIN mascotas m
            ON m.id = s.id_mascota

        WHERE 1 = 1
    ";

    $parametros = [];


    if (isset($filtros['estado'])) {

        $sql .= " AND sa.estado = :estado";

        $parametros[':estado'] = $filtros['estado'];
    }


    if (!empty($filtros['id_tipo_seguimiento'])) {

        $sql .= "
            AND sa.id_tipo_seguimiento = :id_tipo_seguimiento
        ";

        $parametros[':id_tipo_seguimiento'] =
            $filtros['id_tipo_seguimiento'];
    }


    if (!empty($filtros['fecha_desde'])) {

        $sql .= "
            AND sa.fecha_programada >= :fecha_desde
        ";

        $parametros[':fecha_desde'] =
            $filtros['fecha_desde'];
    }


    if (!empty($filtros['fecha_hasta'])) {

        $sql .= "
            AND sa.fecha_programada <= :fecha_hasta
        ";

        $parametros[':fecha_hasta'] =
            $filtros['fecha_hasta'];
    }


    $sql .= "
        ORDER BY
            sa.fecha_programada ASC,
            sa.id ASC
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute($parametros);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function marcar_seguimiento_adopcion_realizado(
    int $id,
    string $fecha_realizada,
    ?string $observaciones
): bool {

    $conexion = conectarDB();

    $sql = "
        UPDATE seguimientos_adopcion
        SET
            fecha_realizada = :fecha_realizada,
            observaciones = :observaciones,
            estado = FALSE
        WHERE id = :id
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':fecha_realizada' => $fecha_realizada,
        ':observaciones' => $observaciones,
        ':id' => $id
    ]);

    return $consulta->rowCount() > 0;
}


function reprogramar_seguimiento_adopcion(
    int $id,
    string $fecha_programada
): bool {

    $conexion = conectarDB();

    $sql = "
        UPDATE seguimientos_adopcion
        SET fecha_programada = :fecha_programada
        WHERE id = :id
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':fecha_programada' => $fecha_programada,
        ':id' => $id
    ]);

    return $consulta->rowCount() > 0;
}

function contar_seguimientos_adopcion_por_solicitud(
    int $id_solicitud
): int {

    $conexion = conectarDB();

    $sql = "
        SELECT COUNT(*)
        FROM seguimientos_adopcion
        WHERE id_solicitud = :id_solicitud
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_solicitud' => $id_solicitud
    ]);

    return (int) $consulta->fetchColumn();
}


function obtener_ultimo_seguimiento_adopcion_por_solicitud(
    int $id_solicitud
): ?array {

    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_solicitud,
            fecha_programada,
            fecha_realizada,
            id_tipo_seguimiento,
            observaciones,
            estado
        FROM seguimientos_adopcion
        WHERE id_solicitud = :id_solicitud
        ORDER BY fecha_programada DESC, id DESC
        LIMIT 1
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_solicitud' => $id_solicitud
    ]);

    $seguimiento = $consulta->fetch(PDO::FETCH_ASSOC);

    return $seguimiento ?: null;
}