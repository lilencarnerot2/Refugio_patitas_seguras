<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_tipos_vivienda(): array {
    $conexion = conectarDB();
    $sql = "
        SELECT
            id,
            nombre_tipo,
            estado
        FROM tipos_vivienda
        WHERE estado = TRUE
        ORDER BY nombre_tipo
    ";
    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function existe_tipo_vivienda(int $id): bool {
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM tipos_vivienda
        WHERE id = :id
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return (bool)$consulta->fetchColumn();
}


function existe_tipo_vivienda_por_nombre(
    string $nombre,
    ?int $idExcluir = null
): bool {
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM tipos_vivienda
        WHERE LOWER(nombre_tipo) = LOWER(:nombre)
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


function crear_tipo_vivienda(
    string $nombre
): array|false {
    $conexion = conectarDB();

    $sql = "
        INSERT INTO tipos_vivienda (
            nombre_tipo,
            estado
        )
        VALUES (
            :nombre_tipo,
            TRUE
        )
        RETURNING
            id,
            nombre_tipo,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':nombre_tipo' => $nombre
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function actualizar_tipo_vivienda(
    int $id,
    string $nombre
): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE tipos_vivienda
        SET nombre_tipo = :nombre_tipo
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_tipo,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id,
        ':nombre_tipo' => $nombre
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function eliminar_tipo_vivienda(
    int $id
): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE tipos_vivienda
        SET estado = FALSE
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_tipo,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}