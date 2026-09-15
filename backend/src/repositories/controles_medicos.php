<?php

require_once __DIR__ . '/../config/database.php';

function obtener_controles_por_ficha(int $id_ficha_medica): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_ficha_medica,
            fecha_control,
            peso,
            observaciones
        FROM controles_medicos
        WHERE id_ficha_medica = :id_ficha_medica
        ORDER BY fecha_control DESC, id DESC
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $id_ficha_medica
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function crear_control_medico(array $datos): array
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO controles_medicos (
            id_ficha_medica,
            fecha_control,
            peso,
            observaciones
        )
        VALUES (
            :id_ficha_medica,
            :fecha_control,
            :peso,
            :observaciones
        )
        RETURNING
            id,
            id_ficha_medica,
            fecha_control,
            peso,
            observaciones
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $datos['id_ficha_medica'],
        ':fecha_control' => $datos['fecha_control'],
        ':peso' => $datos['peso'] ?? null,
        ':observaciones' => $datos['observaciones'] ?? null
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}