<?php

require_once __DIR__ . '/../../../config/database.php';

require_once __DIR__ . '/domicilios_catalogos.php';
require_once __DIR__ . '/domicilios_barrios.php';
require_once __DIR__ . '/domicilios_detalles.php';
require_once __DIR__ . '/domicilios_usuarios.php';
require_once __DIR__ . '/domicilios_consultas.php';


function crear_domicilio_usuario(
    int $id_usuario,
    array $datos
): array {

    $conexion = conectarDB();

    try {

        $conexion->beginTransaction();

        validar_tipo_vivienda_domicilio(
            $conexion,
            (int) $datos['id_tipo_vivienda']
        );

        validar_localidad_domicilio(
            $conexion,
            (int) $datos['id_localidad']
        );

        $id_barrio_zona = obtener_o_crear_barrio_zona(
            $conexion,
            (int) $datos['id_localidad'],
            $datos['nombre_barrio']
        );

        $id_domicilio_detalle = crear_detalle_domicilio(
            $conexion,
            $id_barrio_zona,
            $datos
        );

        $domicilio = crear_usuario_domicilio(
            $conexion,
            $id_domicilio_detalle,
            $datos['codigo_postal'] ?? null
        );

        asociar_domicilio_a_usuario(
            $conexion,
            $id_usuario,
            (int) $domicilio['id']
        );

        $conexion->commit();

        return [
            'id' => (int) $domicilio['id'],
            'id_domicilio_detalle' => $id_domicilio_detalle,
            'id_barrio_zona' => $id_barrio_zona,
            'codigo_postal' => $domicilio['codigo_postal']
        ];

    } catch (Throwable $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        throw $e;
    }
}


function actualizar_domicilio_usuario(
    int $id_usuario,
    array $datos
): array|false {

    $conexion = conectarDB();

    try {

        $conexion->beginTransaction();

        $domicilio_actual =
            obtener_referencia_domicilio_usuario(
                $conexion,
                $id_usuario
            );

        if (!$domicilio_actual) {
            $conexion->rollBack();
            return false;
        }

        validar_tipo_vivienda_domicilio(
            $conexion,
            (int) $datos['id_tipo_vivienda']
        );

        validar_localidad_domicilio(
            $conexion,
            (int) $datos['id_localidad']
        );

        $id_barrio_zona = obtener_o_crear_barrio_zona(
            $conexion,
            (int) $datos['id_localidad'],
            $datos['nombre_barrio']
        );

        actualizar_detalle_domicilio(
            $conexion,
            (int) $domicilio_actual['id_domicilio_detalle'],
            $id_barrio_zona,
            $datos
        );

        actualizar_codigo_postal_domicilio(
            $conexion,
            (int) $domicilio_actual['id_domicilio'],
            $datos['codigo_postal'] ?? null
        );

        $conexion->commit();

        return obtener_domicilio_por_usuario(
            $id_usuario
        );

    } catch (Throwable $e) {

        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        throw $e;
    }
}