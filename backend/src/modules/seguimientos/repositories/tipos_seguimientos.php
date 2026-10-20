<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_tipos_seguimientos(): array {
    $conexion = conectarDB();
    $sql = "
        SELECT
            id,
            descripcion,
            estado
        FROM tipos_seguimientos
        WHERE estado = TRUE
        ORDER BY id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}