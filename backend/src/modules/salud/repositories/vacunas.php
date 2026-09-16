<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_vacunas_por_ficha(int $id_ficha_medica): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            v.id,
            v.id_ficha_medica,
            v.id_tipo_vacuna,
            tv.nombre_vacuna,
            v.numero_dosis,
            v.fecha_aplicacion,
            v.proxima_dosis,
            v.observaciones
        FROM vacunas v
        INNER JOIN tipos_vacunas tv
            ON tv.id = v.id_tipo_vacuna
        WHERE v.id_ficha_medica = :id_ficha_medica
        ORDER BY v.fecha_aplicacion DESC, v.id DESC
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $id_ficha_medica
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function crear_vacuna(array $datos): array
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO vacunas (
            id_ficha_medica,
            id_tipo_vacuna,
            numero_dosis,
            fecha_aplicacion,
            proxima_dosis,
            observaciones
        )
        VALUES (
            :id_ficha_medica,
            :id_tipo_vacuna,
            :numero_dosis,
            :fecha_aplicacion,
            :proxima_dosis,
            :observaciones
        )
        RETURNING
            id,
            id_ficha_medica,
            id_tipo_vacuna,
            numero_dosis,
            fecha_aplicacion,
            proxima_dosis,
            observaciones
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $datos['id_ficha_medica'],
        ':id_tipo_vacuna' => $datos['id_tipo_vacuna'],
        ':numero_dosis' => $datos['numero_dosis'],
        ':fecha_aplicacion' => $datos['fecha_aplicacion'],
        ':proxima_dosis' => $datos['proxima_dosis'] ?? null,
        ':observaciones' => $datos['observaciones'] ?? null
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}