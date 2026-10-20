<?php

require_once __DIR__ . '/../../../config/database.php';

function obtenerRazasPorEspecie($idEspecie) {
    $conexion = conectarDB();
    $sql = "
        SELECT
            id,
            id_especie,
            nombre_raza
        FROM razas
        WHERE id_especie = :id_especie
          AND estado = TRUE
        ORDER BY nombre_raza
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':id_especie' => $idEspecie
    ]);
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function existeRazaParaEspecie($idRaza, $idEspecie){
    $conexion = conectarDB();
    $sql = "
        SELECT 1
        FROM razas
        WHERE id = :id_raza
          AND id_especie = :id_especie
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':id_raza' => $idRaza,
        ':id_especie' => $idEspecie
    ]);

    return (bool)$consulta->fetchColumn();
}

function crearRaza(int $idEspecie, string $nombre): array|false {
    $conexion = conectarDB();
    $sql = "
        INSERT INTO razas (
            id_especie,
            nombre_raza,
            estado
        )
        VALUES (
            :id_especie,
            :nombre_raza,
            TRUE
        )
        RETURNING
            id,
            id_especie,
            nombre_raza,
            estado
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':id_especie' => $idEspecie,
        ':nombre_raza' => $nombre
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function actualizarRaza(
    int $id,
    int $idEspecie,
    string $nombre
): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE razas
        SET
            id_especie = :id_especie,
            nombre_raza = :nombre_raza
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            id_especie,
            nombre_raza,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id,
        ':id_especie' => $idEspecie,
        ':nombre_raza' => $nombre
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function eliminarRaza(int $id): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE razas
        SET estado = FALSE
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            id_especie,
            nombre_raza,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function existeRazaPorId(int $id): bool {
    $conexion = conectarDB();
    $sql = "
        SELECT 1
        FROM razas
        WHERE id = :id
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':id' => $id
    ]);
    return (bool)$consulta->fetchColumn();
}


function existeRazaPorNombre(
    int $idEspecie,
    string $nombre,
    ?int $idExcluir = null
): bool {
    $conexion = conectarDB();
    $sql = "
        SELECT 1
        FROM razas
        WHERE id_especie = :id_especie
          AND LOWER(nombre_raza) = LOWER(:nombre)
          AND estado = TRUE
    ";
    $parametros = [
        ':id_especie' => $idEspecie,
        ':nombre' => $nombre
    ];
    if ($idExcluir !== null) {
        $sql .= " AND id <> :id_excluir";
        $parametros[':id_excluir'] = $idExcluir;
    }
    $consulta = $conexion->prepare($sql);
    $consulta->execute($parametros);

    return (bool)$consulta->fetchColumn();
}