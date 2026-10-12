<?php

require_once __DIR__ . '/../../../config/database.php';


// ============================================================
// CATÁLOGOS
// ============================================================

function obtener_compromisos_adopcion(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT id, descripcion, estado
        FROM compromisos_adopcion
        WHERE estado = TRUE
        ORDER BY id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_condiciones_especiales(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT id, descripcion, estado
        FROM condiciones_especiales
        WHERE estado = TRUE
        ORDER BY id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_tipos_mascotas(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT id, descripcion, estado
        FROM tipos_mascotas
        WHERE estado = TRUE
        ORDER BY id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// ============================================================
// FORMULARIO DE ADOPCIÓN
// ============================================================

function obtener_formulario_adopcion_por_id(int $id): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            fa.id,
            fa.id_solicitud,
            fa.id_tipo_vivienda,
            tv.nombre_tipo AS tipo_vivienda,
            fa.id_condicion_vivienda,
            cv.descripcion AS condicion_vivienda,
            fa.tiene_patio,
            fa.metros_cuadrados_patio,
            fa.id_destino_mascota,
            dm.descripcion AS destino_mascota,
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
        INNER JOIN tipos_vivienda tv
            ON tv.id = fa.id_tipo_vivienda
        INNER JOIN condiciones_vivienda cv
            ON cv.id = fa.id_condicion_vivienda
        INNER JOIN destinos_mascotas dm
            ON dm.id = fa.id_destino_mascota
        WHERE fa.id = :id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);

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
            tv.nombre_tipo AS tipo_vivienda,
            fa.id_condicion_vivienda,
            cv.descripcion AS condicion_vivienda,
            fa.tiene_patio,
            fa.metros_cuadrados_patio,
            fa.id_destino_mascota,
            dm.descripcion AS destino_mascota,
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
        INNER JOIN tipos_vivienda tv
            ON tv.id = fa.id_tipo_vivienda
        INNER JOIN condiciones_vivienda cv
            ON cv.id = fa.id_condicion_vivienda
        INNER JOIN destinos_mascotas dm
            ON dm.id = fa.id_destino_mascota
        WHERE fa.id_solicitud = :id_solicitud
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_solicitud' => $id_solicitud]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function obtener_datos_solicitud(int $id_solicitud): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            s.id,
            s.id_usuario,
            s.id_mascota,
            s.id_tipo_solicitud,
            s.id_estado_solicitud,
            es.nombre_estado AS estado_solicitud
        FROM solicitudes s
        INNER JOIN estados_solicitudes es
            ON es.id = s.id_estado_solicitud
        WHERE s.id = :id_solicitud
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_solicitud' => $id_solicitud]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function existe_formulario_para_solicitud(int $id_solicitud): bool
{
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM formularios_adopcion
        WHERE id_solicitud = :id_solicitud
        LIMIT 1
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_solicitud' => $id_solicitud]);

    return (bool) $stmt->fetchColumn();
}


function existe_tipo_vivienda(int $id): bool
{
    $conexion = conectarDB();
    $sql = "SELECT 1 FROM tipos_vivienda WHERE id = :id AND estado = TRUE LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
    return (bool) $stmt->fetchColumn();
}


function existe_condicion_vivienda(int $id): bool
{
    $conexion = conectarDB();
    $sql = "SELECT 1 FROM condiciones_vivienda WHERE id = :id AND estado = TRUE LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
    return (bool) $stmt->fetchColumn();
}


function existe_destino_mascota(int $id): bool
{
    $conexion = conectarDB();
    $sql = "SELECT 1 FROM destinos_mascotas WHERE id = :id AND estado = TRUE LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
    return (bool) $stmt->fetchColumn();
}


function existe_compromiso_adopcion(int $id): bool
{
    $conexion = conectarDB();
    $sql = "SELECT 1 FROM compromisos_adopcion WHERE id = :id AND estado = TRUE LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
    return (bool) $stmt->fetchColumn();
}


function existe_condicion_especial(int $id): bool
{
    $conexion = conectarDB();
    $sql = "SELECT 1 FROM condiciones_especiales WHERE id = :id AND estado = TRUE LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
    return (bool) $stmt->fetchColumn();
}


function existe_tipo_mascota(int $id): bool
{
    $conexion = conectarDB();
    $sql = "SELECT 1 FROM tipos_mascotas WHERE id = :id AND estado = TRUE LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
    return (bool) $stmt->fetchColumn();
}


function crear_formulario_adopcion(array $datos): array|false
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO formularios_adopcion (
            id_solicitud,
            id_tipo_vivienda,
            id_condicion_vivienda,
            tiene_patio,
            metros_cuadrados_patio,
            id_destino_mascota,
            cantidad_integrantes,
            hay_ninos,
            horas_solo,
            tiene_mascotas,
            responsable_principal,
            motivo_adopcion,
            puede_realizar_paseos,
            puede_realizar_traslados,
            cuenta_con_apoyo,
            limitaciones_cuidado,
            experiencia_cuidado,
            cuenta_con_recursos,
            observaciones
        )
        VALUES (
            :id_solicitud,
            :id_tipo_vivienda,
            :id_condicion_vivienda,
            :tiene_patio,
            :metros_cuadrados_patio,
            :id_destino_mascota,
            :cantidad_integrantes,
            :hay_ninos,
            :horas_solo,
            :tiene_mascotas,
            :responsable_principal,
            :motivo_adopcion,
            :puede_realizar_paseos,
            :puede_realizar_traslados,
            :cuenta_con_apoyo,
            :limitaciones_cuidado,
            :experiencia_cuidado,
            :cuenta_con_recursos,
            :observaciones
        )
        RETURNING id
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->bindValue(':id_solicitud', $datos['id_solicitud'], PDO::PARAM_INT);
    $stmt->bindValue(':id_tipo_vivienda', $datos['id_tipo_vivienda'], PDO::PARAM_INT);
    $stmt->bindValue(':id_condicion_vivienda', $datos['id_condicion_vivienda'], PDO::PARAM_INT);
    $stmt->bindValue(':tiene_patio', $datos['tiene_patio'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':metros_cuadrados_patio', $datos['metros_cuadrados_patio'], $datos['metros_cuadrados_patio'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':id_destino_mascota', $datos['id_destino_mascota'], PDO::PARAM_INT);
    $stmt->bindValue(':cantidad_integrantes', $datos['cantidad_integrantes'], PDO::PARAM_INT);
    $stmt->bindValue(':hay_ninos', $datos['hay_ninos'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':horas_solo', $datos['horas_solo'], $datos['horas_solo'] === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':tiene_mascotas', $datos['tiene_mascotas'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':responsable_principal', $datos['responsable_principal'], PDO::PARAM_STR);
    $stmt->bindValue(':motivo_adopcion', $datos['motivo_adopcion'], $datos['motivo_adopcion'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':puede_realizar_paseos', $datos['puede_realizar_paseos'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':puede_realizar_traslados', $datos['puede_realizar_traslados'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':cuenta_con_apoyo', $datos['cuenta_con_apoyo'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':limitaciones_cuidado', $datos['limitaciones_cuidado'], $datos['limitaciones_cuidado'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':experiencia_cuidado', $datos['experiencia_cuidado'], $datos['experiencia_cuidado'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

    if ($datos['cuenta_con_recursos'] === null) {
        $stmt->bindValue(':cuenta_con_recursos', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':cuenta_con_recursos', $datos['cuenta_con_recursos'] ? 't' : 'f', PDO::PARAM_STR);
    }

    $stmt->bindValue(':observaciones', $datos['observaciones'], $datos['observaciones'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

    $stmt->execute();

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    return $fila ?: false;
}

function actualizar_formulario_adopcion(int $id, array $datos): array|false
{
    $conexion = conectarDB();

    $sql = "
        UPDATE formularios_adopcion
        SET
            id_tipo_vivienda = :id_tipo_vivienda,
            id_condicion_vivienda = :id_condicion_vivienda,
            tiene_patio = :tiene_patio,
            metros_cuadrados_patio = :metros_cuadrados_patio,
            id_destino_mascota = :id_destino_mascota,
            cantidad_integrantes = :cantidad_integrantes,
            hay_ninos = :hay_ninos,
            horas_solo = :horas_solo,
            tiene_mascotas = :tiene_mascotas,
            responsable_principal = :responsable_principal,
            motivo_adopcion = :motivo_adopcion,
            puede_realizar_paseos = :puede_realizar_paseos,
            puede_realizar_traslados = :puede_realizar_traslados,
            cuenta_con_apoyo = :cuenta_con_apoyo,
            limitaciones_cuidado = :limitaciones_cuidado,
            experiencia_cuidado = :experiencia_cuidado,
            cuenta_con_recursos = :cuenta_con_recursos,
            observaciones = :observaciones
        WHERE id = :id
        RETURNING id
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':id_tipo_vivienda', $datos['id_tipo_vivienda'], PDO::PARAM_INT);
    $stmt->bindValue(':id_condicion_vivienda', $datos['id_condicion_vivienda'], PDO::PARAM_INT);
    $stmt->bindValue(':tiene_patio', $datos['tiene_patio'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':metros_cuadrados_patio', $datos['metros_cuadrados_patio'], $datos['metros_cuadrados_patio'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':id_destino_mascota', $datos['id_destino_mascota'], PDO::PARAM_INT);
    $stmt->bindValue(':cantidad_integrantes', $datos['cantidad_integrantes'], PDO::PARAM_INT);
    $stmt->bindValue(':hay_ninos', $datos['hay_ninos'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':horas_solo', $datos['horas_solo'], $datos['horas_solo'] === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':tiene_mascotas', $datos['tiene_mascotas'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':responsable_principal', $datos['responsable_principal'], PDO::PARAM_STR);
    $stmt->bindValue(':motivo_adopcion', $datos['motivo_adopcion'], $datos['motivo_adopcion'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':puede_realizar_paseos', $datos['puede_realizar_paseos'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':puede_realizar_traslados', $datos['puede_realizar_traslados'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':cuenta_con_apoyo', $datos['cuenta_con_apoyo'] ? 't' : 'f', PDO::PARAM_STR);
    $stmt->bindValue(':limitaciones_cuidado', $datos['limitaciones_cuidado'], $datos['limitaciones_cuidado'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':experiencia_cuidado', $datos['experiencia_cuidado'], $datos['experiencia_cuidado'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

    if ($datos['cuenta_con_recursos'] === null) {
        $stmt->bindValue(':cuenta_con_recursos', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':cuenta_con_recursos', $datos['cuenta_con_recursos'] ? 't' : 'f', PDO::PARAM_STR);
    }

    $stmt->bindValue(':observaciones', $datos['observaciones'], $datos['observaciones'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);

    $stmt->execute();

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    return $fila ?: false;
}

// ============================================================
// PIVOTES: COMPROMISOS
// ============================================================

function guardar_compromisos_formulario(int $id_formulario, array $ids_compromisos): void
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO formulario_adopcion_compromisos (
            id_formulario_adopcion,
            id_compromiso_adopcion
        )
        VALUES (
            :id_formulario,
            :id_compromiso
        )
    ";

    $stmt = $conexion->prepare($sql);

    foreach ($ids_compromisos as $id_compromiso) {
        $stmt->execute([
            ':id_formulario' => $id_formulario,
            ':id_compromiso' => $id_compromiso
        ]);
    }
}


function eliminar_compromisos_formulario(int $id_formulario): void
{
    $conexion = conectarDB();

    $sql = "
        DELETE FROM formulario_adopcion_compromisos
        WHERE id_formulario_adopcion = :id_formulario
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_formulario' => $id_formulario]);
}


function obtener_compromisos_de_formulario(int $id_formulario): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            ca.id,
            ca.descripcion
        FROM formulario_adopcion_compromisos fac
        INNER JOIN compromisos_adopcion ca
            ON ca.id = fac.id_compromiso_adopcion
        WHERE fac.id_formulario_adopcion = :id_formulario
        ORDER BY ca.id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_formulario' => $id_formulario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// ============================================================
// PIVOTES: CONDICIONES ESPECIALES
// ============================================================

function guardar_condiciones_formulario(int $id_formulario, array $ids_condiciones): void
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO condiciones_aceptadas_formulario (
            id_formulario_adopcion,
            id_condicion_especial
        )
        VALUES (
            :id_formulario,
            :id_condicion
        )
    ";

    $stmt = $conexion->prepare($sql);

    foreach ($ids_condiciones as $id_condicion) {
        $stmt->execute([
            ':id_formulario' => $id_formulario,
            ':id_condicion' => $id_condicion
        ]);
    }
}


function eliminar_condiciones_formulario(int $id_formulario): void
{
    $conexion = conectarDB();

    $sql = "
        DELETE FROM condiciones_aceptadas_formulario
        WHERE id_formulario_adopcion = :id_formulario
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_formulario' => $id_formulario]);
}


function obtener_condiciones_de_formulario(int $id_formulario): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            ce.id,
            ce.descripcion
        FROM condiciones_aceptadas_formulario caf
        INNER JOIN condiciones_especiales ce
            ON ce.id = caf.id_condicion_especial
        WHERE caf.id_formulario_adopcion = :id_formulario
        ORDER BY ce.id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_formulario' => $id_formulario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// ============================================================
// PIVOTES: MASCOTAS EXISTENTES EN EL HOGAR
// ============================================================

function guardar_mascotas_hogar(int $id_formulario, array $mascotas): void
{
    $conexion = conectarDB();

    $sql = "
        INSERT INTO mascotas_existentes_hogar (
            id_formulario_adopcion,
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

        // Solo permitir los valores del enum
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


function eliminar_mascotas_hogar(int $id_formulario): void
{
    $conexion = conectarDB();

    $sql = "
        DELETE FROM mascotas_existentes_hogar
        WHERE id_formulario_adopcion = :id_formulario
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_formulario' => $id_formulario]);
}


function obtener_mascotas_de_formulario(int $id_formulario): array
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
        WHERE meh.id_formulario_adopcion = :id_formulario
        ORDER BY meh.id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id_formulario' => $id_formulario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
} 
    
      function obtener_tipo_solicitud_por_id(int $id): array|false
{
    $conexion = conectarDB();

    $sql = "
        SELECT id, nombre_tipo
        FROM tipos_solicitudes
        WHERE id = :id
          AND estado = TRUE
        LIMIT 1
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function obtener_condiciones_vivienda(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT id, descripcion, estado
        FROM condiciones_vivienda
        WHERE estado = TRUE
        ORDER BY id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_destinos_mascotas(): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT id, descripcion, estado
        FROM destinos_mascotas
        WHERE estado = TRUE
        ORDER BY id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtener_formularios_adopcion_filtrados(array $filtros, ?int $id_usuario_solo_suyos): array
{
    $conexion = conectarDB();

    $sql = "
        SELECT
            fa.id,
            fa.id_solicitud,
            s.fecha_solicitud,
            s.id_usuario,
            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            s.id_mascota,
            m.nombre AS nombre_mascota,
            s.id_tipo_solicitud,
            ts.nombre_tipo AS tipo_solicitud,
            s.id_estado_solicitud,
            es.nombre_estado AS estado_solicitud,
            fa.id_tipo_vivienda,
            tv.nombre_tipo AS tipo_vivienda,
            fa.id_condicion_vivienda,
            cv.descripcion AS condicion_vivienda,
            fa.tiene_patio,
            fa.metros_cuadrados_patio,
            fa.id_destino_mascota,
            dm.descripcion AS destino_mascota,
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
        INNER JOIN solicitudes s
            ON s.id = fa.id_solicitud
        INNER JOIN usuarios u
            ON u.id = s.id_usuario
        INNER JOIN mascotas m
            ON m.id = s.id_mascota
        INNER JOIN tipos_solicitudes ts
            ON ts.id = s.id_tipo_solicitud
        INNER JOIN estados_solicitudes es
            ON es.id = s.id_estado_solicitud
        INNER JOIN tipos_vivienda tv
            ON tv.id = fa.id_tipo_vivienda
        INNER JOIN condiciones_vivienda cv
            ON cv.id = fa.id_condicion_vivienda
        INNER JOIN destinos_mascotas dm
            ON dm.id = fa.id_destino_mascota
        WHERE 1 = 1
    ";

    $parametros = [];

    if ($id_usuario_solo_suyos !== null) {
        $sql .= " AND s.id_usuario = :id_usuario";
        $parametros[':id_usuario'] = $id_usuario_solo_suyos;
    }

    if (!empty($filtros['estado'])) {
        $sql .= " AND LOWER(TRIM(es.nombre_estado)) = LOWER(TRIM(:estado))";
        $parametros[':estado'] = $filtros['estado'];
    }

    if (!empty($filtros['id_tipo_vivienda'])) {
        $sql .= " AND fa.id_tipo_vivienda = :id_tipo_vivienda";
        $parametros[':id_tipo_vivienda'] = (int) $filtros['id_tipo_vivienda'];
    }

    if (!empty($filtros['id_condicion_vivienda'])) {
        $sql .= " AND fa.id_condicion_vivienda = :id_condicion_vivienda";
        $parametros[':id_condicion_vivienda'] = (int) $filtros['id_condicion_vivienda'];
    }

    if (isset($filtros['hay_ninos']) && $filtros['hay_ninos'] !== '') {
        $sql .= " AND fa.hay_ninos = :hay_ninos";
        $parametros[':hay_ninos'] = ($filtros['hay_ninos'] === 'true' || $filtros['hay_ninos'] === true) ? 't' : 'f';
    }

    if (isset($filtros['tiene_mascotas']) && $filtros['tiene_mascotas'] !== '') {
        $sql .= " AND fa.tiene_mascotas = :tiene_mascotas";
        $parametros[':tiene_mascotas'] = ($filtros['tiene_mascotas'] === 'true' || $filtros['tiene_mascotas'] === true) ? 't' : 'f';
    }

    if (!empty($filtros['busqueda'])) {
        $sql .= " AND (
            LOWER(u.nombre) LIKE LOWER(:busqueda)
            OR LOWER(u.apellido) LIKE LOWER(:busqueda)
            OR LOWER(m.nombre) LIKE LOWER(:busqueda)
        )";
        $parametros[':busqueda'] = '%' . $filtros['busqueda'] . '%';
    }

    $sql .= " ORDER BY s.fecha_solicitud DESC, fa.id DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->execute($parametros);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}