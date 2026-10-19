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

function agregarFotoMascota(int $idMascota, string $fotoUrl): array|false {
    $conexion = conectarDB();

    $sql = "
        INSERT INTO fotos_mascotas (
            id_mascota,
            foto_url
        )
        VALUES (
            :id_mascota,
            :foto_url
        )
        RETURNING
            id,
            id_mascota,
            foto_url
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':id_mascota' => $idMascota,
        ':foto_url' => $fotoUrl
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function obtenerFotosMascota(int $idMascota): array {
    $conexion = conectarDB();
    $sql = "
        SELECT
            id,
            id_mascota,
            foto_url
        FROM fotos_mascotas
        WHERE id_mascota = :id_mascota
        ORDER BY id
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':id_mascota' => $idMascota
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function eliminarFotoMascota(int $idFoto, int $idMascota): array|false {
    $conexion = conectarDB();
    $sql = "
        DELETE FROM fotos_mascotas
        WHERE id = :id
          AND id_mascota = :id_mascota
        RETURNING
            id,
            foto_url
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':id' => $idFoto,
        ':id_mascota' => $idMascota
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}