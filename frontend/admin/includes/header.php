<?php
$paginaAdmin = $paginaAdmin ?? '';
$salir = '/public/login.php?salir=1';
$esSuperadministrador = esSuperadministrador();
$menuSolicitudesActivo = $paginaAdmin === 'solicitudes'
    || substr($paginaAdmin, -8) === '_detalle'
    || strpos($paginaAdmin, 'seguimientos_') === 0;
?>
<header>
    <div class="menu-botones">
        <input type="checkbox" id="menu-admin-toggle" class="nav-toggle">
        <label for="menu-admin-toggle" class="menu-toggle menu-abrir" aria-label="Menú">
            <i class="bi bi-list"></i>
        </label>
        <label for="menu-admin-toggle" class="menu-toggle menu-cerrar" aria-label="Cerrar menú">
            <i class="bi bi-x-lg"></i>
        </label>
    </div>

    <a class="logo-container" href="dashboard.php">
        <img class="logo-image" src="/assets/img/LogoPatitas.jpeg" alt="">
        <div class="logo-text">
            <h1>PATITAS SEGURAS</h1>
            <span>Panel administrativo</span>
        </div>
    </a>

    <div class="header-actions">
        <a class="btn-outline" href="/index.php">SITIO</a>
        <a class="btn-outline" href="<?php echo $salir; ?>" role="button" data-bs-toggle="modal" data-bs-target="#modal-salir">SALIR</a>
    </div>
</header>

<nav id="menu-admin" class="admin-nav-panel overflow-auto" aria-label="Menú administrativo">
    <ul>
        <li>
            <a href="dashboard.php"<?php echo $paginaAdmin === 'dashboard' ? ' class="active"' : ''; ?>>
                <i class="bi bi-house-door"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="mascotas.php"<?php echo $paginaAdmin === 'mascotas' ? ' class="active"' : ''; ?>>
                <span class="icono-pata"></span> Mascotas
            </a>
        </li>
        <li>
            <a href="solicitudes.php"<?php echo $menuSolicitudesActivo ? ' class="active"' : ''; ?>>
                <i class="bi bi-file-earmark-text"></i> Solicitudes
            </a>
        </li>
        <li>
            <details<?php echo in_array($paginaAdmin, ['donaciones', 'reportes_movimiento'], true) ? ' open' : ''; ?>>
                <summary<?php echo in_array($paginaAdmin, ['donaciones', 'reportes_movimiento'], true) ? ' class="active"' : ''; ?>>
                    <i class="bi bi-heart-fill"></i> Donaciones
                </summary>
                <a href="donaciones.php"<?php echo $paginaAdmin === 'donaciones' ? ' class="active"' : ''; ?>>Aportes</a>
                <a href="reportes_movimiento.php"<?php echo $paginaAdmin === 'reportes_movimiento' ? ' class="active"' : ''; ?>>Movimiento</a>
            </details>
        </li>
        <li>
            <a href="salud.php"<?php echo in_array($paginaAdmin, ['salud', 'ficha_medica'], true) ? ' class="active"' : ''; ?>>
                <i class="bi bi-heart-pulse"></i> Control de salud
            </a>
        </li>
        <?php if ($esSuperadministrador) { ?>
        <li>
            <details>
                <summary<?php echo in_array($paginaAdmin, ['usuarios', 'usuario_detalle', 'usuario_editar'], true) ? ' class="active"' : ''; ?>>
                    <i class="bi bi-people-fill"></i> Usuarios
                </summary>
                <a href="usuarios.php"<?php echo $paginaAdmin === 'usuarios' ? ' class="active"' : ''; ?>>Listado</a>
                <a href="usuario_editar.php"<?php echo $paginaAdmin === 'usuario_editar' ? ' class="active"' : ''; ?>>Editar usuario</a>
            </details>
        </li>
 
        <?php } else { ?>
        <li>
            <a href="usuarios.php"<?php echo in_array($paginaAdmin, ['usuarios', 'usuario_detalle'], true) ? ' class="active"' : ''; ?>>
                <i class="bi bi-people-fill"></i> Usuarios
            </a>
        </li>
        <?php } ?>

        <li>
            <a href="configuracion.php"<?php echo $paginaAdmin === 'configuracion' ? ' class="active"' : ''; ?>>
                <i class="bi bi-gear-fill"></i> Configuración
            </a>
        </li>

        <?php if ($esSuperadministrador) { ?>
        <li>
            <a href="auditorias.php"<?php echo in_array($paginaAdmin, ['auditorias', 'auditoria_detalle'], true) ? ' class="active"' : ''; ?>>
                <i class="bi bi-journal-text"></i> Auditorías
            </a>
        </li>
        <li>
            <a href="configuracion.php"<?php echo $paginaAdmin === 'configuracion' ? ' class="active"' : ''; ?>>
                <i class="bi bi-gear"></i> Configuración
            </a>
        </li>
        <?php } ?>
        <li class="nav-sitio">
            <a href="/index.php">
                <i class="bi bi-globe"></i> Sitio
            </a>
        </li>
        <li class="nav-salir">
            <a href="<?php echo $salir; ?>" role="button" data-bs-toggle="modal" data-bs-target="#modal-salir">
                <i class="bi bi-box-arrow-right"></i> Salir
            </a>
        </li>
    </ul>
</nav>

<?php require __DIR__ . '/../../public/includes/modal_salir.php'; ?>
