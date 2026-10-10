<?php

function usuarioSesion(): ?array
{
    if (empty($_COOKIE['usuario_id'])) {
        return null;
    }

    return [
        'id' => $_COOKIE['usuario_id'],
        'nombre' => $_COOKIE['usuario_nombre'] ?? '',
        'apellido' => $_COOKIE['usuario_apellido'] ?? '',
        'email' => $_COOKIE['usuario_email'] ?? '',
        'id_rol' => $_COOKIE['usuario_rol'] ?? ''
    ];
}

function usuarioAutenticado(): bool
{
    return usuarioSesion() !== null;
}

function esPersonalAdmin(): bool
{
    return !empty($_COOKIE['usuario_acceso_admin']);
}

function esSuperadministrador(): bool
{
    return !empty($_COOKIE['usuario_acceso_superadmin']);
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
    if (esSuperadministrador()) {
        return;
    }

    if (esPersonalAdmin()) {
        header('Location: /admin/dashboard.php');
        exit;
    }

    exigirPersonalAdmin();
}
