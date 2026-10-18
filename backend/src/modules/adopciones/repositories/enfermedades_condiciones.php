<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_enfermedades_condiciones(): array{
    $conexion = conectarDB();
    $sql = "
        SELECT
            id,
            descripcion,
            estado
        FROM enfermedades_condiciones
        WHERE estado = TRUE
        ORDER BY id
    ";
    
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}