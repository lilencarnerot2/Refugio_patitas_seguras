<?php

require_once __DIR__ . '/../config/database.php';

function obtenerRazasPorEspecie($idEspecie)
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            id_especie,
            nombre_raza
        FROM razas
        WHERE id_especie = :id_especie
          AND estado = TRUE
        ORDER BY nombre_raza
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_especie' => $idEspecie
    ]);

    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function existeRazaParaEspecie($idRaza, $idEspecie){
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM razas
        WHERE id = :id_raza
          AND id_especie = :id_especie
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_raza' => $idRaza,
        ':id_especie' => $idEspecie
    ]);

    return (bool)$consulta->fetchColumn();
}