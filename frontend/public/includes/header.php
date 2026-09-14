<?php
$esInicio = !empty($esInicio);
$inicio = $esInicio ? '' : 'index.php';
?><header>
        <a class="logo-container" href="<?php echo $esInicio ? '#inicio' : 'index.php'; ?>">
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
                <li><a href="<?php echo $esInicio ? '#inicio' : 'index.php'; ?>">INICIO</a></li>
                <li><a href="<?php echo $inicio; ?>#nosotros">NOSOTROS</a></li>
                <li class="nav-dropdown">
                    <details>
                        <summary>SUMATE</summary>
                        <ul class="dropdown-menu">
                            <li><a href="mascotas.php#adopcion">ADOPTÁ</a></li>
                            <li><a href="mascotas.php#transito">HOGAR DE TRÁNSITO</a></li>
                            <li><a href="<?php echo $inicio; ?>#modal-voluntariado">SE VOLUNTARIO</a></li>
                        </ul>
                    </details>
                </li>
                <li class="nav-dropdown">
                    <details>
                        <summary>CÓMO AYUDAR</summary>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo $inicio; ?>#modal-castracion">CASTRACIÓN</a></li>
                            <li><a href="<?php echo $inicio; ?>#modal-maltrato">DENUNCIA MALTRATO</a></li>
                        </ul>
                    </details>
                </li>
                <li><a href="<?php echo $inicio; ?>#contacto">CONTACTO</a></li>
                <li class="nav-actions">
                    <a class="btn-outline" href="login.php">INGRESAR</a>
                    <a class="btn-donate" href="<?php echo $esInicio ? '#modal-donaciones' : 'index.php#modal-donaciones'; ?>">
                        <i class="fa-solid fa-heart"></i> DONAR
                    </a>
                </li>
            </ul>
        </nav>
        <div class="header-actions">
            <a class="btn-outline" href="login.php">INGRESAR</a>
            <a class="btn-donate" href="<?php echo $esInicio ? '#modal-donaciones' : 'index.php#modal-donaciones'; ?>">
                <i class="fa-solid fa-heart"></i> DONAR
            </a>
            <label for="nav-toggle" class="menu-toggle" aria-label="Menú">
                <i class="fa-solid fa-bars menu-icon-bars"></i>
                <i class="fa-solid fa-xmark menu-icon-close"></i>
            </label>
        </div>
    </header>