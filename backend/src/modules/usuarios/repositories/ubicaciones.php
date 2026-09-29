<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_provincias(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre
        FROM provincias
        ORDER BY nombre
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function obtener_comunas_por_provincia(int $id_provincia): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_provincia,
            nombre
        FROM comunas_municipios
        WHERE id_provincia = :id_provincia
        ORDER BY nombre
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_provincia' => $id_provincia
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function obtener_localidades_por_comuna(int $id_comuna_municipio): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_comuna_municipio,
            nombre_localidad,
            estado
        FROM localidades
        WHERE id_comuna_municipio = :id_comuna_municipio
          AND estado = TRUE
        ORDER BY nombre_localidad
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_comuna_municipio' => $id_comuna_municipio
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function obtener_barrios_por_localidad(int $id_localidad): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_localidad,
            nombre_barrio,
            estado
        FROM barrios_zonas
        WHERE id_localidad = :id_localidad
          AND estado = TRUE
        ORDER BY nombre_barrio
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_localidad' => $id_localidad
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}