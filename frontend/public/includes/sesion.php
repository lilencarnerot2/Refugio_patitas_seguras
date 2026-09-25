<?php

function usuarioSesion(): ?array
{
    if (empty($_COOKIE['usuario_id']) || empty($_COOKIE['usuario_nombre_rol'])) {
        return null;
    }

    return [
        'id' => $_COOKIE['usuario_id'],
        'nombre' => $_COOKIE['usuario_nombre'] ?? '',
        'apellido' => $_COOKIE['usuario_apellido'] ?? '',
        'email' => $_COOKIE['usuario_email'] ?? '',
        'id_rol' => $_COOKIE['usuario_rol'] ?? '',
        'nombre_rol' => $_COOKIE['usuario_nombre_rol']
    ];
}

function usuarioAutenticado(): bool
{
    return usuarioSesion() !== null;
}

function nombreRolSesion(): string
{
    $usuario = usuarioSesion();

    return $usuario['nombre_rol'] ?? '';
}

function esRol(string $nombreRol): bool
{
    return nombreRolSesion() === $nombreRol;
}

function esPersonalAdmin(): bool
{
    return esRol('Administrador') || esRol('Superadministrador');
}

function exigirPersonalAdmin(): void
{
    if (esPersonalAdmin()) {
        return;
    }

    if (usuarioAutenticado()) {
        header('Location: /index.php');
        exit;
    }

    header('Location: /public/login.php');
    exit;
}

function exigirSuperadministrador(): void
{
    if (esRol('Superadministrador')) {
        return;
    }

    if (esPersonalAdmin()) {
        header('Location: /admin/dashboard.php');
        exit;
    }

    exigirPersonalAdmin();
}
