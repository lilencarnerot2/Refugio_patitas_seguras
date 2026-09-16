<?php

require_once __DIR__ . '/../../../config/database.php';

function buscarUsuarioPorUidPP2($uid_pp2)
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_rol,
            id_domicilio,
            nombre,
            apellido,
            fecha_nacimiento,
            fecha_alta,
            estado,
            uid_pp2,
            password
        FROM usuarios
        WHERE uid_pp2 = :uid_pp2
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':uid_pp2' => $uid_pp2
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function crearUsuarioDesdePadron($datos)
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO usuarios (
            id_rol,
            nombre,
            apellido,
            fecha_alta,
            estado,
            uid_pp2,
            password
        )
        VALUES (
            :id_rol,
            :nombre,
            :apellido,
            NOW(),
            TRUE,
            :uid_pp2,
            NULL
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
        ':id_rol' => $datos['id_rol'],
        ':nombre' => $datos['nombre'],
        ':apellido' => $datos['apellido'],
        ':uid_pp2' => $datos['uid_pp2']
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function actualizarUsuarioDesdePadron($id, $datos)
{
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