<?php
$paginaAdmin = $paginaAdmin ?? '';
$esMascotas = in_array($paginaAdmin, ['mascotas', 'mascota_form', 'salud'], true);
$esGestion = in_array($paginaAdmin, ['solicitudes', 'hogares_transito', 'donaciones', 'usuarios'], true);
?>
<header>
    <a class="logo-container" href="dashboard.php">
        <div class="logo-icon"><i class="fa-solid fa-paw"></i></div>
        <div class="logo-text">
            <h1>PATITAS SEGURAS</h1>
            <span>Panel administrativo</span>
        </div>
    </a>

    <!-- Checkbox + label: abre el menú en celular. Solo CSS, sin JS -->
    <input type="checkbox" id="nav-toggle" class="nav-toggle">

    <nav>
        <ul>
            <li>
                <a href="dashboard.php"<?php echo $paginaAdmin === 'dashboard' ? ' class="active"' : ''; ?>>INICIO</a>
            </li>
            <li class="nav-dropdown">
                <details>
                    <summary<?php echo $esMascotas ? ' class="active"' : ''; ?>>MASCOTAS</summary>
                    <ul class="dropdown-menu">
                        <li><a href="mascotas.php"<?php echo $paginaAdmin === 'mascotas' ? ' class="active"' : ''; ?>>Listado</a></li>
                        <li><a href="mascota_form.php"<?php echo $paginaAdmin === 'mascota_form' ? ' class="active"' : ''; ?>>Nueva mascota</a></li>
                        <li><a href="salud.php"<?php echo $paginaAdmin === 'salud' ? ' class="active"' : ''; ?>>Salud</a></li>
                    </ul>
                </details>
            </li>
            <li class="nav-dropdown">
                <details>
                    <summary<?php echo $esGestion ? ' class="active"' : ''; ?>>GESTIÓN</summary>
                    <ul class="dropdown-menu">
                        <li><a href="solicitudes.php"<?php echo $paginaAdmin === 'solicitudes' ? ' class="active"' : ''; ?>>Solicitudes</a></li>
                        <li><a href="hogares_transito.php"<?php echo $paginaAdmin === 'hogares_transito' ? ' class="active"' : ''; ?>>Hogares de tránsito</a></li>
                        <li><a href="donaciones.php"<?php echo $paginaAdmin === 'donaciones' ? ' class="active"' : ''; ?>>Donaciones</a></li>
                        <li><a href="usuarios.php"<?php echo $paginaAdmin === 'usuarios' ? ' class="active"' : ''; ?>>Usuarios</a></li>
                    </ul>
                </details>
            </li>
            <li>
                <a href="configuracion.php"<?php echo $paginaAdmin === 'configuracion' ? ' class="active"' : ''; ?>>CONFIGURACIÓN</a>
            </li>
            <li class="nav-actions">
                <a class="btn-outline" href="../public/index.php">SITIO</a>
            </li>
        </ul>
    </nav>

    <div class="header-actions">
        <a class="btn-outline" href="../public/index.php">SITIO</a>
        <label for="nav-toggle" class="menu-toggle" aria-label="Menú">
            <i class="fa-solid fa-bars menu-icon-bars"></i>
            <i class="fa-solid fa-xmark menu-icon-close"></i>
        </label>
    </div>
</header>
