<?php
/**
 * Vista: dashboard/paciente-crear.php
 * Variables esperadas: $razas, $csrfToken, $error, $datos, volverUrl y guardarUrl.
 */
$e = static fn($v): string => htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
$razas = $razas ?? [];
$datos = $datos ?? [];
$error = $error ?? ($_SESSION['flash_error'] ?? '');
$volverUrl = $volverUrl ?? url('centro-consultas');
$guardarUrl = $guardarUrl ?? url('pacientes/guardar');
?>

<main class="paciente-page">
    <header class="paciente-header">
        <div>
            <p class="paciente-eyebrow">POLICLÍNICO / PACIENTES</p>
            <h1>Registrar paciente</h1>
            <p class="paciente-subtitle">Ingresá los datos del equino y de su tutor.</p>
        </div>
        <a class="pc-btn pc-btn--secondary" href="<?= $e($volverUrl) ?>">Volver</a>
    </header>

    <?php if ($error !== ''): ?>
        <div class="pc-alert pc-alert--error" role="alert"><?= $e($error) ?></div>
    <?php endif; ?>
    <form class="pc-form" action="<?= $e($guardarUrl) ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $e($csrfToken) ?>">
        <section class="pc-card" aria-labelledby="datos-paciente">
            <div class="pc-section-heading">
                <span class="pc-step">01</span>
                <div><h2 id="datos-paciente">Identificación del paciente</h2><p>Datos principales para identificarlo en la ficha clínica.</p></div>
            </div>

            <div class="pc-grid">
                <div class="pc-field pc-field--wide">
                    <label for="nombre_Paciente">Nombre del equino <span>*</span></label>
                    <input id="nombre_Paciente" name="nombre_Paciente" type="text" maxlength="100" required autocomplete="off" value="<?= $e($datos['nombre_Paciente'] ?? '') ?>" placeholder="Ej. Relámpago">
                </div>
                <div class="pc-field">
                    <label for="numchip_Paciente">Número de chip</label>
                    <input id="numchip_Paciente" name="numchip_Paciente" type="text" maxlength="26" value="<?= $e($datos['numchip_Paciente'] ?? '') ?>" placeholder="Si corresponde">
                </div>
                <div class="pc-field">
                    <label for="id_Raza">Raza</label>
                    <select id="id_Raza" name="id_Raza">
                        <option value="">Sin especificar</option>
                        <?php foreach ($razas as $raza): ?>
                            <option value="<?= $e($raza['id_Raza'] ?? '') ?>" <?= (string)($datos['id_Raza'] ?? '') === (string)($raza['id_Raza'] ?? '') ? 'selected' : '' ?>><?= $e($raza['nombre_Raza'] ?? '') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="pc-field">
                    <label for="fecha_nac_Paciente">Fecha de nacimiento</label>
                    <input id="fecha_nac_Paciente" name="fecha_nac_Paciente" type="date" max="<?= date('Y-m-d') ?>" value="<?= $e($datos['fecha_nac_Paciente'] ?? '') ?>">
                </div>
                <div class="pc-field">
                    <label for="edad_Paciente">Edad (años)</label>
                    <input id="edad_Paciente" name="edad_Paciente" type="number" min="0" max="100" step="1" value="<?= $e($datos['edad_Paciente'] ?? '') ?>" placeholder="Opcional">
                    <small>Si ingresás la fecha de nacimiento, la edad puede calcularse desde el servidor.</small>
                </div>
                <div class="pc-field">
                    <label for="sexo_Paciente">Sexo</label>
                    <select id="sexo_Paciente" name="sexo_Paciente">
                        <option value="">Sin especificar</option>
                        <?php foreach (['Hembra', 'Macho', 'Macho castrado'] as $sexo): ?>
                            <option value="<?= $e($sexo) ?>" <?= ($datos['sexo_Paciente'] ?? '') === $sexo ? 'selected' : '' ?>><?= $e($sexo) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="pc-field">
                    <label for="color_Paciente">Pelaje / color</label>
                    <input id="color_Paciente" name="color_Paciente" type="text" maxlength="50" value="<?= $e($datos['color_Paciente'] ?? '') ?>" placeholder="Ej. Zaino, alazán, tordillo">
                </div>
            </div>
        </section>

        <section class="pc-card" aria-labelledby="datos-tutor">
            <div class="pc-section-heading">
                <span class="pc-step">02</span>
                <div><h2 id="datos-tutor">Tutor</h2><p>Si el documento ya está registrado, se reutiliza ese tutor para el nuevo paciente.</p></div>
            </div>
            <div class="pc-grid">
                <div class="pc-field">
                    <label for="documento_Tutor">Documento / cédula <span>*</span></label>
                    <input id="documento_Tutor" name="documento_Tutor" type="text" maxlength="20" required autocomplete="off" value="<?= $e($datos['documento_Tutor'] ?? '') ?>">
                </div>
                <div class="pc-field">
                    <label for="nombre_Tutor">Nombre completo <span>*</span></label>
                    <input id="nombre_Tutor" name="nombre_Tutor" type="text" maxlength="150" required autocomplete="name" value="<?= $e($datos['nombre_Tutor'] ?? '') ?>">
                </div>
                <div class="pc-field">
                    <label for="telefono_Tutor">Teléfono</label>
                    <input id="telefono_Tutor" name="telefono_Tutor" type="tel" maxlength="50" autocomplete="tel" value="<?= $e($datos['telefono_Tutor'] ?? '') ?>" placeholder="Ej. 099 123 456">
                </div>
                <div class="pc-field">
                    <label for="email_Tutor">Correo electrónico</label>
                    <input id="email_Tutor" name="email_Tutor" type="email" maxlength="150" autocomplete="email" value="<?= $e($datos['email_Tutor'] ?? '') ?>">
                </div>
                <div class="pc-field pc-field--wide">
                    <label for="direccion_Tutor">Dirección</label>
                    <input id="direccion_Tutor" name="direccion_Tutor" type="text" maxlength="255" autocomplete="street-address" value="<?= $e($datos['direccion_Tutor'] ?? '') ?>">
                </div>
                <div class="pc-field pc-field--wide">
                    <label for="notas_Tutor">Notas del tutor</label>
                    <textarea id="notas_Tutor" name="notas_Tutor" rows="3" placeholder="Información de contacto u observaciones administrativas"><?= $e($datos['notas_Tutor'] ?? '') ?></textarea>
                </div>
            </div>
        </section>

        <div class="pc-form-note"><strong>Importante:</strong> los campos marcados con * son obligatorios. La raza se elige del catálogo de la base de datos.</div>
        <footer class="pc-actions">
            <a class="pc-btn pc-btn--secondary" href="<?= $e($volverUrl) ?>">Cancelar</a>
            <button class="pc-btn pc-btn--primary" type="submit">Registrar paciente</button>
        </footer>
    </form>
</main>
