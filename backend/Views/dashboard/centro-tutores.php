<?php
$escape = static fn ($valor): string =>
    htmlspecialchars(is_scalar($valor) ? (string) $valor : '', ENT_QUOTES, 'UTF-8');

$flash = $_SESSION['flash_tutor_creado'] ?? '';
unset($_SESSION['flash_tutor_creado']);

$datos = is_array($datos ?? null) ? $datos : [];
?>

<div class="container-fluid tutores-page py-4">

    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Tutores</h1>
            <p class="text-secondary mb-0">
                Directorio y registro de tutores de pacientes.
            </p>
        </div>

        <a class="btn btn-outline-secondary"
           href="<?= $escape($volverUrl ?? '#') ?>">
            Volver
        </a>
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
            <h2 class="h5 mb-3">Registrar tutor</h2>

            <form method="post"
                  action="<?= $escape($guardarUrl ?? '') ?>"
                  class="row g-3">

                <input type="hidden"
                       name="csrf_token"
                       value="<?= $escape($csrfToken ?? '') ?>">

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
                        value="<?= $escape($datos['documento_Tutor'] ?? '') ?>"
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
                        value="<?= $escape($datos['nombre_Tutor'] ?? '') ?>"
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
                        value="<?= $escape($datos['telefono_Tutor'] ?? '') ?>"
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
                        value="<?= $escape($datos['email_Tutor'] ?? '') ?>"
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
                        value="<?= $escape($datos['direccion_Tutor'] ?? '') ?>"
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
                    ><?= $escape($datos['notas_Tutor'] ?? '') ?></textarea>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary" type="submit">
                        Guardar tutor
                    </button>
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>
