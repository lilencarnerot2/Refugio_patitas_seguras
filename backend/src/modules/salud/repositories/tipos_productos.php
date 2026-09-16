<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_tipos_productos(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_tipo
        FROM tipos_productos
        WHERE estado = TRUE
        ORDER BY nombre_tipo
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function existe_tipo_producto(int $id_tipo_producto): bool
{
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM tipos_productos
        WHERE id = :id_tipo_producto
          AND estado = TRUE
        LIMIT 1
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_tipo_producto' => $id_tipo_producto
    ]);

    return (bool) $consulta->fetchColumn();
}