<?php

require_once __DIR__ . '/../config/database.php';

function obtener_tipos_movimientos_mascotas(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_movimiento
        FROM tipos_movimientos_mascotas
        WHERE estado = TRUE
        ORDER BY id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}