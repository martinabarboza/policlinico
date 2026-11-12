<?php
/** Directorio y formularios de alta/edición de tutores. */
$escape = static fn ($valor): string =>
    htmlspecialchars(is_scalar($valor) ? (string) $valor : '', ENT_QUOTES, 'UTF-8');

$flash = $_SESSION['flash_tutor_creado'] ?? '';
unset($_SESSION['flash_tutor_creado']);

$datos = is_array($datos ?? null) ? $datos : [];
$tutorEditando = is_array($tutorEditando ?? null) ? $tutorEditando : null;
$editando = $tutorEditando !== null && !empty($tutorEditando['id_Tutor']);
$urlFormulario = $editando ? ($actualizarUrl ?? '') : ($guardarUrl ?? '');
?>

<div class="container-fluid tutores-page usuarios-page py-4">

    <div class="usuarios-header">
        <div class="usuarios-header__title">
            <div class="usuarios-header__icon">
                <i class="fa-solid fa-users" aria-hidden="true"></i>
            </div>
            <div>
                <p class="usuarios-header__eyebrow">Directorio</p>
                <h1 class="h3 mb-0">Gestión de tutores</h1>
            </div>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-3">
            <span class="text-secondary small">Tutores de pacientes</span>
            <a class="btn btn-outline-secondary" href="<?= $escape($volverUrl ?? '#') ?>" data-back-history>Volver</a>
        </div>
    </div>

    <?php if ($flash !== ''): ?>
        <div class="alert alert-success" role="status">
            <?= $escape($flash) ?>
        </div>
    <?php endif; ?>

    <?php if (($error ?? '') !== ''): ?>
        <div class="alert alert-danger" role="alert">
            <?= $escape($error) ?>
        </div>
    <?php endif; ?>

    <?php if (($errorBusqueda ?? '') !== ''): ?>
        <div class="alert alert-danger" role="alert">
            <?= $escape($errorBusqueda) ?>
        </div>
    <?php endif; ?>

    <!-- Búsqueda -->
    <section class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h5 mb-3">Buscar tutores</h2>

            <form method="get"
                  action="<?= $escape($buscarUrl ?? '') ?>"
                  class="row g-2">

                <div class="col-12 col-md">
                    <label for="tutor-busqueda" class="form-label">
                        Nombre, documento o teléfono
                    </label>

                    <input
                        class="form-control"
                        id="tutor-busqueda"
                        type="search"
                        name="q"
                        maxlength="150"
                        value="<?= $escape($busqueda ?? '') ?>"
                        placeholder="Ingresá un nombre, documento o teléfono"
                    >
                </div>

                <div class="col-12 col-md-auto d-flex
                            align-items-end gap-2">
                    <button class="btn btn-primary" type="submit">
                        Buscar
                    </button>

                    <a class="btn btn-outline-secondary"
                       href="<?= $escape($buscarUrl ?? '') ?>">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </section>

    <!-- Alta de tutor -->
    <section class="card shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h5 mb-3"><?= $editando ? 'Editar tutor' : 'Registrar tutor' ?></h2>
            <?php if ($editando): ?>
                <p class="text-secondary">
                    Los cambios se reflejan en todos los pacientes asociados a este tutor.
                </p>
            <?php endif; ?>

            <form method="post"
                  action="<?= $escape($urlFormulario) ?>"
                  class="row g-3">

                <input type="hidden"
                       name="csrf_token"
                       value="<?= $escape($csrfToken ?? '') ?>">
                <?php if ($editando): ?>
                    <input type="hidden"
                           name="tutor_id"
                           value="<?= (int) ($tutorEditando['id_Tutor'] ?? 0) ?>">
                <?php endif; ?>

                <div class="col-12 col-md-4">
                    <label for="documento_Tutor" class="form-label">
                        Documento *
                    </label>

                    <input
                        class="form-control"
                        id="documento_Tutor"
                        name="documento_Tutor"
                        maxlength="20"
                        required
                        value="<?= $escape($datos['documento_Tutor'] ?? $tutorEditando['documento_Tutor'] ?? '') ?>"
                    >
                </div>

                <div class="col-12 col-md-8">
                    <label for="nombre_Tutor" class="form-label">
                        Nombre completo *
                    </label>

                    <input
                        class="form-control"
                        id="nombre_Tutor"
                        name="nombre_Tutor"
                        maxlength="150"
                        required
                        value="<?= $escape($datos['nombre_Tutor'] ?? $tutorEditando['nombre_Tutor'] ?? '') ?>"
                    >
                </div>

                <div class="col-12 col-md-4">
                    <label for="telefono_Tutor" class="form-label">
                        Teléfono
                    </label>

                    <input
                        class="form-control"
                        id="telefono_Tutor"
                        name="telefono_Tutor"
                        maxlength="50"
                        value="<?= $escape($datos['telefono_Tutor'] ?? $tutorEditando['telefono_Tutor'] ?? '') ?>"
                    >
                </div>

                <div class="col-12 col-md-8">
                    <label for="email_Tutor" class="form-label">
                        Email
                    </label>

                    <input
                        class="form-control"
                        id="email_Tutor"
                        name="email_Tutor"
                        type="email"
                        maxlength="150"
                        value="<?= $escape($datos['email_Tutor'] ?? $tutorEditando['email_Tutor'] ?? '') ?>"
                    >
                </div>

                <div class="col-12">
                    <label for="direccion_Tutor" class="form-label">
                        Dirección
                    </label>

                    <input
                        class="form-control"
                        id="direccion_Tutor"
                        name="direccion_Tutor"
                        maxlength="255"
                        value="<?= $escape($datos['direccion_Tutor'] ?? $tutorEditando['direccion_Tutor'] ?? '') ?>"
                    >
                </div>

                <div class="col-12">
                    <label for="notas_Tutor" class="form-label">
                        Notas
                    </label>

                    <textarea
                        class="form-control"
                        id="notas_Tutor"
                        name="notas_Tutor"
                        rows="2"
                    ><?= $escape($datos['notas_Tutor'] ?? $tutorEditando['notas_Tutor'] ?? '') ?></textarea>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <?= $editando ? 'Guardar cambios' : 'Guardar tutor' ?>
                    </button>
                    <?php if ($editando): ?>
                        <a class="btn btn-outline-secondary" href="<?= $escape($buscarUrl ?? '') ?>">
                            Cancelar edición
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </section>

    <!-- Directorio -->
    <section class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between
                        align-items-center gap-2 mb-3">
                <h2 class="h5 mb-0">Directorio</h2>

                <span class="text-secondary small">
                    <?= count($tutores ?? []) ?> resultado(s)
                </span>
            </div>

            <?php if (empty($tutores)): ?>
                <p class="text-secondary mb-0">
                    <?= ($busqueda ?? '') !== ''
                        ? 'No se encontraron tutores para esa búsqueda.'
                        : 'Todavía no hay tutores registrados.' ?>
                </p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Documento</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th class="text-end">Pacientes</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($tutores as $tutor): ?>
                                <tr>
                                    <td>
                                        <?= $escape($tutor['nombre'] ?? '') ?>
                                    </td>
                                    <td>
                                        <?= $escape($tutor['documento'] ?? '') ?>
                                    </td>
                                    <td>
                                        <?= $escape(($tutor['telefono'] ?? '') ?: '—') ?>
                                    </td>
                                    <td>
                                        <?= $escape(($tutor['email'] ?? '') ?: '—') ?>
                                    </td>
                                    <td class="text-end">
                                        <?= (int) ($tutor['cantidad_pacientes'] ?? 0) ?>
                                    </td>
                                    <td>
                                        <?php
                                        $parametrosEdicion = ['editar' => (int) ($tutor['id'] ?? 0)];
                                        if (($busqueda ?? '') !== '') {
                                            $parametrosEdicion['q'] = $busqueda;
                                        }
                                        ?>
                                        <a class="btn btn-sm btn-outline-primary"
                                           href="<?= $escape(($buscarUrl ?? '') . '?' . http_build_query($parametrosEdicion)) ?>">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>
