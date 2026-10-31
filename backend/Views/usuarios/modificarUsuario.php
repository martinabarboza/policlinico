<?php
$usuarioEdit = $usuarioEdit ?? [];
$rolesUsuario = [
    'Director' => 'Director',
    'Estudiante' => 'Estudiante',
    'Profesor' => 'Profesor'
];
?>

<div class="usuarios-form-page container-fluid py-4">
    <div class="usuarios-form-header">
        <div class="usuarios-form-title">
            <div class="usuarios-form-icon">
                <i class="fa-solid fa-user-pen"></i>
            </div>
            <div>
                <p class="text-uppercase small fw-bold text-primary mb-1">Edición</p>
                <h1 class="h3 mb-0">Modificar usuario</h1>
            </div>
        </div>
        <a class="btn btn-outline-secondary" href="/usuarios">
            <i class="fa-solid fa-arrow-left me-1"></i>Volver
        </a>
    </div>

    <section class="card usuarios-form-card border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="h5 mb-0">Datos del usuario</h2>
            </div>
            <span class="badge text-bg-light border"><?php echo htmlspecialchars(($usuarioEdit['rol_Usuario'] ?? 'Sin rol'), ENT_QUOTES, 'UTF-8'); ?></span>
        </div>

        <div class="card-body p-4">
            <?php if (isset($error) && !empty($error)) {
                echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
            } ?>
            <form action="/usuarios/actualizarUsuario" method="POST" class="row g-4">
                <input type="hidden" name="cedulaOld" value="<?php echo htmlspecialchars($usuarioEdit['cedula_Usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <div class="col-12 col-md-6">
                    <label for="cedula" class="form-label">Cédula</label>
                    <input class="form-control" type="text" id="cedula" name="cedula" inputmode="numeric" pattern="[0-9]{8}" value="<?php echo htmlspecialchars($usuarioEdit['cedula_Usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="col-12 col-md-6">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input class="form-control" type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuarioEdit['nombre_Usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="col-12 col-md-6">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input class="form-control" type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuarioEdit['apellido_Usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="col-12 col-md-6">
                    <label for="rol" class="form-label">Rol</label>
                    <select class="form-select" name="rol" id="rol" required>
                        <option value="">Seleccione un rol</option>
                        <?php foreach ($rolesUsuario as $valor => $etiqueta): ?>
                            <option value="<?php echo htmlspecialchars($valor, ENT_QUOTES, 'UTF-8'); ?>" <?php echo (($usuarioEdit['rol_Usuario'] ?? '') === $valor) ? 'selected' : ''; ?>><?php echo htmlspecialchars($etiqueta, ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input class="form-control" type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuarioEdit['email_Usuario'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="col-12 col-md-6">
                    <label for="passw" class="form-label">Contraseña</label>
                    <input class="form-control" type="password" id="passw" name="passw" placeholder="Dejar vacío para conservar la actual">
                </div>

                <div class="col-12 pt-2 border-top mt-2">
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk me-1"></i>Guardar cambios
                        </button>
                        <a class="btn btn-outline-secondary" href="/usuarios">
                            Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
