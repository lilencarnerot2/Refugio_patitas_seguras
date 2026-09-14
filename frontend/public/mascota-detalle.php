<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascota - Patitas Seguras</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css?v=menu7">
</head>
<body>

    <header>
        <a class="logo-container" href="index.php">
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
                <li><a href="index.php">INICIO</a></li>
                <li><a href="index.php#nosotros">NOSOTROS</a></li>
                <li class="nav-dropdown">
                    <input type="checkbox" id="drop-sumate" class="nav-drop-toggle">
                    <label for="drop-sumate">SUMATE</label>
                    <ul class="dropdown-menu">
                        <li><a href="mascotas.php#adopcion">ADOPTÁ</a></li>
                        <li><a href="mascotas.php#transito">HOGAR DE TRÁNSITO</a></li>
                        <li><a href="index.php#modal-voluntariado">SE VOLUNTARIO</a></li>
                    </ul>
                </li>
                <li class="nav-dropdown">
                    <input type="checkbox" id="drop-ayudar" class="nav-drop-toggle">
                    <label for="drop-ayudar">CÓMO AYUDAR</label>
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

    <section>
        <div class="catalog-empty">
            <i class="fa-solid fa-paw"></i>
            <h4>Mascota no encontrada</h4>
            <p>Volvé al listado para ver las mascotas disponibles.</p>
            <a class="btn-detail" href="mascotas.php">Volver a mascotas</a>
        </div>
    </section>

    <footer>
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h3>PATITAS SEGURAS</h3>
                <p style="font-size: 12px; color: #ccc;">Refugio de animales</p>
            </div>
            <div>
                <p style="font-size: 13px;"><i class="fa-solid fa-envelope"></i> hola@patitaseguras.org</p>
                <p style="font-size: 13px; margin-top: 5px;"><i class="fa-solid fa-location-dot"></i> Merlo, Buenos Aires, Argentina</p>
            </div>
        </div>
    </footer>

</body>
</html>
