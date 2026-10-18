<?php

require_once __DIR__ . '/../repositories/formularios_adopcion.php';
require_once __DIR__ . '/../../usuarios/services/usuarios.php';

// CATÁLOGOS

function listar_compromisos_adopcion(): array {
    return obtener_compromisos_adopcion();
}

function listar_condiciones_especiales(): array {
    return obtener_condiciones_especiales();
}

function listar_tipos_mascotas(): array {
    return obtener_tipos_mascotas();
}


// VALIDACIONES AUXILIARES
function validar_solicitud_para_formulario(int $id_solicitud, int $id_usuario_actual, bool $es_admin): array {
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

    return $solicitud;
}


function validar_solicitud_pendiente(array $solicitud): void {
    $estado = strtolower(trim((string) $solicitud['estado_solicitud']));
    if ($estado !== 'pendiente') {
        throw new InvalidArgumentException(
            'Solo se puede crear o modificar el formulario mientras la solicitud está pendiente.'
        );
    }
}


function validar_formulario_datos(array $datos): void {
    if (empty($datos['id_tipo_vivienda']) || !existe_tipo_vivienda((int) $datos['id_tipo_vivienda'])) {
        throw new InvalidArgumentException('El tipo de vivienda no es válido.');
    }

    if (empty($datos['id_condicion_vivienda']) || !existe_condicion_vivienda((int) $datos['id_condicion_vivienda'])) {
        throw new InvalidArgumentException('La condición de vivienda no es válida.');
    }

    if (empty($datos['id_destino_mascota']) || !existe_destino_mascota((int) $datos['id_destino_mascota'])) {
        throw new InvalidArgumentException('El destino de la mascota no es válido.');
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

    if (empty($datos['responsable_principal'])) {
        throw new InvalidArgumentException('El responsable principal es obligatorio.');
    }

    $responsable = trim((string) $datos['responsable_principal']);
    if (mb_strlen($responsable) > 60) {
        throw new InvalidArgumentException('El responsable principal no puede superar los 60 caracteres.');
    }

    $id_motivo_adopcion = filter_var(
    $datos['id_motivo_adopcion'] ?? null,
    FILTER_VALIDATE_INT
    );

    if (
        $id_motivo_adopcion === false ||
        $id_motivo_adopcion <= 0
    ) {
        throw new InvalidArgumentException(
            'El motivo de adopción es obligatorio.'
        );
    }

    if (!existe_motivo_adopcion($id_motivo_adopcion)) {
        throw new InvalidArgumentException(
            'El motivo de adopción seleccionado no es válido.'
        );
    }

    if (!isset($datos['puede_realizar_paseos']) || !is_bool($datos['puede_realizar_paseos'])) {
        throw new InvalidArgumentException('Debe indicar si puede realizar paseos.');
    }

    if (!isset($datos['puede_realizar_traslados']) || !is_bool($datos['puede_realizar_traslados'])) {
        throw new InvalidArgumentException('Debe indicar si puede realizar traslados.');
    }

    if (!isset($datos['cuenta_con_apoyo']) || !is_bool($datos['cuenta_con_apoyo'])) {
        throw new InvalidArgumentException('Debe indicar si cuenta con apoyo.');
    }
}


function validar_compromisos(array $ids_compromisos): void {
    if (empty($ids_compromisos) || !is_array($ids_compromisos)) {
        throw new InvalidArgumentException('Debe aceptar los compromisos de adopción.');
    }

    $compromisos_activos = obtener_compromisos_adopcion();
    $ids_activos = array_map(function ($c) {
        return (int) $c['id'];
    }, $compromisos_activos);

    $ids_recibidos = array_map('intval', $ids_compromisos);
    $ids_recibidos = array_values(array_unique($ids_recibidos));

    sort($ids_activos);
    sort($ids_recibidos);

    if ($ids_activos !== $ids_recibidos) {
        throw new InvalidArgumentException(
            'Debe aceptar todos los compromisos de adopción activos.'
        );
    }

    foreach ($ids_recibidos as $id) {
        if (!existe_compromiso_adopcion($id)) {
            throw new InvalidArgumentException(
                'Uno de los compromisos seleccionados no es válido.'
            );
        }
    }
}


function validar_condiciones_especiales(array $ids_condiciones): void {
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


function validar_mascotas_hogar(array $mascotas): void {
    if (!is_array($mascotas)) {
        throw new InvalidArgumentException(
            'Las mascotas del hogar deben ser una lista.'
        );
    }
    foreach ($mascotas as $m) {
        if (
            empty($m['id_tipo_mascota']) ||
            !existe_tipo_mascota((int) $m['id_tipo_mascota'])
        ) {
            throw new InvalidArgumentException(
                'Una de las mascotas del hogar tiene un tipo inválido.'
            );
        }

        if (
            isset($m['edad_aproximada']) &&
            $m['edad_aproximada'] !== null
        ) {
            $edad = (int) $m['edad_aproximada'];

            if ($edad <= 0 || $edad >= 30) {
                throw new InvalidArgumentException(
                    'La edad aproximada de una mascota debe ser mayor a 0 y menor a 30 años.'
                );
            }
        }

        if (
            isset($m['id_enfermedad_condicion']) &&
            $m['id_enfermedad_condicion'] !== null
        ) {
            $id_enfermedad_condicion =
                (int) $m['id_enfermedad_condicion'];

            if (
                $id_enfermedad_condicion <= 0 ||
                !existe_enfermedad_condicion(
                    $id_enfermedad_condicion
                )
            ) {
                throw new InvalidArgumentException(
                    'La enfermedad o condición seleccionada no es válida.'
                );
            }
        }
    }
}


// OPERACIONES
function obtener_formulario_adopcion(int $id, int $id_usuario_actual, bool $es_admin): array {
    if ($id <= 0) {
        throw new InvalidArgumentException('El id del formulario no es válido.');
    }

    $formulario = obtener_formulario_adopcion_por_id($id);

    if (!$formulario) {
        throw new RuntimeException('Formulario no encontrado.');
    }

    $solicitud = obtener_datos_solicitud((int) $formulario['id_solicitud']);

    if (!$es_admin && (int) $solicitud['id_usuario'] !== $id_usuario_actual) {
        throw new InvalidArgumentException('No tenés permiso para ver este formulario.');
    }

    $formulario['compromisos'] = obtener_compromisos_de_formulario($id);
    $formulario['condiciones_especiales'] = obtener_condiciones_de_formulario($id);
    $formulario['mascotas_hogar'] = obtener_mascotas_de_formulario($id);

    return $formulario;
}


function obtener_formulario_por_solicitud(int $id_solicitud, int $id_usuario_actual, bool $es_admin): array {
    $solicitud = validar_solicitud_para_formulario($id_solicitud, $id_usuario_actual, $es_admin);

    $formulario = obtener_formulario_adopcion_por_solicitud($id_solicitud);

    if (!$formulario) {
        throw new RuntimeException('Esta solicitud todavía no tiene formulario de adopción.');
    }

    $id_formulario = (int) $formulario['id'];

    $formulario['compromisos'] = obtener_compromisos_de_formulario($id_formulario);
    $formulario['condiciones_especiales'] = obtener_condiciones_de_formulario($id_formulario);
    $formulario['mascotas_hogar'] = obtener_mascotas_de_formulario($id_formulario);

    return $formulario;
}


function registrar_formulario_adopcion(int $id_usuario_actual, array $datos): array {
    validar_usuario_mayor_de_edad($id_usuario_actual);
    $id_solicitud = filter_var($datos['id_solicitud'] ?? null, FILTER_VALIDATE_INT);

    if ($id_solicitud === false || $id_solicitud <= 0) {
        throw new InvalidArgumentException('El id de la solicitud no es válido.');
    }

    $solicitud = validar_solicitud_para_formulario($id_solicitud, $id_usuario_actual, false);
    validar_solicitud_pendiente($solicitud);

    if (existe_formulario_para_solicitud($id_solicitud)) {
        throw new InvalidArgumentException(
            'Esta solicitud ya tiene un formulario de adopción. Usá PUT para modificarlo.'
        );
    }

    validar_formulario_datos($datos);
    validar_compromisos($datos['compromisos'] ?? []);
    validar_condiciones_especiales($datos['condiciones_especiales'] ?? []);
    validar_mascotas_hogar($datos['mascotas_hogar'] ?? []);

    $datosInsert = [
        'id_solicitud' => $id_solicitud,
        'id_tipo_vivienda' => (int) $datos['id_tipo_vivienda'],
        'id_condicion_vivienda' => (int) $datos['id_condicion_vivienda'],
        'tiene_patio' => (bool) $datos['tiene_patio'],
        'metros_cuadrados_patio' => $datos['tiene_patio'] ? (float) $datos['metros_cuadrados_patio'] : null,
        'id_destino_mascota' => (int) $datos['id_destino_mascota'],
        'cantidad_integrantes' => (int) $datos['cantidad_integrantes'],
        'hay_ninos' => (bool) $datos['hay_ninos'],
        'horas_solo' => isset($datos['horas_solo']) && $datos['horas_solo'] !== null ? (int) $datos['horas_solo'] : null,
        'tiene_mascotas' => (bool) $datos['tiene_mascotas'],
        'responsable_principal' => trim((string) $datos['responsable_principal']),
        'id_motivo_adopcion' => (int) $datos['id_motivo_adopcion'],
        'puede_realizar_paseos' => (bool) $datos['puede_realizar_paseos'],
        'puede_realizar_traslados' => (bool) $datos['puede_realizar_traslados'],
        'cuenta_con_apoyo' => (bool) $datos['cuenta_con_apoyo'],
        'limitaciones_cuidado' => !empty($datos['limitaciones_cuidado']) ? trim((string) $datos['limitaciones_cuidado']) : null,
        'experiencia_cuidado' => !empty($datos['experiencia_cuidado']) ? trim((string) $datos['experiencia_cuidado']) : null,
        'cuenta_con_recursos' => isset($datos['cuenta_con_recursos']) && $datos['cuenta_con_recursos'] !== null ? (bool) $datos['cuenta_con_recursos'] : null,
        'observaciones' => !empty($datos['observaciones']) ? trim((string) $datos['observaciones']) : null,
    ];

    $creado = crear_formulario_adopcion($datosInsert);

    if (!$creado) {
        throw new RuntimeException('No se pudo crear el formulario de adopción.');
    }

    $id_formulario = (int) $creado['id'];

    guardar_compromisos_formulario($id_formulario, array_map('intval', $datos['compromisos']));
    guardar_condiciones_formulario($id_formulario, array_map('intval', $datos['condiciones_especiales']));

    if (!empty($datos['mascotas_hogar'])) {
        guardar_mascotas_hogar($id_formulario, $datos['mascotas_hogar']);
    }

    return obtener_formulario_adopcion($id_formulario, $id_usuario_actual, false);
}


function modificar_formulario_adopcion(int $id_formulario, int $id_usuario_actual, array $datos): array {
    if ($id_formulario <= 0) {
        throw new InvalidArgumentException('El id del formulario no es válido.');
    }

    $formulario = obtener_formulario_adopcion_por_id($id_formulario);

    if (!$formulario) {
        throw new RuntimeException('Formulario no encontrado.');
    }

    $solicitud = validar_solicitud_para_formulario(
        (int) $formulario['id_solicitud'],
        $id_usuario_actual,
        false
    );

    validar_solicitud_pendiente($solicitud);

    validar_formulario_datos($datos);
    validar_compromisos($datos['compromisos'] ?? []);
    validar_condiciones_especiales($datos['condiciones_especiales'] ?? []);
    validar_mascotas_hogar($datos['mascotas_hogar'] ?? []);

    $datosUpdate = [
        'id_tipo_vivienda' => (int) $datos['id_tipo_vivienda'],
        'id_condicion_vivienda' => (int) $datos['id_condicion_vivienda'],
        'tiene_patio' => (bool) $datos['tiene_patio'],
        'metros_cuadrados_patio' => $datos['tiene_patio'] ? (float) $datos['metros_cuadrados_patio'] : null,
        'id_destino_mascota' => (int) $datos['id_destino_mascota'],
        'cantidad_integrantes' => (int) $datos['cantidad_integrantes'],
        'hay_ninos' => (bool) $datos['hay_ninos'],
        'horas_solo' => isset($datos['horas_solo']) && $datos['horas_solo'] !== null ? (int) $datos['horas_solo'] : null,
        'tiene_mascotas' => (bool) $datos['tiene_mascotas'],
        'responsable_principal' => trim((string) $datos['responsable_principal']),
        'id_motivo_adopcion' => (int) $datos['id_motivo_adopcion'],
        'puede_realizar_paseos' => (bool) $datos['puede_realizar_paseos'],
        'puede_realizar_traslados' => (bool) $datos['puede_realizar_traslados'],
        'cuenta_con_apoyo' => (bool) $datos['cuenta_con_apoyo'],
        'limitaciones_cuidado' => !empty($datos['limitaciones_cuidado']) ? trim((string) $datos['limitaciones_cuidado']) : null,
        'experiencia_cuidado' => !empty($datos['experiencia_cuidado']) ? trim((string) $datos['experiencia_cuidado']) : null,
        'cuenta_con_recursos' => isset($datos['cuenta_con_recursos']) && $datos['cuenta_con_recursos'] !== null ? (bool) $datos['cuenta_con_recursos'] : null,
        'observaciones' => !empty($datos['observaciones']) ? trim((string) $datos['observaciones']) : null,
    ];

    $actualizado = actualizar_formulario_adopcion($id_formulario, $datosUpdate);

    if (!$actualizado) {
        throw new RuntimeException('No se pudo actualizar el formulario de adopción.');
    }

    eliminar_compromisos_formulario($id_formulario);
    eliminar_condiciones_formulario($id_formulario);
    eliminar_mascotas_hogar($id_formulario);

    guardar_compromisos_formulario($id_formulario, array_map('intval', $datos['compromisos']));
    guardar_condiciones_formulario($id_formulario, array_map('intval', $datos['condiciones_especiales']));

    if (!empty($datos['mascotas_hogar'])) {
        guardar_mascotas_hogar($id_formulario, $datos['mascotas_hogar']);
    }

    return obtener_formulario_adopcion($id_formulario, $id_usuario_actual, false);
}
function listar_condiciones_vivienda(): array {
    return obtener_condiciones_vivienda();
}


function listar_destinos_mascotas(): array {
    return obtener_destinos_mascotas();
}

function listar_formularios_adopcion(array $filtros, int $id_usuario_actual, bool $es_admin): array {
    $id_usuario_solo_suyos = $es_admin ? null : $id_usuario_actual;

    return obtener_formularios_adopcion_filtrados($filtros, $id_usuario_solo_suyos);
}