<?php
if (empty($_COOKIE['usuario_id'])) {
    header('Location: /public/login.php');
    exit;
}

$tituloPagina = 'Mis solicitudes - Patitas Seguras';
require __DIR__ . '/../public/includes/head.php';
require __DIR__ . '/../public/includes/header.php';
?>

    <section>
    </section>

<?php require __DIR__ . '/../public/includes/footer.php'; ?>
