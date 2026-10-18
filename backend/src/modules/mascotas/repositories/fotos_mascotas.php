<?php

require_once __DIR__ . '/../../../config/database.php';

function actualizarFotoMascota($id, $fotoUrl) {
    $conexion = conectarDB();

    $sql = "
        UPDATE mascotas
        SET foto_url = :foto_url
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre,
            foto_url
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':id' => $id,
        ':foto_url' => $fotoUrl
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}