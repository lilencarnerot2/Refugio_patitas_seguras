<?php

require_once __DIR__ . '/../config/database.php';

function obtenerEspecies()
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_especie
        FROM especies
        WHERE estado = TRUE
        ORDER BY nombre_especie
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function existeEspecie($idEspecie)
{
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM especies
        WHERE id = :id
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $idEspecie
    ]);

    return (bool)$consulta->fetchColumn();
}