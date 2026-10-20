<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_roles(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_rol,
            descripcion,
            estado
        FROM roles
        WHERE estado = TRUE
        ORDER BY id
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_rol_por_id(int $id): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_rol,
            descripcion,
            estado
        FROM roles
        WHERE id = :id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function obtener_rol_por_nombre(string $nombre_rol): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_rol,
            descripcion,
            estado
        FROM roles
        WHERE nombre_rol = :nombre_rol
          AND estado = TRUE
        LIMIT 1
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':nombre_rol' => trim($nombre_rol)
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function crear_rol(array $datos): array
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO roles (
            nombre_rol,
            descripcion,
            estado
        )
        VALUES (
            :nombre_rol,
            :descripcion,
            TRUE
        )
        RETURNING
            id,
            nombre_rol,
            descripcion,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':nombre_rol' => $datos['nombre_rol'],
        ':descripcion' => $datos['descripcion'] ?? null
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function actualizar_rol(
    int $id,
    array $datos
): array|false {

    $conexion = conectarDB();

    $sql = "
        UPDATE roles
        SET
            nombre_rol = :nombre_rol,
            descripcion = :descripcion
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_rol,
            descripcion,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id,
        ':nombre_rol' => $datos['nombre_rol'],
        ':descripcion' => $datos['descripcion'] ?? null
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function desactivar_rol(int $id): bool
{
    $conexion = conectarDB();

    $sql = "
        UPDATE roles
        SET estado = FALSE
        WHERE id = :id
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->rowCount() > 0;
}


function existe_rol_por_nombre(
    string $nombre_rol,
    ?int $id_excluir = null
): bool {

    $conexion = conectarDB();

    $sql = "
        SELECT id
        FROM roles
        WHERE LOWER(TRIM(nombre_rol)) =
              LOWER(TRIM(:nombre_rol))
    ";

    $parametros = [
        ':nombre_rol' => $nombre_rol
    ];

    if ($id_excluir !== null) {
        $sql .= " AND id <> :id_excluir";

        $parametros[':id_excluir'] =
            $id_excluir;
    }

    $sql .= " LIMIT 1";

    $consulta = $conexion->prepare($sql);
    $consulta->execute($parametros);

    return (bool) $consulta->fetch(PDO::FETCH_ASSOC);
}