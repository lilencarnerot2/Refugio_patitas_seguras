<?php

require_once __DIR__ . '/../repositories/auditorias.php';

function registrar_auditoria(
    string $nombre_tabla,
    string $accion,
    ?array $valores_anteriores = null,
    ?array $valores_nuevos = null
): int {
    $acciones_permitidas = [
        'INSERT',
        'UPDATE',
        'DELETE'
    ];

    $accion = strtoupper(trim($accion));
    $nombre_tabla = trim($nombre_tabla);

    if ($nombre_tabla === '') {
        throw new InvalidArgumentException(
            'El nombre de la tabla es obligatorio.'
        );
    }

    if (mb_strlen($nombre_tabla) > 50) {
        throw new InvalidArgumentException(
            'El nombre de la tabla no puede superar los 50 caracteres.'
        );
    }

    if (!in_array($accion, $acciones_permitidas, true)) {
        throw new InvalidArgumentException(
            'La acción de auditoría no es válida.'
        );
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $id_usuario = isset($_SESSION['usuario_id'])
        ? (int) $_SESSION['usuario_id']
        : null;

    $ip_origen = $_SERVER['REMOTE_ADDR'] ?? null;

    if ($ip_origen !== null && mb_strlen($ip_origen) > 45) {
        $ip_origen = substr($ip_origen, 0, 45);
    }

    return crear_auditoria(
        $id_usuario,
        $nombre_tabla,
        $accion,
        $valores_anteriores,
        $valores_nuevos,
        $ip_origen
    );
}