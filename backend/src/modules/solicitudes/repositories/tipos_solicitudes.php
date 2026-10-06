<?php

require_once __DIR__ . '/../../../config/database.php';


function obtener_tipos_solicitudes(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_tipo,
            estado
        FROM tipos_solicitudes
        WHERE estado = TRUE
        ORDER BY nombre_tipo
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_tipo_solicitud_por_id(int $id): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_tipo,
            estado
        FROM tipos_solicitudes
        WHERE id = :id
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function existe_tipo_solicitud_por_nombre(
    string $nombre_tipo,
    ?int $id_excluir = null
): bool
{
    $conexion = conectarDB();

    $sql = "
        SELECT id
        FROM tipos_solicitudes
        WHERE LOWER(TRIM(nombre_tipo)) =
              LOWER(TRIM(:nombre_tipo))
    ";

    $parametros = [
        ':nombre_tipo' => $nombre_tipo
    ];

    if ($id_excluir !== null) {
        $sql .= " AND id <> :id_excluir";

        $parametros[':id_excluir'] = $id_excluir;
    }

    $sql .= " LIMIT 1";

    $stmt = $conexion->prepare($sql);
    $stmt->execute($parametros);

    return (bool) $stmt->fetchColumn();
}


function crear_tipo_solicitud(
    string $nombre_tipo
): array|false
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO tipos_solicitudes (
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

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':nombre_tipo' => $nombre_tipo
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function actualizar_tipo_solicitud(
    int $id,
    string $nombre_tipo
): array|false
{
    $conexion = conectarDB();

    $sql = "
        UPDATE tipos_solicitudes
        SET nombre_tipo = :nombre_tipo
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_tipo,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id,
        ':nombre_tipo' => $nombre_tipo
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function desactivar_tipo_solicitud(int $id): bool
{
    $conexion = conectarDB();

    $sql = "
        UPDATE tipos_solicitudes
        SET estado = FALSE
        WHERE id = :id
          AND estado = TRUE
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->rowCount() > 0;
}