<?php

require_once __DIR__ . '/../config/database.php';

function obtener_castracion_por_ficha(int $id_ficha_medica): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_ficha_medica,
            fecha_castracion,
            observaciones
        FROM castraciones
        WHERE id_ficha_medica = :id_ficha_medica
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $id_ficha_medica
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function existe_castracion_para_ficha(int $id_ficha_medica): bool
{
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM castraciones
        WHERE id_ficha_medica = :id_ficha_medica
        LIMIT 1
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $id_ficha_medica
    ]);

    return (bool) $consulta->fetchColumn();
}


function crear_castracion(array $datos): array
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO castraciones (
            id_ficha_medica,
            fecha_castracion,
            observaciones
        )
        VALUES (
            :id_ficha_medica,
            :fecha_castracion,
            :observaciones
        )
        RETURNING
            id,
            id_ficha_medica,
            fecha_castracion,
            observaciones
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $datos['id_ficha_medica'],
        ':fecha_castracion' => $datos['fecha_castracion'] ?? null,
        ':observaciones' => $datos['observaciones'] ?? null
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}