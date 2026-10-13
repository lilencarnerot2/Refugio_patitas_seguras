<?php

function crear_usuario_domicilio(
    PDO $conexion,
    int $id_domicilio_detalle,
    $codigo_postal
): array {

    $sql = "
        INSERT INTO usuarios_domicilios (
            id_domicilio_detalle,
            codigo_postal
        )
        VALUES (
            :id_domicilio_detalle,
            :codigo_postal
        )
        RETURNING
            id,
            id_domicilio_detalle,
            codigo_postal
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_domicilio_detalle' => $id_domicilio_detalle,
        ':codigo_postal' => $codigo_postal
    ]);

    $domicilio = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$domicilio) {
        throw new RuntimeException(
            'No se pudo crear el domicilio.'
        );
    }

    return $domicilio;
}


function asociar_domicilio_a_usuario(
    PDO $conexion,
    int $id_usuario,
    int $id_domicilio
): void {

    $sql = "
        UPDATE usuarios
        SET id_domicilio = :id_domicilio
        WHERE id = :id_usuario
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_domicilio' => $id_domicilio,
        ':id_usuario' => $id_usuario
    ]);

    if ($consulta->rowCount() === 0) {
        throw new RuntimeException(
            'No se pudo asociar el domicilio al usuario.'
        );
    }
}


function obtener_referencia_domicilio_usuario(
    PDO $conexion,
    int $id_usuario
): array|false {

    $sql = "
        SELECT
            ud.id AS id_domicilio,
            ud.id_domicilio_detalle
        FROM usuarios u
        INNER JOIN usuarios_domicilios ud
            ON ud.id = u.id_domicilio
        WHERE u.id = :id_usuario
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id_usuario' => $id_usuario
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function actualizar_codigo_postal_domicilio(
    PDO $conexion,
    int $id_domicilio,
    $codigo_postal
): void {

    $sql = "
        UPDATE usuarios_domicilios
        SET codigo_postal = :codigo_postal
        WHERE id = :id_domicilio
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':codigo_postal' => $codigo_postal,
        ':id_domicilio' => $id_domicilio
    ]);
}