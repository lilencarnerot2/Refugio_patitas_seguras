<?php

require_once __DIR__ . '/../../../config/database.php';

function crear_auditoria(
    ?int $id_usuario,
    string $nombre_tabla,
    string $accion,
    ?array $valores_anteriores,
    ?array $valores_nuevos,
    ?string $ip_origen
): int {
    $conexion = conectarDB();

    $sql = "
        INSERT INTO auditorias (
            id_usuario,
            nombre_tabla,
            accion,
            valores_anteriores,
            valores_nuevos,
            ip_origen
        )
        VALUES (
            :id_usuario,
            :nombre_tabla,
            :accion,
            CAST(:valores_anteriores AS jsonb),
            CAST(:valores_nuevos AS jsonb),
            :ip_origen
        )
        RETURNING id
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':nombre_tabla' => $nombre_tabla,
        ':accion' => $accion,
        ':valores_anteriores' => $valores_anteriores !== null
            ? json_encode($valores_anteriores, JSON_UNESCAPED_UNICODE)
            : null,
        ':valores_nuevos' => $valores_nuevos !== null
            ? json_encode($valores_nuevos, JSON_UNESCAPED_UNICODE)
            : null,
        ':ip_origen' => $ip_origen
    ]);

    return (int) $stmt->fetchColumn();
}

function obtener_auditoria_por_id(
    int $id
): array|false {

    $conexion = conectarDB();

    $sql = "
        SELECT
            a.id,
            a.id_usuario,
            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            r.nombre_rol,
            a.nombre_tabla,
            a.accion,
            a.fecha_hora,
            a.valores_anteriores,
            a.valores_nuevos,
            a.ip_origen
        FROM auditorias a

        LEFT JOIN usuarios u
            ON u.id = a.id_usuario

        LEFT JOIN roles r
            ON r.id = u.id_rol

        WHERE a.id = :id
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute([
        ':id' => $id
    ]);

    return $consulta->fetch(PDO::FETCH_ASSOC);
}


function obtener_auditorias(
    array $filtros = []
): array {

    $conexion = conectarDB();

    $condiciones = [];
    $parametros = [];

    if (!empty($filtros['id_usuario'])) {
        $condiciones[] =
            'a.id_usuario = :id_usuario';

        $parametros[':id_usuario'] =
            $filtros['id_usuario'];
    }

    if (!empty($filtros['nombre_tabla'])) {
        $condiciones[] =
            'a.nombre_tabla = :nombre_tabla';

        $parametros[':nombre_tabla'] =
            $filtros['nombre_tabla'];
    }

    if (!empty($filtros['accion'])) {
        $condiciones[] =
            'a.accion = :accion';

        $parametros[':accion'] =
            $filtros['accion'];
    }

    if (!empty($filtros['fecha_desde'])) {
        $condiciones[] =
            'a.fecha_hora >= :fecha_desde';

        $parametros[':fecha_desde'] =
            $filtros['fecha_desde'] . ' 00:00:00';
    }

    if (!empty($filtros['fecha_hasta'])) {
        $condiciones[] =
            'a.fecha_hora <= :fecha_hasta';

        $parametros[':fecha_hasta'] =
            $filtros['fecha_hasta'] . ' 23:59:59';
    }

    $sql = "
        SELECT
            a.id,
            a.id_usuario,
            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            r.nombre_rol,
            a.nombre_tabla,
            a.accion,
            a.fecha_hora,
            a.valores_anteriores,
            a.valores_nuevos,
            a.ip_origen
        FROM auditorias a

        LEFT JOIN usuarios u
            ON u.id = a.id_usuario

        LEFT JOIN roles r
            ON r.id = u.id_rol
    ";

    if (!empty($condiciones)) {
        $sql .= "
            WHERE " .
            implode(' AND ', $condiciones);
    }

    $sql .= "
        ORDER BY a.fecha_hora DESC, a.id DESC
    ";

    $consulta = $conexion->prepare($sql);

    $consulta->execute(
        $parametros
    );

    return $consulta->fetchAll(
        PDO::FETCH_ASSOC
    );
}