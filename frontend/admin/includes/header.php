<?php
$paginaAdmin = $paginaAdmin ?? '';
?>
<input type="checkbox" id="nav-toggle" class="nav-toggle">
<header>
    <label for="nav-toggle" class="menu-toggle" aria-label="Menú">
        <i class="fa-solid fa-bars menu-icon-bars"></i>
        <i class="fa-solid fa-xmark menu-icon-close"></i>
    </label>

    <a class="logo-container" href="dashboard.php">
        <div class="logo-icon"><i class="fa-solid fa-paw"></i></div>
        <div class="logo-text">
            <h1>PATITAS SEGURAS</h1>
            <span>Panel administrativo</span>
        </div>
    </a>

    <div class="header-actions">
        <a class="btn-outline" href="/index.php">SITIO</a>
    </div>
</header>

<nav>
    <ul>
        <li>
            <a href="dashboard.php"<?php echo $paginaAdmin === 'dashboard' ? ' class="active"' : ''; ?>>
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
        </li>
        <li>
            <details>
                <summary<?php echo in_array($paginaAdmin, ['mascotas', 'mascota_form', 'mascota_editar'], true) ? ' class="active"' : ''; ?>>
                    <i class="fa-solid fa-paw"></i> Mascotas
                </summary>
                <a href="mascotas.php"<?php echo $paginaAdmin === 'mascotas' ? ' class="active"' : ''; ?>>Listado</a>
                <a href="mascota_form.php"<?php echo $paginaAdmin === 'mascota_form' ? ' class="active"' : ''; ?>>Nueva mascota</a>
                <a href="mascota_editar.php"<?php echo $paginaAdmin === 'mascota_editar' ? ' class="active"' : ''; ?>>Editar mascota</a>
            </details>
        </li>
        <li>
            <a href="solicitudes.php"<?php echo in_array($paginaAdmin, ['solicitudes', 'solicitudes_detalle'], true) ? ' class="active"' : ''; ?>>
                <i class="fa-solid fa-file-lines"></i> Solicitudes
            </a>
        </li>
        <li>
            <a href="hogares_transito.php"<?php echo $paginaAdmin === 'hogares_transito' ? ' class="active"' : ''; ?>>
                <i class="fa-solid fa-house-chimney"></i> Hogares de tránsito
            </a>
        </li>
        <li>
            <a href="donaciones.php"<?php echo $paginaAdmin === 'donaciones' ? ' class="active"' : ''; ?>>
                <i class="fa-solid fa-heart"></i> Donaciones
            </a>
        </li>
        <li>
            <a href="salud.php"<?php echo $paginaAdmin === 'salud' ? ' class="active"' : ''; ?>>
                <i class="fa-solid fa-heart-pulse"></i> Control de salud
            </a>
        </li>
        <li>
            <a href="usuarios.php"<?php echo $paginaAdmin === 'usuarios' ? ' class="active"' : ''; ?>>
                <i class="fa-solid fa-users"></i> Usuarios
            </a>
        </li>
        <li>
            <a href="configuracion.php"<?php echo $paginaAdmin === 'configuracion' ? ' class="active"' : ''; ?>>
                <i class="fa-solid fa-gear"></i> Configuración
            </a>
        </li>
        <li class="nav-sitio">
            <a href="/index.php">
                <i class="fa-solid fa-globe"></i> Sitio
            </a>
        </li>
    </ul>
</nav>
