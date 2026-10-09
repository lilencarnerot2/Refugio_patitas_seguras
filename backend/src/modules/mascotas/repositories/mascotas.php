<?php

require_once __DIR__ . '/../../../config/database.php';

function obtenerMascotas()
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            m.id,
            m.nombre,
            m.fecha_nacimiento_estimada,
            m.historial,
            m.foto_url,
            e.nombre_especie AS especie,
            r.nombre_raza AS raza,
            m.sexo,
            m.tamaño
        FROM mascotas m
        INNER JOIN especies e
            ON e.id = m.id_especie
        INNER JOIN razas r
            ON r.id = m.id_raza
        WHERE m.estado = TRUE
        ORDER BY m.id
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerMascotaPorId($id)
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            m.id,
            m.nombre,
            m.fecha_nacimiento_estimada,
            m.historial,
            m.foto_url,
            m.estado,
            e.nombre_especie AS especie,
            r.nombre_raza AS raza,
            m.sexo,
            m.tamaño
        FROM mascotas m
        INNER JOIN especies e
            ON e.id = m.id_especie
        INNER JOIN razas r
            ON r.id = m.id_raza
        WHERE m.id = :id
          AND m.estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->fetch();
}

//POST de mascotas
function crearMascota($datos)
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO mascotas (
            id_especie,
            id_raza,
            nombre,
            sexo,
            tamaño,
            fecha_nacimiento_estimada,
            historial,
            foto_url,
            estado
        )
        VALUES (
            :id_especie,
            :id_raza,
            :nombre,
            :sexo,
            :tamano,
            :fecha_nacimiento_estimada,
            :historial,
            :foto_url,
            TRUE
        )
        RETURNING
            id,
            id_especie,
            id_raza,
            nombre,
            sexo,
            tamaño,
            fecha_nacimiento_estimada,
            historial,
            foto_url,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_especie' => $datos['id_especie'],
        ':id_raza' => $datos['id_raza'],
        ':nombre' => $datos['nombre'],
        ':sexo' => $datos['sexo'],
        ':tamano' => $datos['tamaño'],
        ':fecha_nacimiento_estimada' => $datos['fecha_nacimiento_estimada'],
        ':historial' => $datos['historial'],
        ':foto_url' => $datos['foto_url']
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

//PUT para editar mascotas existentes
function actualizarMascota($id, $datos){
    $conexion = conectarDB();

    $sql = "
        UPDATE mascotas
        SET
            id_especie = :id_especie,
            id_raza = :id_raza,
            nombre = :nombre,
            sexo = :sexo,
            tamaño = :tamano,
            fecha_nacimiento_estimada = :fecha_nacimiento_estimada,
            historial = :historial,
            foto_url = :foto_url
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            id_especie,
            id_raza,
            nombre,
            sexo,
            tamaño,
            fecha_nacimiento_estimada,
            historial,
            foto_url,
            estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id,
        ':id_especie' => $datos['id_especie'],
        ':id_raza' => $datos['id_raza'],
        ':nombre' => $datos['nombre'],
        ':sexo' => $datos['sexo'],
        ':tamano' => $datos['tamaño'],
        ':fecha_nacimiento_estimada' => $datos['fecha_nacimiento_estimada'],
        ':historial' => $datos['historial'],
        ':foto_url' => $datos['foto_url']
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

//Baja lógica de mascotas DELETE
function desactivarMascota($id){
    $conexion = conectarDB();

    $sql = "
        UPDATE mascotas
        SET estado = FALSE
        WHERE id = :id
          AND estado = TRUE
        RETURNING id, nombre, estado
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}
