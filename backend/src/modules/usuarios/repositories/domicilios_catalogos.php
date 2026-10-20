<?php

function validar_tipo_vivienda_domicilio(
    PDO $conexion,
    int $id_tipo_vivienda
): void {

    $sql = "
        SELECT id
        FROM tipos_vivienda
        WHERE id = :id
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id_tipo_vivienda
    ]);

    if (!$consulta->fetch(PDO::FETCH_ASSOC)) {
        throw new InvalidArgumentException(
            'El tipo de vivienda no es válido.'
        );
    }
}


function validar_localidad_domicilio(
    PDO $conexion,
    int $id_localidad
): void {

    $sql = "
        SELECT id
        FROM localidades
        WHERE id = :id
          AND estado = TRUE
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id_localidad
    ]);

    if (!$consulta->fetch(PDO::FETCH_ASSOC)) {
        throw new InvalidArgumentException(
            'La localidad no es válida.'
        );
    }
}