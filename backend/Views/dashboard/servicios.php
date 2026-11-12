<?php
/** @var list<array<string, mixed>> $servicios */
/** @var array<string, mixed>|null $servicioEditando */
$servicios = is_array($servicios ?? null) ? $servicios : [];
$servicioEditando = is_array($servicioEditando ?? null) ? $servicioEditando : null;
$esc = static fn($valor): string => htmlspecialchars(is_scalar($valor) ? (string) $valor : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$editando = $servicioEditando !== null && !empty($servicioEditando['id_Servicio']);
$valor = static fn(string $campo, string $porDefecto = ''): string =>
    is_scalar($servicioEditando[$campo] ?? null) ? (string) $servicioEditando[$campo] : $porDefecto;
?>

<main class="container-fluid agenda-page usuarios-page servicios-page py-4">
    <header class="usuarios-header">
        <div class="usuarios-header__title">
            <div class="usuarios-header__icon">
                <i class="fa-solid fa-list-check" aria-hidden="true"></i>
            </div>
            <div>
                <p class="usuarios-header__eyebrow">Administración</p>
                <h1 class="h3 mb-1">Servicios</h1>
            </div>
        </div>
        <div class="text-secondary small">Gestioná el catálogo que se publica en la página de inicio.</div>
    </header>

    <?php if (is_string($mensaje ?? null) && $mensaje !== ''): ?>
        <div class="alert alert-success" role="status"><?= $esc($mensaje) ?></div>
    <?php endif; ?>
    <?php if (is_string($error ?? null) && $error !== ''): ?>
        <div class="alert alert-danger" role="alert"><?= $esc($error) ?></div>
    <?php endif; ?>

    <section class="card shadow-sm mt-4" aria-labelledby="formulario-servicio-title">
        <div class="card-body">
            <h2 class="h5 mb-1" id="formulario-servicio-title"><?= $editando ? 'Modificar servicio' : 'Agregar servicio' ?></h2>
            <p class="text-secondary">El nombre y el precio son obligatorios. Podés adjuntar imágenes JPG, PNG o WebP de hasta 5 MB.</p>

            <form action="<?= htmlspecialchars(url('servicios/guardar'), ENT_QUOTES, 'UTF-8') ?>" method="post" enctype="multipart/form-data" class="row g-3">
                <input type="hidden" name="csrf_token" value="<?= $esc($csrfToken ?? '') ?>">
                <input type="hidden" name="servicio_id" value="<?= $editando ? (int) $servicioEditando['id_Servicio'] : '' ?>">

                <div class="col-12 col-md-6">
                    <label for="nombre_Servicio" class="form-label">Nombre</label>
                    <input class="form-control" id="nombre_Servicio" name="nombre_Servicio" maxlength="100" required value="<?= $esc($valor('nombre_Servicio')) ?>">
                </div>
                <div class="col-12 col-md-3">
                    <label for="precio_Servicio" class="form-label">Precio</label>
                    <input class="form-control" id="precio_Servicio" name="precio_Servicio" type="number" min="0" max="99999999.99" step="0.01" required value="<?= $esc($valor('precio_Servicio')) ?>">
                </div>
                <div class="col-12 col-md-3">
                    <label for="duracion_Servicio" class="form-label">Duración</label>
                    <input class="form-control" id="duracion_Servicio" name="duracion_Servicio" maxlength="50" placeholder="Ej.: 30 minutos" value="<?= $esc($valor('duracion_Servicio')) ?>">
                </div>
                <div class="col-12">
                    <label for="descripcion_Servicio" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion_Servicio" name="descripcion_Servicio" rows="3" maxlength="65535"><?= $esc($valor('descripcion_Servicio')) ?></textarea>
                </div>
                <div class="col-12 col-md-6">
                    <label for="imagen_archivo" class="form-label">Imagen del servicio</label>
                    <input class="form-control" id="imagen_archivo" name="imagen_archivo" type="file" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp">
                    <?php if ($valor('imagenURL_Servicio') !== ''): ?>
                        <div class="servicio-media-preview mt-2">
                            <img src="<?= $esc($valor('imagenURL_Servicio')) ?>" alt="Imagen actual del servicio">
                            <label class="form-check-label">
                                <input class="form-check-input me-1" type="checkbox" name="quitar_imagen" value="1" <?= ($valor('quitar_imagen') === '1') ? 'checked' : '' ?>>
                                Quitar imagen actual
                            </label>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-12 col-md-6">
                    <label for="icono_archivo" class="form-label">Icono del servicio</label>
                    <input class="form-control" id="icono_archivo" name="icono_archivo" type="file" accept="image/jpeg,image/png,image/webp,.jpg,.jpeg,.png,.webp">
                    <?php if ($valor('iconoURL_Servicio') !== ''): ?>
                        <div class="servicio-media-preview mt-2">
                            <img src="<?= $esc($valor('iconoURL_Servicio')) ?>" alt="Icono actual del servicio">
                            <label class="form-check-label">
                                <input class="form-check-input me-1" type="checkbox" name="quitar_icono" value="1" <?= ($valor('quitar_icono') === '1') ? 'checked' : '' ?>>
                                Quitar icono actual
                            </label>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-12 d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" type="submit">
                    <i class="fa-solid <?= $editando ? 'fa-floppy-disk' : 'fa-plus' ?> me-1" aria-hidden="true"></i>
                    <?= $editando ? 'Guardar cambios' : 'Agregar servicio' ?>
                    </button>
                    <?php if ($editando): ?>
                        <a class="btn btn-outline-secondary" href="<?= htmlspecialchars(url('servicios'), ENT_QUOTES, 'UTF-8') ?>">Cancelar edición</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </section>

    <section class="card shadow-sm mt-4" aria-labelledby="lista-servicios-title">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h2 class="h5 mb-1" id="lista-servicios-title">Catálogo registrado</h2>
                    <p class="text-secondary mb-0">Los servicios activos se muestran en la landing.</p>
                </div>
                <span class="badge text-bg-light"><?= count($servicios) ?> servicio(s)</span>
            </div>

            <?php if ($servicios): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle agenda-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Servicio</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Duración</th>
                                <th scope="col">Descripción</th>
                                <th scope="col" class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($servicios as $servicio): ?>
                                <tr>
                                    <td class="fw-semibold"><?= $esc($servicio['nombre_Servicio'] ?? '') ?></td>
                                    <td>$ <?= $esc(number_format((float) ($servicio['precio_Servicio'] ?? 0), 2, ',', '.')) ?></td>
                                    <td><?= $esc($servicio['duracion_Servicio'] ?? 'Sin definir') ?></td>
                                    <td class="text-break"><?= $esc($servicio['descripcion_Servicio'] ?? 'Sin descripción') ?></td>
                                    <td>
                                        <div class="d-flex justify-content-end flex-wrap gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="<?= htmlspecialchars(url('servicios'), ENT_QUOTES, 'UTF-8') ?>?editar=<?= (int) $servicio['id_Servicio'] ?>" aria-label="Modificar <?= $esc($servicio['nombre_Servicio'] ?? 'servicio') ?>">
                                                <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                                <span class="visually-hidden">Modificar</span>
                                            </a>
                                            <form action="<?= htmlspecialchars(url('servicios/eliminar'), ENT_QUOTES, 'UTF-8') ?>" method="post" onsubmit="return confirm('¿Eliminar este servicio del catálogo?');">
                                                <input type="hidden" name="csrf_token" value="<?= $esc($csrfToken ?? '') ?>">
                                                <input type="hidden" name="servicio_id" value="<?= (int) $servicio['id_Servicio'] ?>">
                                                <button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Eliminar <?= $esc($servicio['nombre_Servicio'] ?? 'servicio') ?>">
                                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                                    <span class="visually-hidden">Eliminar</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-secondary mb-0 py-3">Todavía no hay servicios registrados.</p>
            <?php endif; ?>
        </div>
    </section>
</main>
