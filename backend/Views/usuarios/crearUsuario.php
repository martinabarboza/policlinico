<?php
$usuarioEdit = $usuarioEdit ?? [];
$rolesUsuario = [
    'DIRECTOR' => 'Director',
    'ESTUDIANTE' => 'Estudiante',
    'PROFESOR' => 'Profesor',
    'VETERINARIO' => 'Veterinario'
];
?>

<main class="usuarios-form-page container-fluid py-4">
    <header class="usuarios-form-header">
        <div class="usuarios-form-title">
            <div class="usuarios-form-icon">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div>
                <p class="usuarios-header__eyebrow">Administración</p>
                <h1 class="h3 mb-0">Crear usuario</h1>
            </div>
        </div>
        <a class="btn btn-outline-secondary" href="/usuarios">
            <i class="fa-solid fa-arrow-left me-1"></i>Volver
        </a>
    </header>

    <section class="usuarios-form-card card shadow-sm border-0">
        <div class="card-header">
            <h2 class="h5 mb-0">Datos del nuevo usuario</h2>
        </div>
        <div class="card-body p-4">
            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form action="/usuarios/crear" method="POST" class="row g-3">
                <div class="col-md-6">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input
                        type="text"
                        id="cedula"
                        name="cedula"
                        class="form-control"
                        pattern="\d{8}"
                        maxlength="8"
                        placeholder="Ej: 12345678"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" id="apellido" name="apellido" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label for="rol" class="form-label">Rol</label>
                    <select id="rol" name="rol" class="form-select" required>
                        <option value="">Seleccione un rol</option>
                        <?php foreach ($rolesUsuario as $valor => $etiqueta): ?>
                            <option value="<?php echo htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($etiqueta, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="usuario@ejemplo.com" required>
                </div>

                <div class="col-md-6">
                    <label for="passw" class="form-label">Contraseña</label>
                    <input type="password" id="passw" name="passw" class="form-control" required>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                    <a class="btn btn-outline-secondary" href="/usuarios">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Crear usuario</button>
                </div>
            </form>
        </div>
    </section>
</main>
