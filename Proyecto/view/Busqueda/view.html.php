<div class="content-busqueda">
    <?php

    $termino = $dataToView['termino'] ?? ''; // Si no está definido, usa un string vacío
    $resultados = $dataToView['resultados'] ?? []; // Usar un array vacío si no hay resultados
    $pagina = $dataToView['pagina'] ?? 1; // Página actual
    $totalResultados = $dataToView['totalResultados'] ?? 0; // Total de resultados
    $resultadosPorPagina = 10; // Cantidad de resultados por página
    $totalPaginas = ceil($totalResultados / $resultadosPorPagina); // Total de páginas

    // Función para formatear la fecha en español
    function formatearFecha($fecha) {
        $fechaObj = new DateTime($fecha);
        $ahora = new DateTime();
        $diferencia = $ahora->diff($fechaObj);

        if ($diferencia->d === 0) {
            return 'Hoy a las ' . $fechaObj->format('H:i');
        } elseif ($diferencia->d === 1) {
            return 'Ayer a las ' . $fechaObj->format('H:i');
        } else {
            // Formato de fecha sin usar strftime ni IntlDateFormatter
            $meses = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
            $dias = ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"];

            $diaSemana = $dias[$fechaObj->format('w')];
            $dia = $fechaObj->format('d');
            $mes = $meses[$fechaObj->format('n') - 1];
            $año = $fechaObj->format('Y');

            return "$diaSemana, $dia de $mes de $año";
        }
    }
    ?>

    <h2 class="search-title">Resultados de búsqueda</h2>
    <?php if (!empty($resultados)): ?>
        <ul class="results-list">
            <?php foreach ($resultados as $resultado): ?>
                <li class="result-item">
                    <a href="index.php?controller=respuesta&action=view&id_pregunta=<?php echo htmlspecialchars($resultado['pregunta_id'] ?? ''); ?>" class="result-link">
                        <strong class="result-title"><?= htmlspecialchars($resultado['pregunta_titulo'] ?? 'Sin título') ?></strong>

                    <div class="resultado-detalles">
                        <div class="izquierda details-left">
                            <small class="details-text">
                                <span class="details-label">Empezado por:</span> <span class="details-value"><?= htmlspecialchars($resultado['autor_pregunta'] ?? 'Desconocido') ?></span><br>
                                <span class="details-label">Fecha de primera publicación:</span> <span class="details-value"><?= htmlspecialchars(isset($resultado['fecha_primera_publicacion']) ? formatearFecha($resultado['fecha_primera_publicacion']) : 'No disponible') ?></span>
                            </small>
                        </div>
                        <div class="derecha details-right">
                            <small class="details-text">
                                <span class="details-label">Última actualización por:</span> <span class="details-value"><?= htmlspecialchars($resultado['autor_ultima_respuesta'] ?? $resultado['autor_pregunta']) ?></span><br>
                                <span class="details-label">Fecha de última publicación:</span> <span class="details-value"><?= htmlspecialchars(isset($resultado['fecha_ultima_publicacion']) ? formatearFecha($resultado['fecha_ultima_publicacion']) : 'No disponible') ?></span>
                            </small>
                        </div>
                    </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- Paginación -->
        <?php
// Usa el operador ?? para proporcionar valores predeterminados
        $termino = $dataToView["termino"] ?? '';
        $filtro = $dataToView["filtro"] ?? 'todo';
        $orden = $dataToView["orden"] ?? 'reciente';
        $paginaActual = $dataToView["pagina"] ?? 1;
        $totalResultados = $dataToView["totalResultados"] ?? 0;
        $resultadosPorPagina = 10; // Define cuántos resultados por página quieres
        $totalPaginas = ceil($totalResultados / $resultadosPorPagina);
        ?>

        <div class="paginacion">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="index.php?controller=Busqueda&action=buscar&termino=<?= urlencode($termino) ?>&filtro=<?= urlencode($filtro) ?>&orden=<?= urlencode($orden) ?>&pagina=<?= $i ?>" class="page-btn <?= ($i == $paginaActual) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>



    <?php else: ?>
        <p>No se encontraron resultados para la búsqueda.</p>
    <?php endif; ?>
</div>
