<?php

require_once __DIR__ . '/../config/database.php';

function obtener_movimientos_mascotas(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            mm.id,
            mm.id_mascota,
            m.nombre AS nombre_mascota,
            mm.id_tipo_movimiento,
            tmm.nombre_movimiento,
            mm.id_solicitud,
            mm.fecha_movimiento,
            mm.observaciones
        FROM movimientos_mascotas mm
        INNER JOIN mascotas m
            ON m.id = mm.id_mascota
        INNER JOIN tipos_movimientos_mascotas tmm
            ON tmm.id = mm.id_tipo_movimiento
        ORDER BY mm.fecha_movimiento DESC, mm.id DESC
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_movimientos_por_mascota(int $id_mascota): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            mm.id,
            mm.id_mascota,
            m.nombre AS nombre_mascota,
            mm.id_tipo_movimiento,
            tmm.nombre_movimiento,
            mm.id_solicitud,
            mm.fecha_movimiento,
            mm.observaciones
        FROM movimientos_mascotas mm
        INNER JOIN mascotas m
            ON m.id = mm.id_mascota
        INNER JOIN tipos_movimientos_mascotas tmm
            ON tmm.id = mm.id_tipo_movimiento
        WHERE mm.id_mascota = :id_mascota
        ORDER BY mm.fecha_movimiento DESC, mm.id DESC
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id_mascota' => $id_mascota
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function existe_tipo_movimiento_mascota(int $id_tipo_movimiento): bool
{
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM tipos_movimientos_mascotas
        WHERE id = :id_tipo_movimiento
          AND estado = TRUE
        LIMIT 1
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id_tipo_movimiento' => $id_tipo_movimiento
    ]);

    return (bool) $stmt->fetchColumn();
}


function crear_movimiento_mascota(
    int $id_mascota,
    int $id_tipo_movimiento,
    ?int $id_solicitud,
    ?string $observaciones
): array
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO movimientos_mascotas (
            id_mascota,
            id_tipo_movimiento,
            id_solicitud,
            observaciones
        )
        VALUES (
            :id_mascota,
            :id_tipo_movimiento,
            :id_solicitud,
            :observaciones
        )
        RETURNING
            id,
            id_mascota,
            id_tipo_movimiento,
            id_solicitud,
            fecha_movimiento,
            observaciones
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id_mascota' => $id_mascota,
        ':id_tipo_movimiento' => $id_tipo_movimiento,
        ':id_solicitud' => $id_solicitud,
        ':observaciones' => $observaciones
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function existe_mascota(int $id_mascota): bool
{
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM mascotas
        WHERE id = :id_mascota
        LIMIT 1
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id_mascota' => $id_mascota
    ]);

    return (bool) $stmt->fetchColumn();
}

function existe_solicitud(int $id_solicitud): bool
{
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM solicitudes
        WHERE id = :id_solicitud
        LIMIT 1
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id_solicitud' => $id_solicitud
    ]);

    return (bool) $stmt->fetchColumn();
}