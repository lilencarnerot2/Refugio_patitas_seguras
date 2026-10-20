<?php

require_once __DIR__ . '/../../../config/database.php';


// CATÁLOGOS
function obtener_tipos_aportes(): array {
    $conexion = conectarDB();
    $sql = "SELECT id, nombre_aporte, estado FROM tipos_aportes WHERE estado = TRUE ORDER BY id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_tipos_movimientos_aportes(): array {
    $conexion = conectarDB();
    $sql = "SELECT id, nombre_movimiento, estado FROM tipos_movimientos_aportes WHERE estado = TRUE ORDER BY id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_frecuencia_aportes(): array {
    $conexion = conectarDB();
    $sql = "SELECT id, nombre_frecuencia_aporte, estado FROM frecuencia_aportes WHERE estado = TRUE ORDER BY id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_metodos_pago(): array {
    $conexion = conectarDB();
    $sql = "SELECT id, nombre_metodo, estado FROM metodos_pago WHERE estado = TRUE ORDER BY id";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function existe_tipo_aporte(int $id): bool {
    $conexion = conectarDB();
    $sql = "SELECT 1 FROM tipos_aportes WHERE id = :id AND estado = TRUE LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
    return (bool) $stmt->fetchColumn();
}


function existe_tipo_movimiento(int $id): bool {
    $conexion = conectarDB();
    $sql = "SELECT 1 FROM tipos_movimientos_aportes WHERE id = :id AND estado = TRUE LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
    return (bool) $stmt->fetchColumn();
}


function existe_frecuencia_aporte(int $id): bool {
    $conexion = conectarDB();
    $sql = "SELECT 1 FROM frecuencia_aportes WHERE id = :id AND estado = TRUE LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
    return (bool) $stmt->fetchColumn();
}


function existe_metodo_pago(int $id): bool {
    $conexion = conectarDB();
    $sql = "SELECT 1 FROM metodos_pago WHERE id = :id AND estado = TRUE LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);
    return (bool) $stmt->fetchColumn();
}


// CRUD TIPOS DE APORTES
function existe_tipo_aporte_por_nombre(
    string $nombre,
    ?int $idExcluir = null
): bool {
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM tipos_aportes
        WHERE LOWER(nombre_aporte) = LOWER(:nombre)
          AND estado = TRUE
    ";

    $parametros = [
        ':nombre' => $nombre
    ];

    if ($idExcluir !== null) {
        $sql .= " AND id <> :id_excluir";
        $parametros[':id_excluir'] = $idExcluir;
    }

    $stmt = $conexion->prepare($sql);
    $stmt->execute($parametros);

    return (bool) $stmt->fetchColumn();
}


function crear_tipo_aporte(string $nombre): array|false {
    $conexion = conectarDB();

    $sql = "
        INSERT INTO tipos_aportes (
            nombre_aporte,
            estado
        )
        VALUES (
            :nombre,
            TRUE
        )
        RETURNING
            id,
            nombre_aporte,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':nombre' => $nombre
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function actualizar_tipo_aporte(
    int $id,
    string $nombre
): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE tipos_aportes
        SET nombre_aporte = :nombre
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_aporte,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id,
        ':nombre' => $nombre
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function eliminar_tipo_aporte(int $id): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE tipos_aportes
        SET estado = FALSE
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_aporte,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// CRUD FRECUENCIAS DE APORTES
function existe_frecuencia_aporte_por_nombre(
    string $nombre,
    ?int $idExcluir = null
): bool {
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM frecuencia_aportes
        WHERE LOWER(nombre_frecuencia_aporte) = LOWER(:nombre)
          AND estado = TRUE
    ";

    $parametros = [
        ':nombre' => $nombre
    ];

    if ($idExcluir !== null) {
        $sql .= " AND id <> :id_excluir";
        $parametros[':id_excluir'] = $idExcluir;
    }

    $stmt = $conexion->prepare($sql);
    $stmt->execute($parametros);

    return (bool) $stmt->fetchColumn();
}


function crear_frecuencia_aporte(
    string $nombre
): array|false {
    $conexion = conectarDB();

    $sql = "
        INSERT INTO frecuencia_aportes (
            nombre_frecuencia_aporte,
            estado
        )
        VALUES (
            :nombre,
            TRUE
        )
        RETURNING
            id,
            nombre_frecuencia_aporte,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':nombre' => $nombre
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function actualizar_frecuencia_aporte(
    int $id,
    string $nombre
): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE frecuencia_aportes
        SET nombre_frecuencia_aporte = :nombre
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_frecuencia_aporte,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id,
        ':nombre' => $nombre
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function eliminar_frecuencia_aporte(
    int $id
): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE frecuencia_aportes
        SET estado = FALSE
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_frecuencia_aporte,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// CRUD MÉTODOS DE PAGO
function existe_metodo_pago_por_nombre(
    string $nombre,
    ?int $idExcluir = null
): bool {
    $conexion = conectarDB();

    $sql = "
        SELECT 1
        FROM metodos_pago
        WHERE LOWER(nombre_metodo) = LOWER(:nombre)
          AND estado = TRUE
    ";

    $parametros = [
        ':nombre' => $nombre
    ];

    if ($idExcluir !== null) {
        $sql .= " AND id <> :id_excluir";
        $parametros[':id_excluir'] = $idExcluir;
    }

    $stmt = $conexion->prepare($sql);
    $stmt->execute($parametros);

    return (bool) $stmt->fetchColumn();
}


function crear_metodo_pago(
    string $nombre
): array|false {
    $conexion = conectarDB();

    $sql = "
        INSERT INTO metodos_pago (
            nombre_metodo,
            estado
        )
        VALUES (
            :nombre,
            TRUE
        )
        RETURNING
            id,
            nombre_metodo,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':nombre' => $nombre
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function actualizar_metodo_pago(
    int $id,
    string $nombre
): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE metodos_pago
        SET nombre_metodo = :nombre
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_metodo,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id,
        ':nombre' => $nombre
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function eliminar_metodo_pago(
    int $id
): array|false {
    $conexion = conectarDB();

    $sql = "
        UPDATE metodos_pago
        SET estado = FALSE
        WHERE id = :id
          AND estado = TRUE
        RETURNING
            id,
            nombre_metodo,
            estado
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// MOVIMIENTOS
function obtener_movimientos_filtrados(array $filtros,?int $id_usuario_solo_suyos): array{
    $conexion = conectarDB();
    $sql = "
        SELECT
            m.id,
            m.id_usuario,
            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            m.id_tipo_movimiento,
            tm.nombre_movimiento AS tipo_movimiento,
            m.id_tipo_aporte,
            ta.nombre_aporte AS tipo_aporte,
            m.id_metodo_pago,
            mp.nombre_metodo AS metodo_pago,
            m.id_frecuencia_aporte,
            fa.nombre_frecuencia_aporte AS frecuencia_aporte,
            m.fecha,
            m.monto,
            m.descripcion
        FROM movimientos m
        LEFT JOIN usuarios u
            ON u.id = m.id_usuario
        INNER JOIN tipos_movimientos_aportes tm
            ON tm.id = m.id_tipo_movimiento
        INNER JOIN tipos_aportes ta
            ON ta.id = m.id_tipo_aporte
        LEFT JOIN metodos_pago mp
            ON mp.id = m.id_metodo_pago
        LEFT JOIN frecuencia_aportes fa
            ON fa.id = m.id_frecuencia_aporte
        WHERE 1 = 1
    ";

    $parametros = [];
    if ($id_usuario_solo_suyos !== null) {
        $sql .= " AND m.id_usuario = :id_usuario";
        $parametros[':id_usuario'] = $id_usuario_solo_suyos;
    }
    if (!empty($filtros['id_tipo_aporte'])) {
        $sql .= " AND m.id_tipo_aporte = :id_tipo_aporte";
        $parametros[':id_tipo_aporte'] = (int) $filtros['id_tipo_aporte'];
    }
    if (!empty($filtros['id_tipo_movimiento'])) {
        $sql .= " AND m.id_tipo_movimiento = :id_tipo_movimiento";
        $parametros[':id_tipo_movimiento'] = (int) $filtros['id_tipo_movimiento'];
    }
    if (!empty($filtros['id_metodo_pago'])) {
        $sql .= " AND m.id_metodo_pago = :id_metodo_pago";
        $parametros[':id_metodo_pago'] = (int) $filtros['id_metodo_pago'];
    }
    if (!empty($filtros['id_frecuencia_aporte'])) {
        $sql .= " AND m.id_frecuencia_aporte = :id_frecuencia_aporte";
        $parametros[':id_frecuencia_aporte'] = (int) $filtros['id_frecuencia_aporte'];
    }
    if (!empty($filtros['fecha_desde'])) {
        $sql .= " AND m.fecha >= :fecha_desde";
        $parametros[':fecha_desde'] = $filtros['fecha_desde'];
    }
    if (!empty($filtros['fecha_hasta'])) {
        $sql .= " AND m.fecha <= :fecha_hasta";
        $parametros[':fecha_hasta'] = $filtros['fecha_hasta'];
    }
    if (!empty($filtros['busqueda'])) {
        $sql .= "
            AND (
                LOWER(m.descripcion) LIKE LOWER(:busqueda)
                OR LOWER(u.nombre) LIKE LOWER(:busqueda)
                OR LOWER(u.apellido) LIKE LOWER(:busqueda)
            )
        ";

        $parametros[':busqueda'] =
            '%' . $filtros['busqueda'] . '%';
    }

    $sql .= "
        ORDER BY
            m.fecha DESC,
            m.id DESC
    ";
    $stmt = $conexion->prepare($sql);
    $stmt->execute($parametros);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_movimiento_por_id(int $id): array|false {
    $conexion = conectarDB();
    $sql = "
        SELECT
            m.id,
            m.id_usuario,
            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            m.id_tipo_movimiento,
            tm.nombre_movimiento AS tipo_movimiento,
            m.id_tipo_aporte,
            ta.nombre_aporte AS tipo_aporte,
            m.id_metodo_pago,
            mp.nombre_metodo AS metodo_pago,
            m.id_frecuencia_aporte,
            fa.nombre_frecuencia_aporte AS frecuencia_aporte,
            m.fecha,
            m.monto,
            m.descripcion
        FROM movimientos m
        LEFT JOIN usuarios u
            ON u.id = m.id_usuario
        INNER JOIN tipos_movimientos_aportes tm
            ON tm.id = m.id_tipo_movimiento
        INNER JOIN tipos_aportes ta
            ON ta.id = m.id_tipo_aporte
        LEFT JOIN metodos_pago mp
            ON mp.id = m.id_metodo_pago
        LEFT JOIN frecuencia_aportes fa
            ON fa.id = m.id_frecuencia_aporte
        WHERE m.id = :id
    ";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}



function crear_movimiento(array $datos): array|false {
    $conexion = conectarDB();

    $sql = "
        INSERT INTO movimientos (
            id_usuario,
            id_tipo_movimiento,
            id_tipo_aporte,
            id_metodo_pago,
            id_frecuencia_aporte,
            fecha,
            monto,
            descripcion
        )
        VALUES (
            :id_usuario,
            :id_tipo_movimiento,
            :id_tipo_aporte,
            :id_metodo_pago,
            :id_frecuencia_aporte,
            :fecha,
            :monto,
            :descripcion
        )
        RETURNING id
    ";

    $stmt = $conexion->prepare($sql);

    if ($datos['id_usuario'] === null) {
        $stmt->bindValue(':id_usuario', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':id_usuario', $datos['id_usuario'], PDO::PARAM_INT);
    }
    
    $stmt->bindValue(':id_tipo_movimiento', $datos['id_tipo_movimiento'], PDO::PARAM_INT);
    $stmt->bindValue(':id_tipo_aporte', $datos['id_tipo_aporte'], PDO::PARAM_INT);

    if ($datos['id_metodo_pago'] === null) {
        $stmt->bindValue(':id_metodo_pago', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':id_metodo_pago', $datos['id_metodo_pago'], PDO::PARAM_INT);
    }

    if ($datos['id_frecuencia_aporte'] === null) {
        $stmt->bindValue(':id_frecuencia_aporte', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':id_frecuencia_aporte', $datos['id_frecuencia_aporte'], PDO::PARAM_INT);
    }

    $stmt->bindValue(':fecha', $datos['fecha'], PDO::PARAM_STR);

    if ($datos['monto'] === null) {
        $stmt->bindValue(':monto', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':monto', $datos['monto'], PDO::PARAM_STR);
    }

    if ($datos['descripcion'] === null) {
        $stmt->bindValue(':descripcion', null, PDO::PARAM_NULL);
    } else {
        $stmt->bindValue(':descripcion', $datos['descripcion'], PDO::PARAM_STR);
    }

    $stmt->execute();

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    return $fila ?: false;
}


// REPORTES
function obtener_resumen_movimientos(array $filtros): array {
    $conexion = conectarDB();

    $sql = "
        SELECT
            COALESCE(SUM(CASE WHEN m.id_tipo_movimiento = 1 AND m.monto IS NOT NULL THEN m.monto ELSE 0 END), 0) AS total_ingresos,
            COALESCE(SUM(CASE WHEN m.id_tipo_movimiento = 2 AND m.monto IS NOT NULL THEN m.monto ELSE 0 END), 0) AS total_egresos,
            COALESCE(SUM(CASE WHEN m.id_tipo_movimiento = 1 THEN 1 ELSE 0 END), 0) AS cantidad_ingresos,
            COALESCE(SUM(CASE WHEN m.id_tipo_movimiento = 2 THEN 1 ELSE 0 END), 0) AS cantidad_egresos,
            COUNT(*) AS total_aportes
        FROM movimientos m
        INNER JOIN tipos_movimientos_aportes tm
            ON tm.id = m.id_tipo_movimiento
        INNER JOIN tipos_aportes ta
            ON ta.id = m.id_tipo_aporte
        LEFT JOIN metodos_pago mp
            ON mp.id = m.id_metodo_pago
        LEFT JOIN frecuencia_aportes fa
            ON fa.id = m.id_frecuencia_aporte
        WHERE 1 = 1
    ";

    $parametros = [];

    if (!empty($filtros['id_tipo_movimiento'])) {
        $sql .= " AND m.id_tipo_movimiento = :id_tipo_movimiento";
        $parametros[':id_tipo_movimiento'] = (int) $filtros['id_tipo_movimiento'];
    }

    if (!empty($filtros['id_tipo_aporte'])) {
        $sql .= " AND m.id_tipo_aporte = :id_tipo_aporte";
        $parametros[':id_tipo_aporte'] = (int) $filtros['id_tipo_aporte'];
    }

    if (!empty($filtros['id_metodo_pago'])) {
        $sql .= " AND m.id_metodo_pago = :id_metodo_pago";
        $parametros[':id_metodo_pago'] = (int) $filtros['id_metodo_pago'];
    }

    if (!empty($filtros['id_frecuencia_aporte'])) {
        $sql .= " AND m.id_frecuencia_aporte = :id_frecuencia_aporte";
        $parametros[':id_frecuencia_aporte'] = (int) $filtros['id_frecuencia_aporte'];
    }

    if (!empty($filtros['fecha_desde'])) {
        $sql .= " AND m.fecha >= :fecha_desde";
        $parametros[':fecha_desde'] = $filtros['fecha_desde'];
    }

    if (!empty($filtros['fecha_hasta'])) {
        $sql .= " AND m.fecha <= :fecha_hasta";
        $parametros[':fecha_hasta'] = $filtros['fecha_hasta'];
    }

    $stmt = $conexion->prepare($sql);
    $stmt->execute($parametros);

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$fila) {
        return [
            'total_ingresos' => 0,
            'total_egresos' => 0,
            'balance' => 0,
            'cantidad_ingresos' => 0,
            'cantidad_egresos' => 0,
            'total_aportes' => 0
        ];
    }

    $total_ingresos = (float) $fila['total_ingresos'];
    $total_egresos = (float) $fila['total_egresos'];

    return [
        'total_ingresos' => $total_ingresos,
        'total_egresos' => $total_egresos,
        'balance' => $total_ingresos - $total_egresos,
        'cantidad_ingresos' => (int) $fila['cantidad_ingresos'],
        'cantidad_egresos' => (int) $fila['cantidad_egresos'],
        'total_aportes' => (int) $fila['total_aportes']
    ];
}


function obtener_totales_por_tipo_aporte(array $filtros): array {
    $conexion = conectarDB();

    $sql = "
        SELECT
            ta.id AS id_tipo_aporte,
            ta.nombre_aporte AS tipo_aporte,
            COUNT(m.id) AS cantidad,
            COALESCE(SUM(m.monto), 0) AS total_monto
        FROM movimientos m
        INNER JOIN tipos_aportes ta
            ON ta.id = m.id_tipo_aporte
        WHERE 1 = 1
    ";

    $parametros = [];

    if (!empty($filtros['id_tipo_movimiento'])) {
        $sql .= " AND m.id_tipo_movimiento = :id_tipo_movimiento";
        $parametros[':id_tipo_movimiento'] = (int) $filtros['id_tipo_movimiento'];
    }

    if (!empty($filtros['id_metodo_pago'])) {
        $sql .= " AND m.id_metodo_pago = :id_metodo_pago";
        $parametros[':id_metodo_pago'] = (int) $filtros['id_metodo_pago'];
    }

    if (!empty($filtros['id_frecuencia_aporte'])) {
        $sql .= " AND m.id_frecuencia_aporte = :id_frecuencia_aporte";
        $parametros[':id_frecuencia_aporte'] = (int) $filtros['id_frecuencia_aporte'];
    }

    if (!empty($filtros['fecha_desde'])) {
        $sql .= " AND m.fecha >= :fecha_desde";
        $parametros[':fecha_desde'] = $filtros['fecha_desde'];
    }

    if (!empty($filtros['fecha_hasta'])) {
        $sql .= " AND m.fecha <= :fecha_hasta";
        $parametros[':fecha_hasta'] = $filtros['fecha_hasta'];
    }

    $sql .= " GROUP BY ta.id, ta.nombre_aporte ORDER BY ta.id";

    $stmt = $conexion->prepare($sql);
    $stmt->execute($parametros);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_totales_por_metodo_pago(array $filtros): array {
    $conexion = conectarDB();

    $sql = "
        SELECT
            mp.id AS id_metodo_pago,
            mp.nombre_metodo AS metodo_pago,
            COUNT(m.id) AS cantidad,
            COALESCE(SUM(m.monto), 0) AS total_monto
        FROM movimientos m
        INNER JOIN metodos_pago mp
            ON mp.id = m.id_metodo_pago
        WHERE 1 = 1
    ";

    $parametros = [];

    if (!empty($filtros['id_tipo_movimiento'])) {
        $sql .= " AND m.id_tipo_movimiento = :id_tipo_movimiento";
        $parametros[':id_tipo_movimiento'] = (int) $filtros['id_tipo_movimiento'];
    }

    if (!empty($filtros['id_tipo_aporte'])) {
        $sql .= " AND m.id_tipo_aporte = :id_tipo_aporte";
        $parametros[':id_tipo_aporte'] = (int) $filtros['id_tipo_aporte'];
    }

    if (!empty($filtros['id_frecuencia_aporte'])) {
        $sql .= " AND m.id_frecuencia_aporte = :id_frecuencia_aporte";
        $parametros[':id_frecuencia_aporte'] = (int) $filtros['id_frecuencia_aporte'];
    }

    if (!empty($filtros['fecha_desde'])) {
        $sql .= " AND m.fecha >= :fecha_desde";
        $parametros[':fecha_desde'] = $filtros['fecha_desde'];
    }

    if (!empty($filtros['fecha_hasta'])) {
        $sql .= " AND m.fecha <= :fecha_hasta";
        $parametros[':fecha_hasta'] = $filtros['fecha_hasta'];
    }

    $sql .= " GROUP BY mp.id, mp.nombre_metodo ORDER BY mp.id";

    $stmt = $conexion->prepare($sql);
    $stmt->execute($parametros);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function obtener_totales_por_frecuencia(array $filtros): array {
    $conexion = conectarDB();

    $sql = "
        SELECT
            fa.id AS id_frecuencia_aporte,
            fa.nombre_frecuencia_aporte AS frecuencia_aporte,
            COUNT(m.id) AS cantidad,
            COALESCE(SUM(m.monto), 0) AS total_monto
        FROM movimientos m
        INNER JOIN frecuencia_aportes fa
            ON fa.id = m.id_frecuencia_aporte
        WHERE 1 = 1
    ";

    $parametros = [];

    if (!empty($filtros['id_tipo_movimiento'])) {
        $sql .= " AND m.id_tipo_movimiento = :id_tipo_movimiento";
        $parametros[':id_tipo_movimiento'] = (int) $filtros['id_tipo_movimiento'];
    }

    if (!empty($filtros['id_tipo_aporte'])) {
        $sql .= " AND m.id_tipo_aporte = :id_tipo_aporte";
        $parametros[':id_tipo_aporte'] = (int) $filtros['id_tipo_aporte'];
    }

    if (!empty($filtros['id_metodo_pago'])) {
        $sql .= " AND m.id_metodo_pago = :id_metodo_pago";
        $parametros[':id_metodo_pago'] = (int) $filtros['id_metodo_pago'];
    }

    if (!empty($filtros['fecha_desde'])) {
        $sql .= " AND m.fecha >= :fecha_desde";
        $parametros[':fecha_desde'] = $filtros['fecha_desde'];
    }

    if (!empty($filtros['fecha_hasta'])) {
        $sql .= " AND m.fecha <= :fecha_hasta";
        $parametros[':fecha_hasta'] = $filtros['fecha_hasta'];
    }

    $sql .= " GROUP BY fa.id, fa.nombre_frecuencia_aporte ORDER BY fa.id";

    $stmt = $conexion->prepare($sql);
    $stmt->execute($parametros);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}