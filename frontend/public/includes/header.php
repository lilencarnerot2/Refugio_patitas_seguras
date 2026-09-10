    <header>
        <a class="logo-container" href="index.php#inicio">
            <div style="background: var(--primary-color); border-radius: 50%; padding: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                <i class="fa-solid fa-paw"></i>
            </div>
            <div class="logo-text">
                <h1>PATITAS SEGURAS</h1>
                <span>Refugio de animales</span>
            </div>
        </a>
        <input type="checkbox" id="nav-toggle" class="nav-toggle">
        <nav>
            <ul>
                <li><a href="index.php#inicio">INICIO</a></li>
                <li><a href="index.php#nosotros">NOSOTROS</a></li>
                <li class="nav-dropdown">
                    <a tabindex="0">SUMATE</a>
                    <ul class="dropdown-menu">
                        <li><a href="mascotas.php#adopcion">ADOPTÁ</a></li>
                        <li><a href="mascotas.php#transito">HOGAR DE TRÁNSITO</a></li>
                        <li><a href="index.php#modal-voluntariado">SE VOLUNTARIO</a></li>
                    </ul>
                </li>
                <li class="nav-dropdown">
                    <a tabindex="0">CÓMO AYUDAR</a>
                    <ul class="dropdown-menu">
                        <li><a href="index.php#modal-castracion">CASTRACIÓN</a></li>
                        <li><a href="index.php#modal-maltrato">DENUNCIA MALTRATO</a></li>
                    </ul>
                </li>
                <li><a href="index.php#contacto">CONTACTO</a></li>
            </ul>
        </nav>
        <div class="header-actions">
            <a class="btn-donate" href="index.php#modal-donaciones">
                <i class="fa-solid fa-heart"></i> DONAR
            </a>
            <label for="nav-toggle" class="menu-toggle" aria-label="Menú">
                <i class="fa-solid fa-bars menu-icon-bars"></i>
                <i class="fa-solid fa-xmark menu-icon-close"></i>
            </label>
        </div>
    </header>
