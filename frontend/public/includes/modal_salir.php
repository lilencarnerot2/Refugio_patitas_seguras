<?php
$salir = $salir ?? '/public/login.php?salir=1';
?>
<div class="modal fade" id="modal-salir" tabindex="-1" aria-labelledby="modal-salir-titulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 rounded-4 text-center">
            <div class="modal-body p-4">
                <span class="modal-salir-icono rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                    <i class="bi bi-box-arrow-right"></i>
                </span>
                <h5 class="modal-title mb-2" id="modal-salir-titulo">¿Querés salir?</h5>
                <p class="text-body-secondary small mb-4">Vas a cerrar tu sesión en Patitas Seguras.</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancelar</button>
                    <a class="btn btn-danger rounded-pill px-4" href="<?php echo $salir; ?>">Sí, salir</a>
                </div>
            </div>
        </div>
    </div>
</div>
