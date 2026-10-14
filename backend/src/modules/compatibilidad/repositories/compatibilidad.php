<?php

require_once __DIR__ . '/../../../config/database.php';


function obtener_solicitud_compatibilidad(int $id_solicitud): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            s.id,
            s.id_usuario,
            s.id_mascota,
            s.id_tipo_solicitud,
            ts.nombre_tipo AS tipo_solicitud,
            s.id_estado_solicitud,
            es.nombre_estado AS estado_solicitud
        FROM solicitudes s
        INNER JOIN tipos_solicitudes ts
            ON ts.id = s.id_tipo_solicitud
        INNER JOIN estados_solicitudes es
            ON es.id = s.id_estado_solicitud
        WHERE s.id = :id_solicitud
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_solicitud' => $id_solicitud]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function obtener_formulario_adopcion_por_solicitud(int $id_solicitud): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            fa.id,
            fa.id_solicitud,
            fa.id_tipo_vivienda,
            fa.id_condicion_vivienda,
            fa.tiene_patio,
            fa.metros_cuadrados_patio,
            fa.id_destino_mascota,
            fa.cantidad_integrantes,
            fa.hay_ninos,
            fa.horas_solo,
            fa.tiene_mascotas,
            fa.responsable_principal,
            fa.motivo_adopcion,
            fa.puede_realizar_paseos,
            fa.puede_realizar_traslados,
            fa.cuenta_con_apoyo,
            fa.limitaciones_cuidado,
            fa.experiencia_cuidado,
            fa.cuenta_con_recursos,
            fa.observaciones
        FROM formularios_adopcion fa
        WHERE fa.id_solicitud = :id_solicitud
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_solicitud' => $id_solicitud]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function obtener_formulario_transito_por_solicitud(int $id_solicitud): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            ft.id,
            ft.id_solicitud,
            ft.id_tipo_vivienda,
            ft.id_condicion_vivienda,
            ft.tiene_patio,
            ft.metros_cuadrados_patio,
            ft.cantidad_integrantes,
            ft.hay_ninos,
            ft.horas_solo,
            ft.tiene_mascotas,
            ft.cantidad_maxima_mascotas,
            ft.puede_realizar_paseos,
            ft.puede_realizar_traslados,
            ft.puede_administrar_medicacion,
            ft.cuenta_con_apoyo,
            ft.limitaciones_cuidado,
            ft.observaciones,
            ft.disponibilidad_desde,
            ft.disponibilidad_hasta
        FROM formulario_hogar_transito ft
        WHERE ft.id_solicitud = :id_solicitud
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_solicitud' => $id_solicitud]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function obtener_mascota_compatibilidad(int $id_mascota): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            m.id,
            m.nombre,
            m.id_especie,
            e.nombre_especie AS especie,
            m.id_raza,
            r.nombre_raza AS raza,
            m.tamaño AS tamano,
            m.sexo,
            m.fecha_nacimiento_estimada,
            m.historial,
            m.foto_url
        FROM mascotas m
        INNER JOIN especies e
            ON e.id = m.id_especie
        LEFT JOIN razas r
            ON r.id = m.id_raza
            AND r.id_especie = m.id_especie
        WHERE m.id = :id_mascota
          AND m.estado = TRUE
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_mascota' => $id_mascota]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}