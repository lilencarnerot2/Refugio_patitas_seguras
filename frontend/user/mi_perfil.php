<?php
if (empty($_COOKIE['usuario_id']) || empty($_COOKIE['usuario_nombre_rol'])) {
    header('Location: /public/login.php');
    exit;
}

$tituloPagina = 'Mi perfil - Patitas Seguras';
require __DIR__ . '/../public/includes/head.php';
require __DIR__ . '/../public/includes/header.php';
?>

    <section>
    </section>

<?php require __DIR__ . '/../public/includes/footer.php'; ?>
