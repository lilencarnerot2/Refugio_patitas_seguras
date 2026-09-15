<?php

require_once __DIR__ . '/../config/database.php';

function obtener_desparasitaciones_por_ficha(int $id_ficha_medica): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            d.id,
            d.id_ficha_medica,
            d.id_tipo_producto,
            tp.nombre_tipo,
            d.tipo_parasito,
            d.fecha_aplicacion,
            d.proxima_dosis,
            d.observaciones
        FROM desparasitaciones d
        INNER JOIN tipos_productos tp
            ON tp.id = d.id_tipo_producto
        WHERE d.id_ficha_medica = :id_ficha_medica
        ORDER BY d.fecha_aplicacion DESC, d.id DESC
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $id_ficha_medica
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function crear_desparasitacion(array $datos): array
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO desparasitaciones (
            id_ficha_medica,
            id_tipo_producto,
            tipo_parasito,
            fecha_aplicacion,
            proxima_dosis,
            observaciones
        )
        VALUES (
            :id_ficha_medica,
            :id_tipo_producto,
            :tipo_parasito,
            :fecha_aplicacion,
            :proxima_dosis,
            :observaciones
        )
        RETURNING
            id,
            id_ficha_medica,
            id_tipo_producto,
            tipo_parasito,
            fecha_aplicacion,
            proxima_dosis,
            observaciones
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $datos['id_ficha_medica'],
        ':id_tipo_producto' => $datos['id_tipo_producto'],
        ':tipo_parasito' => $datos['tipo_parasito'],
        ':fecha_aplicacion' => $datos['fecha_aplicacion'],
        ':proxima_dosis' => $datos['proxima_dosis'] ?? null,
        ':observaciones' => $datos['observaciones'] ?? null
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}