<?php

require_once __DIR__ . '/../../../config/database.php';


function obtenerConfiguracion(): array|false {
    $conexion = conectarDB();

    $sql = "
        SELECT
            id,
            nombre_refugio,
            direccion,
            telefono,
            email_contacto,
            horario_atencion,
            alias_mercado_pago,
            cbu,
            titular_cuenta,
            banco,
            whatsapp,
            instagram,
            fecha_actualizacion
        FROM configuraciones
        WHERE id = 1
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute();

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function actualizarConfiguracion(array $datos): array|false {
    $conexion = conectarDB();
    $sql = "
        UPDATE configuraciones
        SET
            nombre_refugio = :nombre_refugio,
            direccion = :direccion,
            telefono = :telefono,
            email_contacto = :email_contacto,
            horario_atencion = :horario_atencion,
            alias_mercado_pago = :alias_mercado_pago,
            cbu = :cbu,
            titular_cuenta = :titular_cuenta,
            banco = :banco,
            whatsapp = :whatsapp,
            instagram = :instagram,
            fecha_actualizacion = CURRENT_TIMESTAMP
        WHERE id = 1
        RETURNING *
    ";

    $consulta = $conexion->prepare($sql);
    $consulta->execute([
        ':nombre_refugio' => $datos['nombre_refugio'],
        ':direccion' => $datos['direccion'],
        ':telefono' => $datos['telefono'],
        ':email_contacto' => $datos['email_contacto'],
        ':horario_atencion' => $datos['horario_atencion'],
        ':alias_mercado_pago' => $datos['alias_mercado_pago'],
        ':cbu' => $datos['cbu'],
        ':titular_cuenta' => $datos['titular_cuenta'],
        ':banco' => $datos['banco'],
        ':whatsapp' => $datos['whatsapp'],
        ':instagram' => $datos['instagram']
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}