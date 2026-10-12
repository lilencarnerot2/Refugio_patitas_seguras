<?php

require_once __DIR__ . '/../../../config/database.php';


// ============================================================
// FORMULARIO DE TRÁNSITO
// ============================================================

function obtener_formulario_transito_por_id(int $id): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            ft.id,
            ft.id_solicitud,
            ft.id_tipo_vivienda,
            tv.nombre_tipo AS tipo_vivienda,
            ft.id_condicion_vivienda,
            cv.descripcion AS condicion_vivienda,
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
        INNER JOIN tipos_vivienda tv
            ON tv.id = ft.id_tipo_vivienda
        INNER JOIN condiciones_vivienda cv
            ON cv.id = ft.id_condicion_vivienda
        WHERE ft.id = :id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function obtener_formulario_transito_por_solicitud_db(int $id_solicitud): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            ft.id,
            ft.id_solicitud,
            ft.id_tipo_vivienda,
            tv.nombre_tipo AS tipo_vivienda,
            ft.id_condicion_vivienda,
            cv.descripcion AS condicion_vivienda,
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
        INNER JOIN tipos_vivienda tv
            ON tv.id = ft.id_tipo_vivienda
        INNER JOIN condiciones_vivienda cv
            ON cv.id = ft.id_condicion_vivienda
        WHERE ft.id_solicitud = :id_solicitud
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_solicitud' => $id_solicitud]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function existe_formulario_transito_para_solicitud(int $id_solicitud): bool
{
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM formulario_hogar_transito
        WHERE id_solicitud = :id_solicitud
        LIMIT 1
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_solicitud' => $id_solicitud]);

    return (bool) $stmt->fetchColumn();
}


function crear_formulario_transito(array $datos): array|false
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO formulario_hogar_transito (
            id_solicitud,
            id_tipo_vivienda,
            id_condicion_vivienda,
            tiene_patio,
            metros_cuadrados_patio,
            cantidad_integrantes,
            hay_ninos,
            horas_solo,
            tiene_mascotas,
            cantidad_maxima_mascotas,
            puede_realizar_paseos,
            puede_realizar_traslados,
            puede_administrar_medicacion,
            cuenta_con_apoyo,
            limitaciones_cuidado,
            observaciones,
            disponibilidad_desde,
            disponibilidad_hasta
        )
        VALUES (
            :id_solicitud,
            :id_tipo_vivienda,
            :id_condicion_vivienda,
            :tiene_patio,
            :metros_cuadrados_patio,
            :cantidad_integrantes,
            :hay_ninos,
            :horas_solo,
            :tiene_mascotas,
            :cantidad_maxima_mascotas,
            :puede_realizar_paseos,
            :puede_realizar_traslados,
            :puede_administrar_medicacion,
            :cuenta_con_apoyo,
            :limitaciones_cuidado,
            :observaciones,
            :disponibilidad_desde,
            :disponibilidad_hasta
        )
        RETURNING id
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->bindValue(':id_solicitud', $datos['id_solicitud'], PDO::PARAM_INT);
    $stmt->bindValue(':id_tipo_vivienda', $datos['id_tipo_vivienda'], PDO::PARAM_INT);
    $stmt->bindValue(':id_condicion_vivienda', $datos['id_condicion_vivienda'], PDO::PARAM_INT);
    $stmt->bindValue(':tiene_patio', $datos['tiene_patio'] ? 't' : 'f', PDO::PARAM_STR);

    if ($datos['metros_cuadrados_patio'] === null) {
        $stmt->bindValue(':metros_cuadrados_patio', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':metros_cuadrados_patio', $datos['metros_cuadrados_patio'], PDO::PARAM_STR);
    }

    $stmt->bindValue(':cantidad_integrantes', $datos['cantidad_integrantes'], PDO::PARAM_INT);
    $stmt->bindValue(':hay_ninos', $datos['hay_ninos'] ? 't' : 'f', PDO::PARAM_STR);

    if ($datos['horas_solo'] === null) {
        $stmt->bindValue(':horas_solo', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':horas_solo', $datos['horas_solo'], PDO::PARAM_INT);
    }

    $stmt->bindValue(':tiene_mascotas', $datos['tiene_mascotas'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':cantidad_maxima_mascotas', $datos['cantidad_maxima_mascotas'], PDO::PARAM_INT);
    $stmt->bindValue(':puede_realizar_paseos', $datos['puede_realizar_paseos'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':puede_realizar_traslados', $datos['puede_realizar_traslados'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':puede_administrar_medicacion', $datos['puede_administrar_medicacion'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':cuenta_con_apoyo', $datos['cuenta_con_apoyo'] ? 't' : 'f', PDO::PARAM_STR);

    if ($datos['limitaciones_cuidado'] === null) {
        $stmt->bindValue(':limitaciones_cuidado', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':limitaciones_cuidado', $datos['limitaciones_cuidado'], PDO::PARAM_STR);
    }

    if ($datos['observaciones'] === null) {
        $stmt->bindValue(':observaciones', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':observaciones', $datos['observaciones'], PDO::PARAM_STR);
    }

    $stmt->bindValue(':disponibilidad_desde', $datos['disponibilidad_desde'], PDO::PARAM_STR);

    if ($datos['disponibilidad_hasta'] === null) {
        $stmt->bindValue(':disponibilidad_hasta', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':disponibilidad_hasta', $datos['disponibilidad_hasta'], PDO::PARAM_STR);
    }

    $stmt->execute();

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    return $fila ?: false;
}


function actualizar_formulario_transito(int $id, array $datos): array|false
{
    $conexion = conectarDB();

    $sql = "
        UPDATE formulario_hogar_transito
        SET
            id_tipo_vivienda = :id_tipo_vivienda,
            id_condicion_vivienda = :id_condicion_vivienda,
            tiene_patio = :tiene_patio,
            metros_cuadrados_patio = :metros_cuadrados_patio,
            cantidad_integrantes = :cantidad_integrantes,
            hay_ninos = :hay_ninos,
            horas_solo = :horas_solo,
            tiene_mascotas = :tiene_mascotas,
            cantidad_maxima_mascotas = :cantidad_maxima_mascotas,
            puede_realizar_paseos = :puede_realizar_paseos,
            puede_realizar_traslados = :puede_realizar_traslados,
            puede_administrar_medicacion = :puede_administrar_medicacion,
            cuenta_con_apoyo = :cuenta_con_apoyo,
            limitaciones_cuidado = :limitaciones_cuidado,
            observaciones = :observaciones,
            disponibilidad_desde = :disponibilidad_desde,
            disponibilidad_hasta = :disponibilidad_hasta
        WHERE id = :id
        RETURNING id
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':id_tipo_vivienda', $datos['id_tipo_vivienda'], PDO::PARAM_INT);
    $stmt->bindValue(':id_condicion_vivienda', $datos['id_condicion_vivienda'], PDO::PARAM_INT);
    $stmt->bindValue(':tiene_patio', $datos['tiene_patio'] ? 't' : 'f', PDO::PARAM_STR);

    if ($datos['metros_cuadrados_patio'] === null) {
        $stmt->bindValue(':metros_cuadrados_patio', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':metros_cuadrados_patio', $datos['metros_cuadrados_patio'], PDO::PARAM_STR);
    }

    $stmt->bindValue(':cantidad_integrantes', $datos['cantidad_integrantes'], PDO::PARAM_INT);
    $stmt->bindValue(':hay_ninos', $datos['hay_ninos'] ? 't' : 'f', PDO::PARAM_STR);

    if ($datos['horas_solo'] === null) {
        $stmt->bindValue(':horas_solo', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':horas_solo', $datos['horas_solo'], PDO::PARAM_INT);
    }

    $stmt->bindValue(':tiene_mascotas', $datos['tiene_mascotas'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':cantidad_maxima_mascotas', $datos['cantidad_maxima_mascotas'], PDO::PARAM_INT);
    $stmt->bindValue(':puede_realizar_paseos', $datos['puede_realizar_paseos'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':puede_realizar_traslados', $datos['puede_realizar_traslados'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':puede_administrar_medicacion', $datos['puede_administrar_medicacion'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':cuenta_con_apoyo', $datos['cuenta_con_apoyo'] ? 't' : 'f', PDO::PARAM_STR);

    if ($datos['limitaciones_cuidado'] === null) {
        $stmt->bindValue(':limitaciones_cuidado', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':limitaciones_cuidado', $datos['limitaciones_cuidado'], PDO::PARAM_STR);
    }

    if ($datos['observaciones'] === null) {
        $stmt->bindValue(':observaciones', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':observaciones', $datos['observaciones'], PDO::PARAM_STR);
    }

    $stmt->bindValue(':disponibilidad_desde', $datos['disponibilidad_desde'], PDO::PARAM_STR);

    if ($datos['disponibilidad_hasta'] === null) {
        $stmt->bindValue(':disponibilidad_hasta', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':disponibilidad_hasta', $datos['disponibilidad_hasta'], PDO::PARAM_STR);
    }

    $stmt->execute();

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    return $fila ?: false;
}


// ============================================================
// PIVOTES: CONDICIONES ESPECIALES (compartida con adopción)
// ============================================================

function guardar_condiciones_transito(int $id_formulario, array $ids_condiciones): void
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO condiciones_aceptadas_formulario (
            id_formulario_transito,
            id_condicion_especial
        )
        VALUES (
            :id_formulario,
            :id_condicion
        )
    ";

    $stmt = $conexion->prepare($sql);

    foreach ($ids_condiciones as $id_condicion) {
        $stmt->bindValue(':id_formulario', $id_formulario, PDO::PARAM_INT);
        $stmt->bindValue(':id_condicion', (int) $id_condicion, PDO::PARAM_INT);
        $stmt->execute();
    }
}


function eliminar_condiciones_transito(int $id_formulario): void
{
    $conexion = conectarDB();

    $sql = "
        DELETE FROM condiciones_aceptadas_formulario
        WHERE id_formulario_transito = :id_formulario
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_formulario' => $id_formulario]);
}


function obtener_condiciones_de_transito(int $id_formulario): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            ce.id,
            ce.descripcion
        FROM condiciones_aceptadas_formulario caf
        INNER JOIN condiciones_especiales ce
            ON ce.id = caf.id_condicion_especial
        WHERE caf.id_formulario_transito = :id_formulario
        ORDER BY ce.id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_formulario' => $id_formulario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// ============================================================
// PIVOTES: MASCOTAS EXISTENTES EN EL HOGAR (compartida)
// ============================================================

function guardar_mascotas_transito(int $id_formulario, array $mascotas): void
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO mascotas_existentes_hogar (
            id_formulario_transito,
            id_tipo_mascota,
            nombre,
            edad_aproximada,
            sexo,
            esterilizada,
            vacunas_al_dia,
            enfermedad_condicion,
            descripcion
        )
        VALUES (
            :id_formulario,
            :id_tipo_mascota,
            :nombre,
            :edad_aproximada,
            :sexo,
            :esterilizada,
            :vacunas_al_dia,
            :enfermedad_condicion,
            :descripcion
        )
    ";

    $stmt = $conexion->prepare($sql);

    foreach ($mascotas as $m) {
        $sexo = isset($m['sexo']) && $m['sexo'] !== null
            ? strtolower(trim((string) $m['sexo']))
            : null;

        if ($sexo !== null && !in_array($sexo, ['macho', 'hembra'], true)) {
            $sexo = null;
        }

        $stmt->bindValue(':id_formulario', $id_formulario, PDO::PARAM_INT);
        $stmt->bindValue(':id_tipo_mascota', (int) $m['id_tipo_mascota'], PDO::PARAM_INT);

        if (empty($m['nombre'])) {
            $stmt->bindValue(':nombre', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':nombre', $m['nombre'], PDO::PARAM_STR);
        }

        if (!isset($m['edad_aproximada']) || $m['edad_aproximada'] === null) {
            $stmt->bindValue(':edad_aproximada', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':edad_aproximada', (int) $m['edad_aproximada'], PDO::PARAM_INT);
        }

        if ($sexo === null) {
            $stmt->bindValue(':sexo', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':sexo', $sexo, PDO::PARAM_STR);
        }

        if (!isset($m['esterilizada']) || $m['esterilizada'] === null) {
            $stmt->bindValue(':esterilizada', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':esterilizada', $m['esterilizada'] ? 't' : 'f', PDO::PARAM_STR);
        }

        if (!isset($m['vacunas_al_dia']) || $m['vacunas_al_dia'] === null) {
            $stmt->bindValue(':vacunas_al_dia', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':vacunas_al_dia', $m['vacunas_al_dia'] ? 't' : 'f', PDO::PARAM_STR);
        }

        if (empty($m['enfermedad_condicion'])) {
            $stmt->bindValue(':enfermedad_condicion', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':enfermedad_condicion', $m['enfermedad_condicion'], PDO::PARAM_STR);
        }

        if (empty($m['descripcion'])) {
            $stmt->bindValue(':descripcion', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':descripcion', $m['descripcion'], PDO::PARAM_STR);
        }

        $stmt->execute();
    }
}


function eliminar_mascotas_transito(int $id_formulario): void
{
    $conexion = conectarDB();

    $sql = "
        DELETE FROM mascotas_existentes_hogar
        WHERE id_formulario_transito = :id_formulario
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_formulario' => $id_formulario]);
}


function obtener_mascotas_de_transito(int $id_formulario): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            meh.id,
            meh.id_tipo_mascota,
            tm.descripcion AS tipo_mascota,
            meh.nombre,
            meh.edad_aproximada,
            meh.sexo,
            meh.esterilizada,
            meh.vacunas_al_dia,
            meh.enfermedad_condicion,
            meh.descripcion
        FROM mascotas_existentes_hogar meh
        INNER JOIN tipos_mascotas tm
            ON tm.id = meh.id_tipo_mascota
        WHERE meh.id_formulario_transito = :id_formulario
        ORDER BY meh.id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_formulario' => $id_formulario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

