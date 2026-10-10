<?php

require_once __DIR__ . '/../repositories/formularios_transito.php';
require_once __DIR__ . '/../../adopciones/repositories/formularios_adopcion.php';


// ============================================================
// VALIDACIONES AUXILIARES
// ============================================================

function validar_solicitud_para_transito(int $id_solicitud, int $id_usuario_actual, bool $es_admin): array
{
    if ($id_solicitud <= 0) {
        throw new InvalidArgumentException(
            'El id de la solicitud no es válido.'
        );
    }

    $solicitud = obtener_datos_solicitud($id_solicitud);

    if (!$solicitud) {
        throw new InvalidArgumentException(
            'La solicitud no existe.'
        );
    }

    if (!$es_admin && (int) $solicitud['id_usuario'] !== $id_usuario_actual) {
        throw new InvalidArgumentException(
            'No podés operar sobre una solicitud que no es tuya.'
        );
    }

    // Verificar que la solicitud sea de tipo "Hogar de tránsito"
    $tipo = obtener_tipo_solicitud_por_id((int) $solicitud['id_tipo_solicitud']);

    if (!$tipo || strtolower(trim($tipo['nombre_tipo'])) !== 'hogar de tránsito') {
        throw new InvalidArgumentException(
            'Esta solicitud no es de tipo Hogar de tránsito.'
        );
    }

    return $solicitud;
}


function validar_solicitud_pendiente_transito(array $solicitud): void
{
    $estado = strtolower(trim((string) $solicitud['estado_solicitud']));

    if ($estado !== 'pendiente') {
        throw new InvalidArgumentException(
            'Solo se puede crear o modificar el formulario mientras la solicitud está pendiente.'
        );
    }
}


function validar_fecha(string $fecha): bool
{
    $d = DateTime::createFromFormat('Y-m-d', $fecha);
    return $d && $d->format('Y-m-d') === $fecha;
}


function validar_formulario_transito_datos(array $datos): void
{
    if (empty($datos['id_tipo_vivienda']) || !existe_tipo_vivienda((int) $datos['id_tipo_vivienda'])) {
        throw new InvalidArgumentException('El tipo de vivienda no es válido.');
    }

    if (empty($datos['id_condicion_vivienda']) || !existe_condicion_vivienda((int) $datos['id_condicion_vivienda'])) {
        throw new InvalidArgumentException('La condición de vivienda no es válida.');
    }

    if (!isset($datos['tiene_patio']) || !is_bool($datos['tiene_patio'])) {
        throw new InvalidArgumentException('Debe indicar si tiene patio.');
    }

    if ($datos['tiene_patio'] === true) {
        if (!isset($datos['metros_cuadrados_patio']) || $datos['metros_cuadrados_patio'] === null) {
            throw new InvalidArgumentException('Si tiene patio, debe indicar los metros cuadrados.');
        }
        if (!is_numeric($datos['metros_cuadrados_patio']) || (float) $datos['metros_cuadrados_patio'] < 0) {
            throw new InvalidArgumentException('Los metros cuadrados del patio no son válidos.');
        }
    }

    if (empty($datos['cantidad_integrantes']) || (int) $datos['cantidad_integrantes'] <= 0) {
        throw new InvalidArgumentException('La cantidad de integrantes debe ser mayor a 0.');
    }

    if (!isset($datos['hay_ninos']) || !is_bool($datos['hay_ninos'])) {
        throw new InvalidArgumentException('Debe indicar si hay niños en el hogar.');
    }

    if (isset($datos['horas_solo']) && $datos['horas_solo'] !== null) {
        $horas = (int) $datos['horas_solo'];
        if ($horas < 0 || $horas > 24) {
            throw new InvalidArgumentException('Las horas que la mascota quedaría sola deben estar entre 0 y 24.');
        }
    }

    if (!isset($datos['tiene_mascotas']) || !is_bool($datos['tiene_mascotas'])) {
        throw new InvalidArgumentException('Debe indicar si tiene mascotas.');
    }

    if (empty($datos['cantidad_maxima_mascotas']) || (int) $datos['cantidad_maxima_mascotas'] <= 0) {
        throw new InvalidArgumentException('La cantidad máxima de mascotas debe ser mayor a 0.');
    }

    if (!isset($datos['puede_realizar_paseos']) || !is_bool($datos['puede_realizar_paseos'])) {
        throw new InvalidArgumentException('Debe indicar si puede realizar paseos.');
    }

    if (!isset($datos['puede_realizar_traslados']) || !is_bool($datos['puede_realizar_traslados'])) {
        throw new InvalidArgumentException('Debe indicar si puede realizar traslados.');
    }

    if (!isset($datos['puede_administrar_medicacion']) || !is_bool($datos['puede_administrar_medicacion'])) {
        throw new InvalidArgumentException('Debe indicar si puede administrar medicación.');
    }

    if (!isset($datos['cuenta_con_apoyo']) || !is_bool($datos['cuenta_con_apoyo'])) {
        throw new InvalidArgumentException('Debe indicar si cuenta con apoyo.');
    }

    if (empty($datos['disponibilidad_desde']) || !validar_fecha((string) $datos['disponibilidad_desde'])) {
        throw new InvalidArgumentException('La fecha de disponibilidad desde es obligatoria y debe tener formato YYYY-MM-DD.');
    }

    if (empty($datos['disponibilidad_hasta']) || !validar_fecha((string) $datos['disponibilidad_hasta'])) {
        throw new InvalidArgumentException('La fecha de disponibilidad hasta es obligatoria y debe tener formato YYYY-MM-DD.');
    }

    if ($datos['disponibilidad_hasta'] <= $datos['disponibilidad_desde']) {
        throw new InvalidArgumentException('La fecha "hasta" debe ser posterior a la fecha "desde".');
    }
}


function validar_condiciones_transito(array $ids_condiciones): void
{
    if (!is_array($ids_condiciones)) {
        throw new InvalidArgumentException('Las condiciones especiales deben ser una lista.');
    }

    foreach ($ids_condiciones as $id) {
        if (!existe_condicion_especial((int) $id)) {
            throw new InvalidArgumentException(
                'Una de las condiciones especiales seleccionadas no es válida.'
            );
        }
    }
}


function validar_mascotas_transito(array $mascotas, bool $tiene_mascotas): void
{
    if (!is_array($mascotas)) {
        throw new InvalidArgumentException('Las mascotas del hogar deben ser una lista.');
    }

    if ($tiene_mascotas === true && count($mascotas) === 0) {
        throw new InvalidArgumentException(
            'Si indicaste que tenés mascotas, debés declarar al menos una.'
        );
    }

    foreach ($mascotas as $m) {
        if (empty($m['id_tipo_mascota']) || !existe_tipo_mascota((int) $m['id_tipo_mascota'])) {
            throw new InvalidArgumentException(
                'Una de las mascotas del hogar tiene un tipo inválido.'
            );
        }

        if (isset($m['edad_aproximada']) && $m['edad_aproximada'] !== null) {
            if ((int) $m['edad_aproximada'] < 0) {
                throw new InvalidArgumentException(
                    'La edad aproximada de una mascota no puede ser negativa.'
                );
            }
        }

        if (!empty($m['nombre']) && mb_strlen((string) $m['nombre']) > 30) {
            throw new InvalidArgumentException(
                'El nombre de una mascota del hogar no puede superar los 30 caracteres.'
            );
        }
    }
}


// ============================================================
// OPERACIONES
// ============================================================

function obtener_formulario_transito(int $id, int $id_usuario_actual, bool $es_admin): array
{
    if ($id <= 0) {
        throw new InvalidArgumentException('El id del formulario no es válido.');
    }

    $formulario = obtener_formulario_transito_por_id($id);

    if (!$formulario) {
        throw new RuntimeException('Formulario no encontrado.');
    }

    $solicitud = obtener_datos_solicitud((int) $formulario['id_solicitud']);

    if (!$es_admin && (int) $solicitud['id_usuario'] !== $id_usuario_actual) {
        throw new InvalidArgumentException('No tenés permiso para ver este formulario.');
    }

    $formulario['condiciones_especiales'] = obtener_condiciones_de_transito($id);
    $formulario['mascotas_hogar'] = obtener_mascotas_de_transito($id);

    return $formulario;
}


function obtener_formulario_transito_por_solicitud(int $id_solicitud, int $id_usuario_actual, bool $es_admin): array
{
    validar_solicitud_para_transito($id_solicitud, $id_usuario_actual, $es_admin);

    $formulario = obtener_formulario_transito_por_solicitud_db($id_solicitud);

    if (!$formulario) {
        throw new RuntimeException('Esta solicitud todavía no tiene formulario de tránsito.');
    }

    $id_formulario = (int) $formulario['id'];

    $formulario['condiciones_especiales'] = obtener_condiciones_de_transito($id_formulario);
    $formulario['mascotas_hogar'] = obtener_mascotas_de_transito($id_formulario);

    return $formulario;
}


function registrar_formulario_transito(int $id_usuario_actual, array $datos): array
{
    $id_solicitud = filter_var($datos['id_solicitud'] ?? null, FILTER_VALIDATE_INT);

    if ($id_solicitud === false || $id_solicitud <= 0) {
        throw new InvalidArgumentException('El id de la solicitud no es válido.');
    }

    $solicitud = validar_solicitud_para_transito($id_solicitud, $id_usuario_actual, false);
    validar_solicitud_pendiente_transito($solicitud);

    if (existe_formulario_transito_para_solicitud($id_solicitud)) {
        throw new InvalidArgumentException(
            'Esta solicitud ya tiene un formulario de tránsito. Usá PUT para modificarlo.'
        );
    }

    validar_formulario_transito_datos($datos);
    validar_condiciones_transito($datos['condiciones_especiales'] ?? []);
    validar_mascotas_transito($datos['mascotas_hogar'] ?? [], (bool) $datos['tiene_mascotas']);

    $datosInsert = [
        'id_solicitud' => $id_solicitud,
        'id_tipo_vivienda' => (int) $datos['id_tipo_vivienda'],
        'id_condicion_vivienda' => (int) $datos['id_condicion_vivienda'],
        'tiene_patio' => (bool) $datos['tiene_patio'],
        'metros_cuadrados_patio' => $datos['tiene_patio'] ? (float) $datos['metros_cuadrados_patio'] : null,
        'cantidad_integrantes' => (int) $datos['cantidad_integrantes'],
        'hay_ninos' => (bool) $datos['hay_ninos'],
        'horas_solo' => isset($datos['horas_solo']) && $datos['horas_solo'] !== null ? (int) $datos['horas_solo'] : null,
        'tiene_mascotas' => (bool) $datos['tiene_mascotas'],
        'cantidad_maxima_mascotas' => (int) $datos['cantidad_maxima_mascotas'],
        'puede_realizar_paseos' => (bool) $datos['puede_realizar_paseos'],
        'puede_realizar_traslados' => (bool) $datos['puede_realizar_traslados'],
        'puede_administrar_medicacion' => (bool) $datos['puede_administrar_medicacion'],
        'cuenta_con_apoyo' => (bool) $datos['cuenta_con_apoyo'],
        'limitaciones_cuidado' => !empty($datos['limitaciones_cuidado']) ? trim((string) $datos['limitaciones_cuidado']) : null,
        'observaciones' => !empty($datos['observaciones']) ? trim((string) $datos['observaciones']) : null,
        'disponibilidad_desde' => (string) $datos['disponibilidad_desde'],
        'disponibilidad_hasta' => (string) $datos['disponibilidad_hasta'],
    ];

    $creado = crear_formulario_transito($datosInsert);

    if (!$creado) {
        throw new RuntimeException('No se pudo crear el formulario de tránsito.');
    }

    $id_formulario = (int) $creado['id'];

    guardar_condiciones_transito($id_formulario, array_map('intval', $datos['condiciones_especiales'] ?? []));

    if (!empty($datos['mascotas_hogar'])) {
        guardar_mascotas_transito($id_formulario, $datos['mascotas_hogar']);
    }

    return obtener_formulario_transito($id_formulario, $id_usuario_actual, false);
}


function modificar_formulario_transito(int $id_formulario, int $id_usuario_actual, array $datos): array
{
    if ($id_formulario <= 0) {
        throw new InvalidArgumentException('El id del formulario no es válido.');
    }

    $formulario = obtener_formulario_transito_por_id($id_formulario);

    if (!$formulario) {
        throw new RuntimeException('Formulario no encontrado.');
    }

    $solicitud = validar_solicitud_para_transito(
        (int) $formulario['id_solicitud'],
        $id_usuario_actual,
        false
    );

    validar_solicitud_pendiente_transito($solicitud);

    validar_formulario_transito_datos($datos);
    validar_condiciones_transito($datos['condiciones_especiales'] ?? []);
    validar_mascotas_transito($datos['mascotas_hogar'] ?? [], (bool) $datos['tiene_mascotas']);

    $datosUpdate = [
        'id_tipo_vivienda' => (int) $datos['id_tipo_vivienda'],
        'id_condicion_vivienda' => (int) $datos['id_condicion_vivienda'],
        'tiene_patio' => (bool) $datos['tiene_patio'],
        'metros_cuadrados_patio' => $datos['tiene_patio'] ? (float) $datos['metros_cuadrados_patio'] : null,
        'cantidad_integrantes' => (int) $datos['cantidad_integrantes'],
        'hay_ninos' => (bool) $datos['hay_ninos'],
        'horas_solo' => isset($datos['horas_solo']) && $datos['horas_solo'] !== null ? (int) $datos['horas_solo'] : null,
        'tiene_mascotas' => (bool) $datos['tiene_mascotas'],
        'cantidad_maxima_mascotas' => (int) $datos['cantidad_maxima_mascotas'],
        'puede_realizar_paseos' => (bool) $datos['puede_realizar_paseos'],
        'puede_realizar_traslados' => (bool) $datos['puede_realizar_traslados'],
        'puede_administrar_medicacion' => (bool) $datos['puede_administrar_medicacion'],
        'cuenta_con_apoyo' => (bool) $datos['cuenta_con_apoyo'],
        'limitaciones_cuidado' => !empty($datos['limitaciones_cuidado']) ? trim((string) $datos['limitaciones_cuidado']) : null,
        'observaciones' => !empty($datos['observaciones']) ? trim((string) $datos['observaciones']) : null,
        'disponibilidad_desde' => (string) $datos['disponibilidad_desde'],
        'disponibilidad_hasta' => (string) $datos['disponibilidad_hasta'],
    ];

    $actualizado = actualizar_formulario_transito($id_formulario, $datosUpdate);

    if (!$actualizado) {
        throw new RuntimeException('No se pudo actualizar el formulario de tránsito.');
    }

    eliminar_condiciones_transito($id_formulario);
    eliminar_mascotas_transito($id_formulario);

    guardar_condiciones_transito($id_formulario, array_map('intval', $datos['condiciones_especiales'] ?? []));

    if (!empty($datos['mascotas_hogar'])) {
        guardar_mascotas_transito($id_formulario, $datos['mascotas_hogar']);
    }

    return obtener_formulario_transito($id_formulario, $id_usuario_actual, false);
}
