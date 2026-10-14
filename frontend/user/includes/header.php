<?php
$salir = '/public/login.php?salir=1';
$paginaPerfil = $paginaPerfil ?? 'datos';
?>
<header>
    <div class="menu-botones">
        <input type="checkbox" id="menu-perfil-toggle" class="nav-toggle">
        <label for="menu-perfil-toggle" class="menu-toggle menu-abrir" aria-label="Menú">
            <i class="bi bi-list"></i>
        </label>
        <label for="menu-perfil-toggle" class="menu-toggle menu-cerrar" aria-label="Cerrar menú">
            <i class="bi bi-x-lg"></i>
        </label>
    </div>

    <a class="logo-container" href="#vista-datos" data-bs-toggle="tab" data-bs-target="#vista-datos">
        <div class="logo-icon"><span class="icono-pata"></span></div>
        <div class="logo-text">
            <h1>PATITAS SEGURAS</h1>
            <span>Mi perfil</span>
        </div>
    </a>

    <div class="header-actions"></div>
</header>

<nav id="menu-perfil" class="overflow-auto">
    <ul role="tablist">
        <li>
            <a href="#vista-datos" data-bs-toggle="tab" data-bs-target="#vista-datos"<?php echo $paginaPerfil === 'datos' ? ' class="active"' : ''; ?>>
                <i class="bi bi-person-fill"></i> Datos
            </a>
        </li>
        <li>
            <a href="#vista-movimientos" data-bs-toggle="tab" data-bs-target="#vista-movimientos"<?php echo $paginaPerfil === 'movimientos' ? ' class="active"' : ''; ?>>
                <i class="bi bi-list-ul"></i> Movimientos
            </a>
        </li>
        <li>
            <a href="#vista-solicitudes" data-bs-toggle="tab" data-bs-target="#vista-solicitudes"<?php echo $paginaPerfil === 'solicitudes' ? ' class="active"' : ''; ?>>
                <i class="bi bi-file-earmark-text"></i> Mis solicitudes
            </a>
        </li>
        <li class="nav-panel" hidden>
            <a href="/admin/dashboard.php">
                <i class="bi bi-speedometer2"></i> Panel
            </a>
        </li>
        <li class="nav-sitio">
            <a href="/index.php">
                <i class="bi bi-globe"></i> Sitio
            </a>
        </li>
        <li class="nav-salir">
            <a href="<?php echo $salir; ?>">
                <i class="bi bi-box-arrow-right"></i> Salir
            </a>
        </li>
    </ul>
</nav>
