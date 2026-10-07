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