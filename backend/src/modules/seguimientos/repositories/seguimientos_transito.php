<?php

require_once __DIR__ . '/../../../config/database.php';


function crear_seguimiento_transito(
    int $id_solicitud,
    string $fecha_programada,
    int $id_tipo_seguimiento,
    ?string $observaciones = null
): int {

    $conexion = conectarDB();

    $sql = "
        INSERT INTO seguimientos_transito (
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


function obtener_seguimiento_transito_por_id(int $id): ?array {
    $conexion = conectarDB();

    $sql = "
        SELECT
            st.id,
            st.id_solicitud,
            st.fecha_programada,
            st.fecha_realizada,
            st.id_tipo_seguimiento,
            ts.descripcion AS tipo_seguimiento,
            st.observaciones,
            st.estado,

            CASE
                WHEN st.estado = FALSE THEN 'realizado'
                WHEN st.fecha_programada < CURRENT_DATE THEN 'vencido'
                ELSE 'pendiente'
            END AS situacion

        FROM seguimientos_transito st

        JOIN tipos_seguimientos ts
            ON ts.id = st.id_tipo_seguimiento

        WHERE st.id = :id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    $seguimiento = $consulta->fetch(PDO::FETCH_ASSOC);

    return $seguimiento ?: null;
}


function obtener_seguimientos_transito(array $filtros = []): array {
    $conexion = conectarDB();

    $sql = "
        SELECT
            st.id,
            st.id_solicitud,
            st.fecha_programada,
            st.fecha_realizada,
            st.id_tipo_seguimiento,
            ts.descripcion AS tipo_seguimiento,
            st.observaciones,
            st.estado,

            s.id_usuario,
            s.id_mascota,

            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,

            m.nombre AS nombre_mascota,

            CASE
                WHEN st.estado = FALSE THEN 'realizado'
                WHEN st.fecha_programada < CURRENT_DATE THEN 'vencido'
                ELSE 'pendiente'
            END AS situacion

        FROM seguimientos_transito st

        JOIN tipos_seguimientos ts
            ON ts.id = st.id_tipo_seguimiento

        JOIN solicitudes s
            ON s.id = st.id_solicitud

        JOIN usuarios u
            ON u.id = s.id_usuario

        JOIN mascotas m
            ON m.id = s.id_mascota

        WHERE 1 = 1
    ";

    $parametros = [];


    if (isset($filtros['estado'])) {

        $sql .= " AND st.estado = :estado";

        $parametros[':estado'] = $filtros['estado'];
    }


    if (!empty($filtros['id_tipo_seguimiento'])) {

        $sql .= "
            AND st.id_tipo_seguimiento = :id_tipo_seguimiento
        ";

        $parametros[':id_tipo_seguimiento'] =
            $filtros['id_tipo_seguimiento'];
    }


    if (!empty($filtros['fecha_desde'])) {

        $sql .= "
            AND st.fecha_programada >= :fecha_desde
        ";

        $parametros[':fecha_desde'] =
            $filtros['fecha_desde'];
    }


    if (!empty($filtros['fecha_hasta'])) {

        $sql .= "
            AND st.fecha_programada <= :fecha_hasta
        ";

        $parametros[':fecha_hasta'] =
            $filtros['fecha_hasta'];
    }


    $sql .= "
        ORDER BY
            st.fecha_programada ASC,
            st.id ASC
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute($parametros);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function marcar_seguimiento_transito_realizado(
    int $id,
    string $fecha_realizada,
    ?string $observaciones
): bool {

    $conexion = conectarDB();

    $sql = "
        UPDATE seguimientos_transito
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


function reprogramar_seguimiento_transito(
    int $id,
    string $fecha_programada
): bool {

    $conexion = conectarDB();

    $sql = "
        UPDATE seguimientos_transito
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


function contar_seguimientos_transito_por_solicitud(
    int $id_solicitud
): int {

    $conexion = conectarDB();

    $sql = "
        SELECT COUNT(*)
        FROM seguimientos_transito
        WHERE id_solicitud = :id_solicitud
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_solicitud' => $id_solicitud
    ]);

    return (int) $consulta->fetchColumn();
}


function obtener_ultimo_seguimiento_transito_por_solicitud(
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
        FROM seguimientos_transito
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