<?php
$paginaAdmin = $paginaAdmin ?? '';
$salir = '/public/login.php?salir=1';
?>
<input type="checkbox" id="nav-toggle" class="nav-toggle">
<header>
    <label for="nav-toggle" class="menu-toggle" aria-label="Menú">
        <i class="bi bi-list menu-icon-bars"></i>
        <i class="bi bi-x-lg menu-icon-close"></i>
    </label>

    <a class="logo-container" href="dashboard.php">
        <div class="logo-icon"><span class="icono-pata"></span></div>
        <div class="logo-text">
            <h1>PATITAS SEGURAS</h1>
            <span>Panel administrativo</span>
        </div>
    </a>

    <div class="header-actions">
        <a class="btn-outline" href="/index.php">SITIO</a>
        <a class="btn-outline" href="<?php echo $salir; ?>">SALIR</a>
    </div>
</header>

<nav>
    <ul>
        <li>
            <a href="dashboard.php"<?php echo $paginaAdmin === 'dashboard' ? ' class="active"' : ''; ?>>
                <i class="bi bi-house-door"></i> Dashboard
            </a>
        </li>
        <li>
            <details>
                <summary<?php echo in_array($paginaAdmin, ['mascotas', 'mascota_form', 'mascota_editar', 'mascota_baja'], true) ? ' class="active"' : ''; ?>>
                    <span class="icono-pata"></span> Mascotas
                </summary>
                <a href="mascotas.php"<?php echo $paginaAdmin === 'mascotas' ? ' class="active"' : ''; ?>>Listado</a>
                <a href="mascota_form.php"<?php echo $paginaAdmin === 'mascota_form' ? ' class="active"' : ''; ?>>Nueva mascota</a>
                <a href="mascota_editar.php"<?php echo $paginaAdmin === 'mascota_editar' ? ' class="active"' : ''; ?>>Editar mascota</a>
                <a href="mascota_baja.php"<?php echo $paginaAdmin === 'mascota_baja' ? ' class="active"' : ''; ?>>Dar de baja</a>
            </details>
        </li>
        <li>
            <a href="solicitudes.php"<?php echo in_array($paginaAdmin, ['solicitudes', 'solicitudes_detalle'], true) ? ' class="active"' : ''; ?>>
                <i class="bi bi-file-earmark-text"></i> Solicitudes
            </a>
        </li>
        <li>
            <a href="hogares_transito.php"<?php echo $paginaAdmin === 'hogares_transito' ? ' class="active"' : ''; ?>>
                <i class="bi bi-house"></i> Hogares de tránsito
            </a>
        </li>
        <li>
            <a href="donaciones.php"<?php echo $paginaAdmin === 'donaciones' ? ' class="active"' : ''; ?>>
                <i class="bi bi-heart-fill"></i> Donaciones
            </a>
        </li>
        <li>
            <a href="salud.php"<?php echo in_array($paginaAdmin, ['salud', 'ficha_medica'], true) ? ' class="active"' : ''; ?>>
                <i class="bi bi-heart-pulse"></i> Control de salud
            </a>
        </li>
        <li>
            <a href="usuarios.php"<?php echo in_array($paginaAdmin, ['usuarios', 'usuario_detalle'], true) ? ' class="active"' : ''; ?>>
                <i class="bi bi-people-fill"></i> Usuarios
            </a>
        </li>
        <li class="nav-sitio">
            <a href="/index.php">
                <i class="bi bi-globe"></i> Sitio
            </a>
        </li>
    </ul>
</nav>
