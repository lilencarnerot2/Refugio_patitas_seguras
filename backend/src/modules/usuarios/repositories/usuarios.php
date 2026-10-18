<?php

require_once __DIR__ . '/../../../config/database.php';

function obtener_usuarios(): array{
    $conexion = conectarDB();

    $sql = "
        SELECT
            u.id,
            u.uid_pp2,
            u.id_rol,
            r.nombre_rol,
            u.id_domicilio,
            u.nombre,
            u.apellido,
            u.fecha_nacimiento,
            u.fecha_alta,
            u.estado
        FROM usuarios u
        INNER JOIN roles r
            ON r.id = u.id_rol
        ORDER BY u.id
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_usuario_por_id(int $id): array|false{
    $conexion = conectarDB();

    $sql = "
        SELECT
            u.id,
            u.uid_pp2,
            u.id_rol,
            r.nombre_rol,
            u.id_domicilio,
            u.nombre,
            u.apellido,
            u.fecha_nacimiento,
            u.fecha_alta,
            u.estado
        FROM usuarios u
        INNER JOIN roles r
            ON r.id = u.id_rol
        WHERE u.id = :id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function obtener_usuario_por_uid_pp2(string $uid_pp2): array|false{
    $conexion = conectarDB();

    $sql = "
        SELECT
            u.id,
            u.uid_pp2,
            u.id_rol,
            r.nombre_rol,
            u.id_domicilio,
            u.nombre,
            u.apellido,
            u.fecha_nacimiento,
            u.fecha_alta,
            u.estado,
            u.password
        FROM usuarios u
        INNER JOIN roles r
            ON r.id = u.id_rol
        WHERE u.uid_pp2 = :uid_pp2
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':uid_pp2' => $uid_pp2
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function crear_usuario_desde_padron(array $datos): array|false{
    $conexion = conectarDB();

    $password_local = null;

    if (isset($datos['password']) && is_string($datos['password']) && $datos['password'] !== '') {
        $password_local = password_hash($datos['password'], PASSWORD_DEFAULT);
    }

    $sql = "
        INSERT INTO usuarios (
            id_rol,
            nombre,
            apellido,
            fecha_nacimiento,
            fecha_alta,
            estado,
            uid_pp2,
            password
        )
        VALUES (
            :id_rol,
            :nombre,
            :apellido,
            :fecha_nacimiento,
            NOW(),
            TRUE,
            :uid_pp2,
            :password
        )
        RETURNING
            id,
            id_rol,
            id_domicilio,
            nombre,
            apellido,
            fecha_nacimiento,
            fecha_alta,
            estado,
            uid_pp2
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_rol' => (int) $datos['id_rol'],
        ':nombre' => $datos['nombre'],
        ':apellido' => $datos['apellido'],
        ':fecha_nacimiento' => $datos['fecha_nacimiento'],
        ':uid_pp2' => $datos['uid_pp2'],
        ':password' => $password_local
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function actualizar_usuario_desde_padron(int $id, array $datos): array|false {
    $conexion = conectarDB();
    $sql = "
        UPDATE usuarios
        SET
            nombre = :nombre,
            apellido = :apellido
        WHERE id = :id
        RETURNING
            id,
            id_rol,
            id_domicilio,
            nombre,
            apellido,
            fecha_nacimiento,
            fecha_alta,
            estado,
            uid_pp2
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':id' => $id,
        ':nombre' => $datos['nombre'],
        ':apellido' => $datos['apellido']
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function actualizar_usuario(
    int $id,
    array $datos
): array|false{
    $conexion = conectarDB();

    $sql = "
        UPDATE usuarios
        SET
            nombre = :nombre,
            apellido = :apellido,
            fecha_nacimiento = :fecha_nacimiento
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            uid_pp2,
            id_rol,
            id_domicilio,
            nombre,
            apellido,
            fecha_nacimiento,
            fecha_alta,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id,
        ':nombre' => $datos['nombre'],
        ':apellido' => $datos['apellido'],
        ':fecha_nacimiento' => $datos['fecha_nacimiento']
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function actualizar_rol_usuario(
    int $id_usuario,
    int $id_rol): bool{
    $conexion = conectarDB();

    $sql = "
        UPDATE usuarios
        SET id_rol = :id_rol
        WHERE id = :id_usuario
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_usuario' => $id_usuario,
        ':id_rol' => $id_rol
    ]);

    return $consulta->rowCount() > 0;
}


function desactivar_usuario(int $id): bool{
    $conexion = conectarDB();

    $sql = "
        UPDATE usuarios
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