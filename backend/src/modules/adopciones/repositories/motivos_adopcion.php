<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_motivos_adopcion(): array {
    $conexion = conectarDB();
    $sql = "
        SELECT
            id,
            descripcion
        FROM motivos_adopcion
        WHERE estado = TRUE
        ORDER BY id
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}