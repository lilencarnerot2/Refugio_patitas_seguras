<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_roles(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_rol,
            descripcion,
            estado
        FROM roles
        WHERE estado = TRUE
        ORDER BY id
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_rol_por_id(int $id): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_rol,
            descripcion,
            estado
        FROM roles
        WHERE id = :id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function obtener_rol_por_nombre(string $nombre_rol): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_rol,
            descripcion,
            estado
        FROM roles
        WHERE nombre_rol = :nombre_rol
          AND estado = TRUE
        LIMIT 1
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':nombre_rol' => trim($nombre_rol)
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}