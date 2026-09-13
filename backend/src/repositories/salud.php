<?php

require_once __DIR__ . '/../config/database.php';

/* ============================================================
 * FICHAS MÉDICAS
 * ============================================================ */

function obtenerFichaMedicaPorMascota($id_mascota)
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_mascota,
            observaciones,
            fecha_actualizacion
        FROM fichas_medicas
        WHERE id_mascota = :id_mascota
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_mascota' => $id_mascota
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function obtenerFichaMedicaPorId($id)
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_mascota,
            observaciones,
            fecha_actualizacion
        FROM fichas_medicas
        WHERE id = :id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function existeFichaMedicaParaMascota($id_mascota)
{
    $conexion = conectarDB();

    $sql = "
        SELECT id
        FROM fichas_medicas
        WHERE id_mascota = :id_mascota
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_mascota' => $id_mascota
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC) !== false;
}

function crearFichaMedica($datos)
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO fichas_medicas (
            id_mascota,
            observaciones,
            fecha_actualizacion
        )
        VALUES (
            :id_mascota,
            :observaciones,
            :fecha_actualizacion
        )
        RETURNING
            id,
            id_mascota,
            observaciones,
            fecha_actualizacion
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_mascota' => $datos['id_mascota'],
        ':observaciones' => $datos['observaciones'] ?? null,
        ':fecha_actualizacion' => $datos['fecha_actualizacion'] ?? date('Y-m-d')
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function actualizarFichaMedica($id, $datos)
{
    $conexion = conectarDB();

    $sql = "
        UPDATE fichas_medicas
        SET
            observaciones = :observaciones,
            fecha_actualizacion = :fecha_actualizacion
        WHERE id = :id
        RETURNING
            id,
            id_mascota,
            observaciones,
            fecha_actualizacion
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id,
        ':observaciones' => $datos['observaciones'] ?? null,
        ':fecha_actualizacion' => $datos['fecha_actualizacion'] ?? date('Y-m-d')
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

/* ============================================================
 * CONTROLES MÉDICOS
 * ============================================================ */

function obtenerControlesPorFicha($id_ficha_medica)
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_ficha_medica,
            fecha_control,
            peso,
            observaciones
        FROM controles_medicos
        WHERE id_ficha_medica = :id_ficha_medica
        ORDER BY fecha_control DESC
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $id_ficha_medica
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function crearControlMedico($datos)
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO controles_medicos (
            id_ficha_medica,
            fecha_control,
            peso,
            observaciones
        )
        VALUES (
            :id_ficha_medica,
            :fecha_control,
            :peso,
            :observaciones
        )
        RETURNING
            id,
            id_ficha_medica,
            fecha_control,
            peso,
            observaciones
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $datos['id_ficha_medica'],
        ':fecha_control' => $datos['fecha_control'],
        ':peso' => $datos['peso'] ?? null,
        ':observaciones' => $datos['observaciones'] ?? null
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

/* ============================================================
 * VACUNAS
 * ============================================================ */

function obtenerVacunasPorFicha($id_ficha_medica)
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            v.id,
            v.id_ficha_medica,
            v.id_tipo_vacuna,
            tv.nombre_vacuna,
            v.numero_dosis,
            v.fecha_aplicacion,
            v.proxima_dosis,
            v.observaciones
        FROM vacunas v
        INNER JOIN tipos_vacunas tv
            ON tv.id = v.id_tipo_vacuna
        WHERE v.id_ficha_medica = :id_ficha_medica
        ORDER BY v.fecha_aplicacion DESC
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $id_ficha_medica
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function crearVacuna($datos)
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO vacunas (
            id_ficha_medica,
            id_tipo_vacuna,
            numero_dosis,
            fecha_aplicacion,
            proxima_dosis,
            observaciones
        )
        VALUES (
            :id_ficha_medica,
            :id_tipo_vacuna,
            :numero_dosis,
            :fecha_aplicacion,
            :proxima_dosis,
            :observaciones
        )
        RETURNING
            id,
            id_ficha_medica,
            id_tipo_vacuna,
            numero_dosis,
            fecha_aplicacion,
            proxima_dosis,
            observaciones
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $datos['id_ficha_medica'],
        ':id_tipo_vacuna' => $datos['id_tipo_vacuna'],
        ':numero_dosis' => $datos['numero_dosis'],
        ':fecha_aplicacion' => $datos['fecha_aplicacion'],
        ':proxima_dosis' => $datos['proxima_dosis'] ?? null,
        ':observaciones' => $datos['observaciones'] ?? null
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function obtenerTiposVacunas()
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_vacuna,
            estado
        FROM tipos_vacunas
        WHERE estado = TRUE
        ORDER BY nombre_vacuna
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

/* ============================================================
 * CASTRACIONES
 * ============================================================ */

function obtenerCastracionPorFicha($id_ficha_medica)
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_ficha_medica,
            fecha_castracion,
            observaciones
        FROM castraciones
        WHERE id_ficha_medica = :id_ficha_medica
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $id_ficha_medica
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function crearCastracion($datos)
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO castraciones (
            id_ficha_medica,
            fecha_castracion,
            observaciones
        )
        VALUES (
            :id_ficha_medica,
            :fecha_castracion,
            :observaciones
        )
        RETURNING
            id,
            id_ficha_medica,
            fecha_castracion,
            observaciones
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $datos['id_ficha_medica'],
        ':fecha_castracion' => $datos['fecha_castracion'] ?? null,
        ':observaciones' => $datos['observaciones'] ?? null
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

/* ============================================================
 * DESPARASITACIONES
 * ============================================================ */

function obtenerDesparasitacionesPorFicha($id_ficha_medica)
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            d.id,
            d.id_ficha_medica,
            d.id_tipo_producto,
            tp.nombre_tipo,
            d.tipo_parasito,
            d.fecha_aplicacion,
            d.proxima_dosis,
            d.observaciones
        FROM desparasitaciones d
        INNER JOIN tipos_productos tp
            ON tp.id = d.id_tipo_producto
        WHERE d.id_ficha_medica = :id_ficha_medica
        ORDER BY d.fecha_aplicacion DESC
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $id_ficha_medica
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function crearDesparasitacion($datos)
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO desparasitaciones (
            id_ficha_medica,
            id_tipo_producto,
            tipo_parasito,
            fecha_aplicacion,
            proxima_dosis,
            observaciones
        )
        VALUES (
            :id_ficha_medica,
            :id_tipo_producto,
            :tipo_parasito,
            :fecha_aplicacion,
            :proxima_dosis,
            :observaciones
        )
        RETURNING
            id,
            id_ficha_medica,
            id_tipo_producto,
            tipo_parasito,
            fecha_aplicacion,
            proxima_dosis,
            observaciones
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_ficha_medica' => $datos['id_ficha_medica'],
        ':id_tipo_producto' => $datos['id_tipo_producto'],
        ':tipo_parasito' => $datos['tipo_parasito'],
        ':fecha_aplicacion' => $datos['fecha_aplicacion'],
        ':proxima_dosis' => $datos['proxima_dosis'] ?? null,
        ':observaciones' => $datos['observaciones'] ?? null
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}

function obtenerTiposProductos()
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_tipo,
            estado
        FROM tipos_productos
        WHERE estado = TRUE
        ORDER BY nombre_tipo
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}