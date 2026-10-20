<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_ficha_medica_por_mascota(int $id_mascota): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_mascota,
            observaciones,
            fecha_actualizacion
        FROM fichas_medicas
        WHERE id_mascota = :id_mascota
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_mascota' => $id_mascota
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function obtener_ficha_medica_por_id(int $id): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_mascota,
            observaciones,
            fecha_actualizacion
        FROM fichas_medicas
        WHERE id = :id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function existe_ficha_medica_para_mascota(int $id_mascota): bool
{
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM fichas_medicas
        WHERE id_mascota = :id_mascota
        LIMIT 1
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_mascota' => $id_mascota
    ]);

    return (bool) $consulta->fetchColumn();
}


function crear_ficha_medica(array $datos): array
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO fichas_medicas (
            id_mascota,
            observaciones,
            fecha_actualizacion
        )
        VALUES (
            :id_mascota,
            :observaciones,
            :fecha_actualizacion
        )
        RETURNING
            id,
            id_mascota,
            observaciones,
            fecha_actualizacion
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_mascota' => $datos['id_mascota'],
        ':observaciones' => $datos['observaciones'] ?? null,
        ':fecha_actualizacion' => $datos['fecha_actualizacion'] ?? date('Y-m-d')
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function actualizar_ficha_medica(int $id, array $datos): array|false
{
    $conexion = conectarDB();

    $sql = "
        UPDATE fichas_medicas
        SET
            observaciones = :observaciones,
            fecha_actualizacion = :fecha_actualizacion
        WHERE id = :id
        RETURNING
            id,
            id_mascota,
            observaciones,
            fecha_actualizacion
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id,
        ':observaciones' => $datos['observaciones'] ?? null,
        ':fecha_actualizacion' => $datos['fecha_actualizacion'] ?? date('Y-m-d')
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}