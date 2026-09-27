<?php
$paginaAdmin = $paginaAdmin ?? '';
$enlaceSitio = '/index.php';
$enlaceSalir = '/public/login.php?salir';
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
        <a class="btn-outline" href="<?php echo htmlspecialchars($enlaceSitio, ENT_QUOTES, 'UTF-8'); ?>">SITIO</a>
        <a class="btn-primary" href="<?php echo htmlspecialchars($enlaceSalir, ENT_QUOTES, 'UTF-8'); ?>">SALIR</a>
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
            <?php if (!empty($esSuperadmin)) { ?>
            <details>
                <summary<?php echo in_array($paginaAdmin, ['usuarios', 'usuario_detalle', 'usuario_editar'], true) ? ' class="active"' : ''; ?>>
                    <i class="bi bi-people-fill"></i> Usuarios
                </summary>
                <a href="usuarios.php"<?php echo $paginaAdmin === 'usuarios' ? ' class="active"' : ''; ?>>Listado</a>
                <a href="usuario_editar.php"<?php echo $paginaAdmin === 'usuario_editar' ? ' class="active"' : ''; ?>>Editar usuario</a>
            </details>
            <?php } else { ?>
            <a href="usuarios.php"<?php echo in_array($paginaAdmin, ['usuarios', 'usuario_detalle'], true) ? ' class="active"' : ''; ?>>
                <i class="bi bi-people-fill"></i> Usuarios
            </a>
            <?php } ?>
        </li>
        <?php if (!empty($esSuperadmin)) { ?>
        <li>
            <a href="configuracion.php"<?php echo $paginaAdmin === 'configuracion' ? ' class="active"' : ''; ?>>
                <i class="bi bi-gear-fill"></i> Configuración
            </a>
        </li>
        <?php } ?>
        <li class="nav-sitio">
            <a href="<?php echo htmlspecialchars($enlaceSitio, ENT_QUOTES, 'UTF-8'); ?>">
                <i class="bi bi-globe"></i> Sitio
            </a>
        </li>
        <li class="nav-salir">
            <a href="<?php echo htmlspecialchars($enlaceSalir, ENT_QUOTES, 'UTF-8'); ?>">
                <i class="bi bi-box-arrow-right"></i> Salir
            </a>
        </li>
    </ul>
</nav>
