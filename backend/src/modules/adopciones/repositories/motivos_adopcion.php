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

function existe_motivo_adopcion(int $id): bool
 {
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM motivos_adopcion
        WHERE id = :id
          AND estado = TRUE
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return (bool)$stmt->fetchColumn();
}


function existe_motivo_adopcion_por_descripcion(
    string $descripcion,
    ?int $idExcluir = null
): bool {
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM motivos_adopcion
        WHERE LOWER(descripcion) = LOWER(:descripcion)
          AND estado = TRUE
    ";

    $parametros = [
        ':descripcion' => $descripcion
    ];

    if ($idExcluir !== null) {
        $sql .= " AND id <> :id_excluir";
        $parametros[':id_excluir'] = $idExcluir;
    }
    $stmt = $conexion->prepare($sql);
    $stmt->execute($parametros);
    return (bool)$stmt->fetchColumn();
}


function crear_motivo_adopcion(
    string $descripcion
): array|false {
    $conexion = conectarDB();

    $sql = "
        INSERT INTO motivos_adopcion (
            descripcion,
            estado
        )
        VALUES (
            :descripcion,
            TRUE
        )
        RETURNING
            id,
            descripcion,
            estado
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':descripcion' => $descripcion
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function actualizar_motivo_adopcion(
    int $id,
    string $descripcion
): array|false {
    $conexion = conectarDB();
    $sql = "
        UPDATE motivos_adopcion
        SET descripcion = :descripcion
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            descripcion,
            estado
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':descripcion' => $descripcion
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function eliminar_motivo_adopcion(
    int $id
): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE motivos_adopcion
        SET estado = FALSE
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            descripcion,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}