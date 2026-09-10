<?php
$tituloPagina = 'Formulario de mascota - Patitas Seguras';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tituloPagina; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css?t=<?php echo filemtime(__DIR__ . '/../assets/css/styles.css'); ?>">
</head>
<body>

    <section>
        <div class="page-intro">
            <h2>Formulario de mascota</h2>
            <p>Las especies y razas salen de la API. La raza depende de la especie.</p>
        </div>

        <form id="form-mascota" class="public-form">
            <div class="mb-3">
                <label class="form-label" for="id_especie">Especie</label>
                <select class="form-select" id="id_especie" name="id_especie">
                    <option value="">Seleccioná una especie</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label" for="id_raza">Raza</label>
                <select class="form-select" id="id_raza" name="id_raza">
                    <option value="">Seleccioná una raza</option>
                </select>
            </div>
        </form>
    </section>

    <script>
        window.APP_CONFIG = {
            backendUrl: <?php echo json_encode(getenv('BACKEND_SERVICE_URL') ?: ''); ?>
        };
    </script>
    <script src="../assets/js/api.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/api.js'); ?>"></script>
    <script src="../assets/js/mascotas.js?t=<?php echo filemtime(__DIR__ . '/../assets/js/mascotas.js'); ?>"></script>
</body>
</html>
