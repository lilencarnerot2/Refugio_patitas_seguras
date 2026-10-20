<?php

require_once __DIR__ . '/../repositories/movimientos.php';


// CATÁLOGOS
function listar_tipos_aportes(): array {
    return obtener_tipos_aportes();
}


function listar_tipos_movimientos_aportes(): array {
    return obtener_tipos_movimientos_aportes();
}


function listar_frecuencia_aportes(): array {
    return obtener_frecuencia_aportes();
}


function listar_metodos_pago(): array {
    return obtener_metodos_pago();
}


// TIPOS DE APORTES
function registrar_tipo_aporte(array $datos): array {
    $nombre = trim($datos['nombre_aporte'] ?? '');
    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre del tipo de aporte es obligatorio.'
        );
    }
    if (existe_tipo_aporte_por_nombre($nombre)) {
        throw new InvalidArgumentException(
            'Ya existe un tipo de aporte con ese nombre.'
        );
    }
    $tipo = crear_tipo_aporte($nombre);

    if (!$tipo) {
        throw new RuntimeException(
            'No se pudo crear el tipo de aporte.'
        );
    }
    return $tipo;
}


function modificar_tipo_aporte(
    int $id,
    array $datos
): array {
    if ($id <= 0 || !existe_tipo_aporte($id)) {
        throw new InvalidArgumentException(
            'El tipo de aporte indicado no existe.'
        );
    }

    $nombre = trim($datos['nombre_aporte'] ?? '');

    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre del tipo de aporte es obligatorio.'
        );
    }

    if (existe_tipo_aporte_por_nombre($nombre, $id)) {
        throw new InvalidArgumentException(
            'Ya existe un tipo de aporte con ese nombre.'
        );
    }

    $tipo = actualizar_tipo_aporte(
        $id,
        $nombre
    );

    if (!$tipo) {
        throw new RuntimeException(
            'No se pudo actualizar el tipo de aporte.'
        );
    }

    return $tipo;
}


function dar_baja_tipo_aporte(int $id): array {
    if ($id <= 0 || !existe_tipo_aporte($id)) {
        throw new InvalidArgumentException(
            'El tipo de aporte indicado no existe.'
        );
    }

    $tipo = eliminar_tipo_aporte($id);

    if (!$tipo) {
        throw new RuntimeException(
            'No se pudo eliminar el tipo de aporte.'
        );
    }

    return $tipo;
}


// FRECUENCIAS
function registrar_frecuencia_aporte(array $datos): array {
    $nombre = trim(
        $datos['nombre_frecuencia_aporte'] ?? ''
    );

    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre de la frecuencia es obligatorio.'
        );
    }

    if (existe_frecuencia_aporte_por_nombre($nombre)) {
        throw new InvalidArgumentException(
            'Ya existe una frecuencia con ese nombre.'
        );
    }

    $frecuencia = crear_frecuencia_aporte($nombre);

    if (!$frecuencia) {
        throw new RuntimeException(
            'No se pudo crear la frecuencia.'
        );
    }

    return $frecuencia;
}


function modificar_frecuencia_aporte(
    int $id,
    array $datos
): array {
    if ($id <= 0 || !existe_frecuencia_aporte($id)) {
        throw new InvalidArgumentException(
            'La frecuencia indicada no existe.'
        );
    }

    $nombre = trim(
        $datos['nombre_frecuencia_aporte'] ?? ''
    );

    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre de la frecuencia es obligatorio.'
        );
    }

    if (
        existe_frecuencia_aporte_por_nombre(
            $nombre,
            $id
        )
    ) {
        throw new InvalidArgumentException(
            'Ya existe una frecuencia con ese nombre.'
        );
    }

    $frecuencia = actualizar_frecuencia_aporte(
        $id,
        $nombre
    );

    if (!$frecuencia) {
        throw new RuntimeException(
            'No se pudo actualizar la frecuencia.'
        );
    }

    return $frecuencia;
}


function dar_baja_frecuencia_aporte(int $id): array {
    if ($id <= 0 || !existe_frecuencia_aporte($id)) {
        throw new InvalidArgumentException(
            'La frecuencia indicada no existe.'
        );
    }

    $frecuencia = eliminar_frecuencia_aporte($id);

    if (!$frecuencia) {
        throw new RuntimeException(
            'No se pudo eliminar la frecuencia.'
        );
    }

    return $frecuencia;
}



// MÉTODOS DE PAGO
function registrar_metodo_pago(array $datos): array {
    $nombre = trim(
        $datos['nombre_metodo'] ?? ''
    );
    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre del método de pago es obligatorio.'
        );
    }
    if (existe_metodo_pago_por_nombre($nombre)) {
        throw new InvalidArgumentException(
            'Ya existe un método de pago con ese nombre.'
        );
    }
    $metodo = crear_metodo_pago($nombre);

    if (!$metodo) {
        throw new RuntimeException(
            'No se pudo crear el método de pago.'
        );
    }

    return $metodo;
}


function modificar_metodo_pago(
    int $id,
    array $datos
): array {
    if ($id <= 0 || !existe_metodo_pago($id)) {
        throw new InvalidArgumentException(
            'El método de pago indicado no existe.'
        );
    }

    $nombre = trim(
        $datos['nombre_metodo'] ?? ''
    );
    if ($nombre === '') {
        throw new InvalidArgumentException(
            'El nombre del método de pago es obligatorio.'
        );
    }
    if (
        existe_metodo_pago_por_nombre(
            $nombre,
            $id
        )
    ) {
        throw new InvalidArgumentException(
            'Ya existe un método de pago con ese nombre.'
        );
    }
    $metodo = actualizar_metodo_pago(
        $id,
        $nombre
    );
    if (!$metodo) {
        throw new RuntimeException(
            'No se pudo actualizar el método de pago.'
        );
    }

    return $metodo;
}


function dar_baja_metodo_pago(int $id): array {
    if ($id <= 0 || !existe_metodo_pago($id)) {
        throw new InvalidArgumentException(
            'El método de pago indicado no existe.'
        );
    }
    $metodo = eliminar_metodo_pago($id);
    if (!$metodo) {
        throw new RuntimeException(
            'No se pudo eliminar el método de pago.'
        );
    }

    return $metodo;
}

// VALIDACIONES
function validar_fecha(string $fecha): bool {
    $d = DateTime::createFromFormat('Y-m-d', $fecha);
    return $d && $d->format('Y-m-d') === $fecha;
}


function validar_movimiento_datos(array $datos): void {
    $id_tipo_movimiento = filter_var($datos['id_tipo_movimiento'] ?? null, FILTER_VALIDATE_INT);

    if ($id_tipo_movimiento === false || $id_tipo_movimiento <= 0) {
        throw new InvalidArgumentException('El tipo de movimiento no es válido.');
    }

    if (!existe_tipo_movimiento($id_tipo_movimiento)) {
        throw new InvalidArgumentException('El tipo de movimiento no existe o está deshabilitado.');
    }

    $id_tipo_aporte = filter_var($datos['id_tipo_aporte'] ?? null, FILTER_VALIDATE_INT);

    if ($id_tipo_aporte === false || $id_tipo_aporte <= 0) {
        throw new InvalidArgumentException('El tipo de aporte no es válido.');
    }

    if (!existe_tipo_aporte($id_tipo_aporte)) {
        throw new InvalidArgumentException('El tipo de aporte no existe o está deshabilitado.');
    }

    // Si el tipo de aporte es "Dinero" (id=1), monto y método de pago son obligatorios
    if ($id_tipo_aporte === 1) {
        if (!isset($datos['monto']) || $datos['monto'] === null || $datos['monto'] === '') {
            throw new InvalidArgumentException('Si el aporte es dinero, el monto es obligatorio.');
        }

        if (!is_numeric($datos['monto']) || (float) $datos['monto'] <= 0) {
            throw new InvalidArgumentException('El monto debe ser mayor a 0.');
        }

        if (!isset($datos['id_metodo_pago']) || $datos['id_metodo_pago'] === null || $datos['id_metodo_pago'] === '') {
            throw new InvalidArgumentException('Si el aporte es dinero, el método de pago es obligatorio.');
        }

        $id_metodo_pago = filter_var($datos['id_metodo_pago'], FILTER_VALIDATE_INT);

        if ($id_metodo_pago === false || $id_metodo_pago <= 0) {
            throw new InvalidArgumentException('El método de pago no es válido.');
        }

        if (!existe_metodo_pago($id_metodo_pago)) {
            throw new InvalidArgumentException('El método de pago no existe o está deshabilitado.');
        }
    }

    if (
        isset($datos['id_metodo_pago']) &&
        $datos['id_metodo_pago'] !== null &&
        $datos['id_metodo_pago'] !== ''
    ) {
        $id_metodo_pago = filter_var($datos['id_metodo_pago'], FILTER_VALIDATE_INT);

        if ($id_metodo_pago === false || $id_metodo_pago <= 0 || !existe_metodo_pago($id_metodo_pago)) {
            throw new InvalidArgumentException('El método de pago no es válido.');
        }
    }

    if (
        isset($datos['id_frecuencia_aporte']) &&
        $datos['id_frecuencia_aporte'] !== null &&
        $datos['id_frecuencia_aporte'] !== ''
    ) {
        $id_frecuencia_aporte = filter_var($datos['id_frecuencia_aporte'], FILTER_VALIDATE_INT);

        if ($id_frecuencia_aporte === false || $id_frecuencia_aporte <= 0) {
            throw new InvalidArgumentException('La frecuencia del aporte no es válida.');
        }

        if (!existe_frecuencia_aporte($id_frecuencia_aporte)) {
            throw new InvalidArgumentException('La frecuencia del aporte no existe o está deshabilitada.');
        }
    }

    if (!empty($datos['fecha'])) {
        if (!validar_fecha((string) $datos['fecha'])) {
            throw new InvalidArgumentException('La fecha no es válida. Formato esperado: YYYY-MM-DD.');
        }
    }

    if (!empty($datos['descripcion'])) {
        if (mb_strlen((string) $datos['descripcion']) > 1000) {
            throw new InvalidArgumentException('La descripción no puede superar los 1000 caracteres.');
        }
    }
}



// OPERACIONES
function listar_movimientos(array $filtros, int $id_usuario_actual, bool $es_admin): array {
    $id_usuario_solo_suyos = $es_admin ? null : $id_usuario_actual;

    return obtener_movimientos_filtrados($filtros, $id_usuario_solo_suyos);
}


function buscar_movimiento_por_id(int $id, int $id_usuario_actual, bool $es_admin): array {
    if ($id <= 0) {
        throw new InvalidArgumentException('El id del movimiento no es válido.');
    }

    $movimiento = obtener_movimiento_por_id($id);

    if (!$movimiento) {
        throw new RuntimeException('Movimiento no encontrado.');
    }

    if (!$es_admin && (int) $movimiento['id_usuario'] !== $id_usuario_actual) {
        throw new InvalidArgumentException('No tenés permiso para ver este movimiento.');
    }

    return $movimiento;
}


function registrar_movimiento(?int $id_usuario_actual, array $datos): array {
    validar_movimiento_datos($datos);

    $id_tipo_aporte = (int) $datos['id_tipo_aporte'];

    // Solo "dinero" (id = 1) puede ser anonimo
    if (
        $id_tipo_aporte !== 1 &&
        ($id_usuario_actual === null || $id_usuario_actual <= 0)
    ) {
        throw new InvalidArgumentException(
            'Debés iniciar sesión para realizar una donación de insumos.'
        );
    }

    // Si existe usuario, debe ser valido
    if ($id_usuario_actual !== null && $id_usuario_actual <= 0) {
        throw new InvalidArgumentException(
            'El usuario autenticado no es válido.'
        );
    }

    $monto = null;
    $id_metodo_pago = null;

    // solo guardamos monto y metodo de pago si el aporte es dinero
    if ($id_tipo_aporte === 1) {
        $monto = (float) $datos['monto'];
        $id_metodo_pago = (int) $datos['id_metodo_pago'];
    }

    $id_frecuencia_aporte = null;

    if (
        isset($datos['id_frecuencia_aporte']) &&
        $datos['id_frecuencia_aporte'] !== null &&
        $datos['id_frecuencia_aporte'] !== ''
    ) {
        $id_frecuencia_aporte =
            (int) $datos['id_frecuencia_aporte'];
    }

    $fecha = !empty($datos['fecha'])
        ? (string) $datos['fecha']
        : date('Y-m-d');

    $descripcion = null;

    if (!empty($datos['descripcion'])) {
        $descripcion =
            trim((string) $datos['descripcion']);
    }

    $creado = crear_movimiento([
        'id_usuario' => $id_usuario_actual,
        'id_tipo_movimiento' =>
            (int) $datos['id_tipo_movimiento'],
        'id_tipo_aporte' => $id_tipo_aporte,
        'id_metodo_pago' => $id_metodo_pago,
        'id_frecuencia_aporte' =>
            $id_frecuencia_aporte,
        'fecha' => $fecha,
        'monto' => $monto,
        'descripcion' => $descripcion,
    ]);

    if (!$creado) {
        throw new RuntimeException(
            'No se pudo registrar el movimiento.'
        );
    }

    $movimiento =
        obtener_movimiento_por_id((int) $creado['id']);

    if (!$movimiento) {
        throw new RuntimeException(
            'No se pudo recuperar el movimiento registrado.'
        );
    }

    return $movimiento;
}


// REPORTES
function validar_filtros_reporte(array $filtros): void {
    if (!empty($filtros['fecha_desde'])) {
        if (!validar_fecha((string) $filtros['fecha_desde'])) {
            throw new InvalidArgumentException('La fecha_desde no es válida. Formato esperado: YYYY-MM-DD.');
        }
    }

    if (!empty($filtros['fecha_hasta'])) {
        if (!validar_fecha((string) $filtros['fecha_hasta'])) {
            throw new InvalidArgumentException('La fecha_hasta no es válida. Formato esperado: YYYY-MM-DD.');
        }
    }

    if (
        !empty($filtros['fecha_desde']) &&
        !empty($filtros['fecha_hasta']) &&
        $filtros['fecha_hasta'] < $filtros['fecha_desde']
    ) {
        throw new InvalidArgumentException('La fecha_hasta no puede ser menor que la fecha_desde.');
    }

    if (!empty($filtros['id_tipo_movimiento'])) {
        if (!existe_tipo_movimiento((int) $filtros['id_tipo_movimiento'])) {
            throw new InvalidArgumentException('El tipo de movimiento no es válido.');
        }
    }

    if (!empty($filtros['id_tipo_aporte'])) {
        if (!existe_tipo_aporte((int) $filtros['id_tipo_aporte'])) {
            throw new InvalidArgumentException('El tipo de aporte no es válido.');
        }
    }

    if (!empty($filtros['id_metodo_pago'])) {
        if (!existe_metodo_pago((int) $filtros['id_metodo_pago'])) {
            throw new InvalidArgumentException('El método de pago no es válido.');
        }
    }

    if (!empty($filtros['id_frecuencia_aporte'])) {
        if (!existe_frecuencia_aporte((int) $filtros['id_frecuencia_aporte'])) {
            throw new InvalidArgumentException('La frecuencia del aporte no es válida.');
        }
    }
}


function resumen_movimientos(array $filtros): array {
    validar_filtros_reporte($filtros);

    return obtener_resumen_movimientos($filtros);
}


function reporte_por_tipo_aporte(array $filtros): array {
    validar_filtros_reporte($filtros);

    return obtener_totales_por_tipo_aporte($filtros);
}


function reporte_por_metodo_pago(array $filtros): array {
    validar_filtros_reporte($filtros);

    return obtener_totales_por_metodo_pago($filtros);
}


function reporte_por_frecuencia(array $filtros): array {
    validar_filtros_reporte($filtros);

    return obtener_totales_por_frecuencia($filtros);
}