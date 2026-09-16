<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_solicitudes(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            s.id,
            s.id_usuario,
            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            s.id_mascota,
            m.nombre AS nombre_mascota,
            s.id_tipo_solicitud,
            ts.nombre_tipo AS tipo_solicitud,
            s.id_estado_solicitud,
            es.nombre_estado AS estado_solicitud,
            s.fecha_solicitud
        FROM solicitudes s
        INNER JOIN usuarios u
            ON u.id = s.id_usuario
        INNER JOIN mascotas m
            ON m.id = s.id_mascota
        INNER JOIN tipos_solicitudes ts
            ON ts.id = s.id_tipo_solicitud
        INNER JOIN estados_solicitudes es
            ON es.id = s.id_estado_solicitud
        ORDER BY s.fecha_solicitud DESC, s.id DESC
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_solicitud_por_id(int $id): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            s.id,
            s.id_usuario,
            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            s.id_mascota,
            m.nombre AS nombre_mascota,
            s.id_tipo_solicitud,
            ts.nombre_tipo AS tipo_solicitud,
            s.id_estado_solicitud,
            es.nombre_estado AS estado_solicitud,
            s.fecha_solicitud
        FROM solicitudes s
        INNER JOIN usuarios u
            ON u.id = s.id_usuario
        INNER JOIN mascotas m
            ON m.id = s.id_mascota
        INNER JOIN tipos_solicitudes ts
            ON ts.id = s.id_tipo_solicitud
        INNER JOIN estados_solicitudes es
            ON es.id = s.id_estado_solicitud
        WHERE s.id = :id
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function existe_estado_solicitud(int $id_estado_solicitud): bool
{
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM estados_solicitudes
        WHERE id = :id_estado_solicitud
          AND estado = TRUE
        LIMIT 1
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id_estado_solicitud' => $id_estado_solicitud
    ]);

    return (bool) $stmt->fetchColumn();
}


function actualizar_estado_solicitud(
    int $id_solicitud,
    int $id_estado_solicitud
): array|false
{
    $conexion = conectarDB();

    $sql = "
        UPDATE solicitudes
        SET id_estado_solicitud = :id_estado_solicitud
        WHERE id = :id_solicitud
        RETURNING
            id,
            id_usuario,
            id_mascota,
            id_tipo_solicitud,
            id_estado_solicitud,
            fecha_solicitud
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id_solicitud' => $id_solicitud,
        ':id_estado_solicitud' => $id_estado_solicitud
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}