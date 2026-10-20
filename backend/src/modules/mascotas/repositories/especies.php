<?php

require_once __DIR__ . '/../../../config/database.php';

function obtenerEspecies() {
    $conexion = conectarDB();
    $sql = "
        SELECT
            id,
            nombre_especie
        FROM especies
        WHERE estado = TRUE
        ORDER BY nombre_especie
    ";
    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function existeEspecie($idEspecie) {
    $conexion = conectarDB();
    $sql = "
        SELECT 1
        FROM especies
        WHERE id = :id
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':id' => $idEspecie
    ]);

    return (bool)$consulta->fetchColumn();
}

function crearEspecie(string $nombre): array|false {
    $conexion = conectarDB();

    $sql = "
        INSERT INTO especies (
            nombre_especie,
            estado
        )
        VALUES (
            :nombre_especie,
            TRUE
        )
        RETURNING
            id,
            nombre_especie,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':nombre_especie' => $nombre
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function actualizarEspecie(
    int $id,
    string $nombre
): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE especies
        SET nombre_especie = :nombre_especie
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_especie,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id,
        ':nombre_especie' => $nombre
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function eliminarEspecie(int $id): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE especies
        SET estado = FALSE
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_especie,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function existeEspeciePorNombre(
    string $nombre,
    ?int $idExcluir = null
): bool {
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM especies
        WHERE LOWER(nombre_especie) = LOWER(:nombre)
          AND estado = TRUE
    ";

    $parametros = [
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