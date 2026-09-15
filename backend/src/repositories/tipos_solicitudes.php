<?php

require_once __DIR__ . '/../config/database.php';

function obtenerTiposSolicitudes(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_tipo
        FROM tipos_solicitudes
        WHERE estado = TRUE
        ORDER BY nombre_tipo
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}