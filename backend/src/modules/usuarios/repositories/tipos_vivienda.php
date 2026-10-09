<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_tipos_vivienda(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_tipo,
            estado
        FROM tipos_vivienda
        WHERE estado = TRUE
        ORDER BY nombre_tipo
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}