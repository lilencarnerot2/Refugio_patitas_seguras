<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_tipos_vacunas(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_vacuna
        FROM tipos_vacunas
        WHERE estado = TRUE
        ORDER BY nombre_vacuna
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function existe_tipo_vacuna(int $id_tipo_vacuna): bool
{
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM tipos_vacunas
        WHERE id = :id_tipo_vacuna
          AND estado = TRUE
        LIMIT 1
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_tipo_vacuna' => $id_tipo_vacuna
    ]);

    return (bool) $consulta->fetchColumn();
}