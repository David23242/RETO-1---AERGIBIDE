<main class="main-content">
    <div class="container-titulo-tema">
        <h1 class="temas-titulo">TEMAS</h1>
        <a href="index.php?controller=pregunta&action=create" class="btn btnCrear">Crear Pregunta</a>
    </div>

    <div class="containerTema">
        <div class="temas">
            <?php
            $temas = $dataToView["temas"] ?? [];
            foreach ($temas as $tema): ?>
                <a href="index.php?controller=pregunta&action=list&id_tema=<?= $tema['id'] ?>">
                <div class="tema">


                        <?php echo htmlspecialchars($tema['nombre'] ?? ''); ?>


                </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="sidebar">
            <div class="ultimas-publicaciones">
                <h3>Últimas Publicaciones</h3>
                <?php
                $publicaciones = $dataToView["publicaciones"] ?? [];
                if (!empty($publicaciones)): ?>
                    <?php foreach ($publicaciones as $publicacion): ?>
                        <a href="index.php?controller=respuesta&action=view&id_pregunta=<?=$publicacion["id"]?>" class="publicacion">
                            <div class="user-avatar">
                                <?php if (!empty($publicacion['foto_perfil'])): ?>
                                    <img src="<?= htmlspecialchars($publicacion['foto_perfil']) ?>" alt="Foto de perfil">
                                <?php else: ?>
                                    <img src="assets/img/fotoPorDefecto.png" alt="Icono predeterminado">
                                <?php endif; ?>
                            </div>
                            <div class="texto">
                                <p><strong><?= htmlspecialchars($publicacion['titulo']) ?></strong></p>
                                <p><?= htmlspecialchars(implode(' ', array_slice(explode(' ', $publicacion['texto']), 0, 6))) ?>...</p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay publicaciones recientes</p>
                <?php endif; ?>
            </div>

            <div class="estadisticas">
                <h3>Estadísticas</h3>
                <p>Total de publicaciones: <?= htmlspecialchars($dataToView['totalPublicaciones'] ?? 0) ?></p>
                <p>Total de usuarios: <?= htmlspecialchars($dataToView['totalUsuarios'] ?? 0) ?></p>
            </div>
        </div>
    </div>

    <!-- Paginación -->
    <div class="paginacion">
        <?php for ($i = 1; $i <= $dataToView['totalPaginas']; $i++): ?>
            <a href="?controller=tema&action=mostrarTemas&pagina=<?= $i ?>" class="page-btn <?= ($i == $dataToView['paginaActual']) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
</main>