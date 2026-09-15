<?php

require_once __DIR__ . '/../config/database.php';

function obtener_estados_solicitudes(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_estado
        FROM estados_solicitudes
        WHERE estado = TRUE
        ORDER BY id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}