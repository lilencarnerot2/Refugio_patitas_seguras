<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_tipos_contacto(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            descripcion,
            estado
        FROM tipos_contacto
        WHERE estado = TRUE
        ORDER BY descripcion
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}