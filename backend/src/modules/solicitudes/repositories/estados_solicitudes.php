<?php

require_once __DIR__ . '/../../../config/database.php';


function obtener_estados_solicitudes(): array{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_estado,
            estado
        FROM estados_solicitudes
        WHERE estado = TRUE
        ORDER BY id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_estado_solicitud_catalogo_por_id(
    int $id
): array|false{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_estado,
            estado
        FROM estados_solicitudes
        WHERE id = :id
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function existe_estado_solicitud_por_nombre(
    string $nombre_estado,
    ?int $id_excluir = null
): bool{
    $conexion = conectarDB();

    $sql = "
        SELECT id
        FROM estados_solicitudes
        WHERE LOWER(TRIM(nombre_estado)) =
              LOWER(TRIM(:nombre_estado))
    ";

    $parametros = [
        ':nombre_estado' => $nombre_estado
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


function crear_estado_solicitud_catalogo(
    string $nombre_estado
): array|false{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO estados_solicitudes (
            nombre_estado,
            estado
        )
        VALUES (
            :nombre_estado,
            TRUE
        )
        RETURNING
            id,
            nombre_estado,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':nombre_estado' => $nombre_estado
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function actualizar_estado_solicitud_catalogo(
    int $id,
    string $nombre_estado
): array|false{
    $conexion = conectarDB();

    $sql = "
        UPDATE estados_solicitudes
        SET nombre_estado = :nombre_estado
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_estado,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id,
        ':nombre_estado' => $nombre_estado
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function desactivar_estado_solicitud_catalogo(
    int $id
): bool{
    $conexion = conectarDB();

    $sql = "
        UPDATE estados_solicitudes
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