<?php

function obtener_o_crear_barrio_zona(
    PDO $conexion,
    int $id_localidad,
    string $nombre_barrio
): int {

    $sql = "
        SELECT id
        FROM barrios_zonas
        WHERE id_localidad = :id_localidad
          AND LOWER(TRIM(nombre_barrio)) =
              LOWER(TRIM(:nombre_barrio))
          AND estado = TRUE
        LIMIT 1
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_localidad' => $id_localidad,
        ':nombre_barrio' => $nombre_barrio
    ]);

    $barrio = $consulta->fetch(PDO::FETCH_ASSOC);

    if ($barrio) {
        return (int) $barrio['id'];
    }

    $sql = "
        INSERT INTO barrios_zonas (
            id_localidad,
            nombre_barrio,
            estado
        )
        VALUES (
            :id_localidad,
            :nombre_barrio,
            TRUE
        )
        RETURNING id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_localidad' => $id_localidad,
        ':nombre_barrio' => $nombre_barrio
    ]);

    $barrio = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$barrio) {
        throw new RuntimeException(
            'No se pudo registrar el barrio o zona.'
        );
    }

    return (int) $barrio['id'];
}