<?php
$paginadorAncla = $paginadorAncla ?? '#';
$paginadorEtiqueta = $paginadorEtiqueta ?? 'Paginación';
$paginadorModeloId = $paginadorModeloId ?? null;
?>
            <nav class="paginador" data-paginador aria-label="<?php echo $paginadorEtiqueta; ?>" hidden>
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" data-pagina-anterior href="<?php echo $paginadorAncla; ?>" aria-label="Página anterior"><i class="bi bi-chevron-left"></i></a>
                    </li>
                    <?php if ($paginadorModeloId) { ?>
                    <li class="page-item" id="<?php echo $paginadorModeloId; ?>" data-pagina-modelo hidden>
                        <a class="page-link" href="<?php echo $paginadorAncla; ?>"></a>
                    </li>
                    <?php } else { ?>
                    <li class="page-item" data-pagina-modelo hidden>
                        <button type="button" class="page-link"></button>
                    </li>
                    <?php } ?>
                    <li class="page-item">
                        <a class="page-link" data-pagina-siguiente href="<?php echo $paginadorAncla; ?>" aria-label="Página siguiente"><i class="bi bi-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>
