<?php
$salir = '/public/login.php?salir=1';
$paginaPerfil = $paginaPerfil ?? 'datos';
$usuarioPerfil = usuarioSesion() ?? [];
$nombrePerfil = trim(($usuarioPerfil['nombre'] ?? '') . ' ' . ($usuarioPerfil['apellido'] ?? ''));
$inicialPerfil = mb_strtoupper(mb_substr($usuarioPerfil['nombre'] ?? '', 0, 1));
?>
<header class="perfil-topbar">
    <div class="d-flex align-items-center gap-2">
        <div class="menu-botones">
            <input type="checkbox" id="menu-perfil-toggle" class="nav-toggle">
            <label for="menu-perfil-toggle" class="menu-toggle menu-abrir" aria-label="Menú">
                <i class="bi bi-list"></i>
            </label>
            <label for="menu-perfil-toggle" class="menu-toggle menu-cerrar" aria-label="Cerrar menú">
                <i class="bi bi-x-lg"></i>
            </label>
        </div>

        <a class="logo-container" href="mi_perfil.php">
            <img class="logo-image" src="/assets/img/LogoPatitas.jpeg" alt="">
            <div class="logo-text">
                <h1>PATITAS SEGURAS</h1>
                <span>Mi perfil</span>
            </div>
        </a>
    </div>

    <div class="header-actions dropdown">
        <button class="btn perfil-usuario dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
            <span class="perfil-avatar rounded-circle d-inline-flex align-items-center justify-content-center"><?php echo $inicialPerfil; ?></span>
            <span class="d-none d-sm-inline"><?php echo $nombrePerfil; ?></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2 perfil-panel-dropdown">
            <li>
                <a class="dropdown-item rounded-3" href="/index.php">
                    <i class="bi bi-globe"></i> Volver al sitio
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <button class="dropdown-item rounded-3 text-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal-salir">
                    <i class="bi bi-box-arrow-right"></i> Salir
                </button>
            </li>
        </ul>
    </div>
</header>

<nav id="menu-perfil" class="position-fixed start-0 overflow-auto">
    <ul>
        <li>
            <label for="menu-perfil-toggle">
                <a href="mi_perfil.php"<?php echo $paginaPerfil === 'datos' ? ' class="active"' : ''; ?>>
                    <i class="bi bi-person"></i> Datos
                </a>
            </label>
        </li>
        <li>
            <label for="menu-perfil-toggle">
                <a href="mis_movimientos.php"<?php echo $paginaPerfil === 'movimientos' ? ' class="active"' : ''; ?>>
                    <i class="bi bi-cash-coin"></i> Mis donaciones
                </a>
            </label>
        </li>
        <li>
            <label for="menu-perfil-toggle">
                <a href="mis_solicitudes.php"<?php echo $paginaPerfil === 'solicitudes' ? ' class="active"' : ''; ?>>
                    <i class="bi bi-file-earmark-text"></i> Mis solicitudes
                </a>
            </label>
        </li>
    </ul>

    <div class="menu-perfil-lema text-center mt-auto">
        <i class="fa-solid fa-paw"></i>
        <p class="mb-0">Juntos hacemos<br>la diferencia</p>
    </div>
</nav>

<?php require __DIR__ . '/../../public/includes/modal_salir.php'; ?>
