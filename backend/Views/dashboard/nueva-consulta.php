<?php
/**
 * Vista: formulario de alta de consulta.
 *
 * Variables esperadas desde el controlador: especieSeleccionada, pacientes,
 * estudiantes, profesores, csrfToken, errorFormulario y oldInput.
 * Esta vista no ejecuta consultas SQL.
 */
function e($valor): string
{
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES, 'UTF-8');
}

$oldInput = is_array($oldInput ?? null) ? $oldInput : [];
$valorAnterior = static function (string $campo, string $predeterminado = '') use ($oldInput): string {
    $valor = $oldInput[$campo] ?? $predeterminado;
    return is_string($valor) ? $valor : $predeterminado;
};

$especiesPermitidas = ['Perro', 'Gato', 'Equino'];
$especieSeleccionada = (string)($especieSeleccionada ?? 'Equino');

if (!in_array($especieSeleccionada, $especiesPermitidas, true)) {
    $especieSeleccionada = 'Equino';
}

$pacientes = is_array($pacientes ?? null) ? $pacientes : [];
$estudiantes = is_array($estudiantes ?? null) ? $estudiantes : [];
$profesores = is_array($profesores ?? null) ? $profesores : [];
$estudiantesSeleccionados = $oldInput['estudiantes'] ?? [];
if (!is_array($estudiantesSeleccionados)) {
    $estudiantesSeleccionados = [];
}
$estudiantesSeleccionados = array_filter(
    $estudiantesSeleccionados,
    static fn($id): bool => is_string($id) && ctype_digit($id)
);
$paraclinicosAnteriores = $oldInput['paraclinicos'] ?? [[]];
if (!is_array($paraclinicosAnteriores) || !$paraclinicosAnteriores) {
    $paraclinicosAnteriores = [[]];
}
$pacientesFiltrados = $pacientes;

$fechaActual = $valorAnterior('fecha_hora_Consulta', date('Y-m-d\TH:i'));
?>

<main class="consulta-page">
    <header class="page-header">
        <div class="page-heading">
            <span class="eyebrow">CONSULTORIO / NUEVA CONSULTA</span>
            <h1>Registrar consulta</h1>
            <p>Registro clínico y evaluación del paciente.</p>
        </div>
        <a class="btn btn-secondary" href="/centro-consultas">
            Volver al consultorio
        </a>
    </header>

    <?php if ($errorFormulario !== null): ?>
        <div class="alert alert-error" role="alert">
            <?= e($errorFormulario) ?>
        </div>
    <?php endif; ?>
    <?php if (is_string($mensajePaciente ?? null) && $mensajePaciente !== ''): ?>
        <div class="alert alert-success" role="status"><?= e($mensajePaciente) ?></div>
    <?php endif; ?>

    <!-- Selector separado: GET recarga la vista y PHP muestra la especie elegida. -->
    <section class="form-card species-card">
        <div class="section-heading">
            <div class="section-icon">01</div>
            <div>
                <h2>Especie del paciente</h2>
                <p>Elegí la especie para cargar los pacientes y el examen correspondiente.</p>
            </div>
        </div>

        <form method="GET" action="/centro-consultas/nueva" class="species-form">

            <div class="field">
                <label for="especie">Especie *</label>
                <select id="especie" name="especie" required>
                    <?php foreach ($especiesPermitidas as $especie): ?>
                        <option value="<?= e($especie) ?>"
                            <?= $especieSeleccionada === $especie ? 'selected' : '' ?>>
                            <?= e($especie) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn btn-primary" type="submit">Seleccionar especie</button>
        </form>

        <p class="species-note">
            Especie seleccionada:
            <strong><?= e($especieSeleccionada) ?></strong>
        </p>
    </section>

    <form id="formConsulta"
          action="/centro-consultas/guardar"
          method="POST"
          enctype="multipart/form-data"
          class="consulta-form">

        <!-- Conserva la elección de la vista; el servidor debe validar contra la BD. -->
        <input type="hidden" name="especie" value="<?= e($especieSeleccionada) ?>">
        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">

        <section class="form-card">
            <div class="section-heading">
                <div class="section-icon">02</div>
                <div>
                    <h2>Participantes</h2>
                    <p>Buscá por cédula o nombre y marcá quiénes participaron.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="buscar-estudiante">Estudiantes participantes</label>
                    <input type="search"
                           id="buscar-estudiante"
                           class="participante-busqueda"
                           data-filter-list="lista-estudiantes"
                           placeholder="Buscar por cédula o nombre..."
                           autocomplete="off">
                    <div class="participante-lista" id="lista-estudiantes">
                        <?php foreach ($estudiantes as $estudiante): ?>
                            <?php $estudianteId = (string) $estudiante['id']; ?>
                            <label class="participante-opcion"
                                   data-search="<?= e($estudiante['cedula'] . ' ' . $estudiante['nombre']) ?>">
                                <input type="checkbox"
                                       name="estudiantes[]"
                                       value="<?= e($estudianteId) ?>"
                                       <?= in_array($estudianteId, $estudiantesSeleccionados, true) ? 'checked' : '' ?>>
                                <span>
                                    <strong><?= e($estudiante['nombre']) ?></strong>
                                    <small>Cédula: <?= e($estudiante['cedula']) ?></small>
                                </span>
                                <span class="participante-opcion__estado" aria-hidden="true"></span>
                            </label>
                        <?php endforeach; ?>
                        <?php if (!$estudiantes): ?>
                            <p class="participante-lista__vacio">No hay estudiantes activos registrados.</p>
                        <?php endif; ?>
                        <p class="participante-lista__sin-resultados" hidden>No hay coincidencias.</p>
                    </div>
                    <small class="field-warning">Marcá la casilla para agregar o quitá la marca para remover.</small>
                </div>

                <div class="field">
                    <label for="buscar-profesor">Profesor participante (opcional)</label>
                    <input type="search"
                           id="buscar-profesor"
                           class="participante-busqueda"
                           data-filter-list="lista-profesores"
                           placeholder="Buscar por cédula o nombre..."
                           autocomplete="off">
                    <div class="participante-lista" id="lista-profesores">
                        <?php foreach ($profesores as $profesor): ?>
                            <?php $profesorId = (string) $profesor['id']; ?>
                            <label class="participante-opcion"
                                   data-search="<?= e($profesor['cedula'] . ' ' . $profesor['nombre']) ?>">
                                <input type="radio"
                                       name="id_Profesor"
                                       value="<?= e($profesorId) ?>"
                                       <?= $valorAnterior('id_Profesor') === $profesorId ? 'checked' : '' ?>>
                                <span>
                                    <strong><?= e($profesor['nombre']) ?></strong>
                                    <small>Cédula: <?= e($profesor['cedula']) ?></small>
                                </span>
                                <span class="participante-opcion__estado" aria-hidden="true"></span>
                            </label>
                        <?php endforeach; ?>
                        <?php if ($profesores): ?>
                            <label class="participante-opcion" data-search="sin profesor participante sin asignar">
                                <input type="radio"
                                       name="id_Profesor"
                                       value=""
                                       <?= $valorAnterior('id_Profesor') === '' ? 'checked' : '' ?>>
                                <span>
                                    <strong>Sin profesor participante</strong>
                                    <small>Dejar sin asignar</small>
                                </span>
                                <span class="participante-opcion__estado" aria-hidden="true"></span>
                            </label>
                        <?php else: ?>
                            <p class="participante-lista__vacio">No hay profesores activos registrados.</p>
                            <input type="hidden" name="id_Profesor" value="">
                        <?php endif; ?>
                        <p class="participante-lista__sin-resultados" hidden>No hay coincidencias.</p>
                    </div>
                    <small class="field-warning">Podés elegir como máximo un profesor.</small>
                </div>
            </div>
        </section>

        <section class="form-card">
            <div class="section-heading">
                <div class="section-icon">03</div>
                <div>
                    <h2>Datos de la consulta</h2>
                    <p>Identificación del paciente y motivo de consulta.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="id_Paciente">Paciente *</label>
                    <select id="id_Paciente" name="id_Paciente" required>
                        <option value="">Seleccionar paciente</option>
                        <?php foreach ($pacientesFiltrados as $p): ?>
                            <option value="<?= e($p['id_Paciente'] ?? '') ?>"
                                <?= $valorAnterior('id_Paciente') === (string) ($p['id_Paciente'] ?? '') ? 'selected' : '' ?>>
                                <?= e($p['nombre_Paciente'] ?? 'Paciente sin nombre') ?>
                                <?php if (!empty($p['numchip_Paciente'])): ?>
                                    — <?= e($p['numchip_Paciente']) ?>
                                <?php endif; ?>
                                <?php if (!empty($p['nombre_Tutor'])): ?>
                                    · Tutor: <?= e($p['nombre_Tutor']) ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (count($pacientesFiltrados) === 0): ?>
                        <small class="field-warning">
                            No hay pacientes asociados a esta especie en los datos recibidos.
                            Revisá el catálogo de especies, las razas y la consulta del modelo.
                        </small>
                    <?php endif; ?>
                </div>

                <div class="field">
                    <label for="fecha_hora">Fecha y hora *</label>
                    <input type="datetime-local"
                           id="fecha_hora"
                           name="fecha_hora_Consulta"
                           value="<?= e($fechaActual) ?>"
                           required>
                </div>

                <div class="field">
                    <label for="tipo">Tipo de consulta *</label>
                    <select id="tipo" name="tipo_Consulta" required>
                        <option value="">Seleccionar tipo</option>
                        <?php
                        $tipos = [
                            'Consulta general',
                            'Control',
                            'Consulta equina',
                            'Hospitalización'
                        ];
                        $tipoSugerido = $especieSeleccionada === 'Equino'
                            ? 'Consulta equina'
                            : 'Consulta general';
                        ?>
                        <?php foreach ($tipos as $tipo): ?>
                            <option value="<?= e($tipo) ?>"
                                <?= $valorAnterior('tipo_Consulta', $tipoSugerido) === $tipo ? 'selected' : '' ?>>
                                <?= e($tipo) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field field-full">
                    <label for="motivo">Motivo de consulta *</label>
                    <textarea id="motivo"
                              name="motivo_Consulta"
                              rows="3"
                              placeholder="Describí el motivo de la consulta..."
                              required><?= e($valorAnterior('motivo_Consulta')) ?></textarea>
                </div>
            </div>
        </section>

        <section class="form-card">
            <div class="section-heading">
                <div class="section-icon">04</div>
                <div>
                    <h2>Anamnesis</h2>
                    <p>Antecedentes y evolución clínica del paciente.</p>
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="sanitaria">Historia sanitaria</label>
                    <textarea id="sanitaria" name="sanitaria_Anamnesis" rows="3"><?= e($valorAnterior('sanitaria_Anamnesis')) ?></textarea>
                </div>

                <div class="field">
                    <label for="ambiental">Entorno y ambiente</label>
                    <textarea id="ambiental" name="ambiental_Anamnesis" rows="3"><?= e($valorAnterior('ambiental_Anamnesis')) ?></textarea>
                </div>

                <div class="field">
                    <label for="remota_fisiologica">Anamnesis remota fisiológica</label>
                    <textarea id="remota_fisiologica" name="remota_fisiologica_Anamnesis" rows="3"><?= e($valorAnterior('remota_fisiologica_Anamnesis')) ?></textarea>
                </div>

                <div class="field">
                    <label for="remota_patologica">Anamnesis remota patológica</label>
                    <textarea id="remota_patologica" name="remota_patologica_Anamnesis" rows="3"><?= e($valorAnterior('remota_patologica_Anamnesis')) ?></textarea>
                </div>

                <div class="field">
                    <label for="proxima_fisiologica">Anamnesis próxima fisiológica</label>
                    <textarea id="proxima_fisiologica" name="proxima_fisiologica_Anamnesis" rows="3"><?= e($valorAnterior('proxima_fisiologica_Anamnesis')) ?></textarea>
                </div>

                <div class="field">
                    <label for="proxima_patologica">Anamnesis próxima patológica</label>
                    <textarea id="proxima_patologica" name="proxima_patologica_Anamnesis" rows="3"><?= e($valorAnterior('proxima_patologica_Anamnesis')) ?></textarea>
                </div>
            </div>
        </section>

        <section class="form-card">
            <div class="section-heading">
                <div class="section-icon">05</div>
                <div>
                    <h2>Examen clínico general</h2>
                    <p>Constantes fisiológicas y hallazgos del examen físico.</p>
                </div>
            </div>

            <div class="form-grid form-grid-3">
                <div class="field">
                    <label for="peso">Peso</label>
                    <input id="peso" name="peso_ExamenG" type="text" value="<?= e($valorAnterior('peso_ExamenG')) ?>" placeholder="Ej. 480 kg">
                </div>

                <div class="field">
                    <label for="cc">Condición corporal (CC)</label>
                    <input id="cc" name="CC_ExamenG" type="text" value="<?= e($valorAnterior('CC_ExamenG')) ?>" placeholder="Ej. 5/9">
                </div>

                <div class="field">
                    <label for="tr">Temperatura rectal (TR)</label>
                    <input id="tr" name="TR_ExamenG" type="text" value="<?= e($valorAnterior('TR_ExamenG')) ?>" placeholder="Ej. 37.8 °C">
                </div>

                <div class="field">
                    <label for="fc">Frecuencia cardíaca (FC)</label>
                    <input id="fc" name="FC_ExamenG" type="text" value="<?= e($valorAnterior('FC_ExamenG')) ?>" placeholder="Ej. 42 lpm">
                </div>

                <div class="field">
                    <label for="fr">Frecuencia respiratoria (FR)</label>
                    <input id="fr" name="FR_ExamenG" type="text" value="<?= e($valorAnterior('FR_ExamenG')) ?>" placeholder="Ej. 16 rpm">
                </div>

                <div class="field">
                    <label for="tllc">Tiempo de llenado capilar (TLLC)</label>
                    <input id="tllc" name="TLLC_ExamenG" type="text" value="<?= e($valorAnterior('TLLC_ExamenG')) ?>" placeholder="Ej. 2 s">
                </div>

                <div class="field">
                    <label for="sensorio">Sensorio</label>
                    <input id="sensorio" name="sensorio_ExamenG" type="text" value="<?= e($valorAnterior('sensorio_ExamenG')) ?>">
                </div>

                <div class="field">
                    <label for="facies">Facies</label>
                    <input id="facies" name="facies_ExamenG" type="text" value="<?= e($valorAnterior('facies_ExamenG')) ?>">
                </div>

                <div class="field">
                    <label for="piel">Piel y subcutáneo</label>
                    <input id="piel" name="piel_subcutaneo_ExamenG" type="text" value="<?= e($valorAnterior('piel_subcutaneo_ExamenG')) ?>">
                </div>

                <div class="field">
                    <label for="mucosas">Mucosas</label>
                    <input id="mucosas" name="mucosas_ExamenG" type="text" value="<?= e($valorAnterior('mucosas_ExamenG')) ?>">
                </div>

                <div class="field">
                    <label for="hidratacion">Hidratación</label>
                    <input id="hidratacion" name="hidratacion_ExamenG" type="text" value="<?= e($valorAnterior('hidratacion_ExamenG')) ?>">
                </div>

                <div class="field">
                    <label for="aparentes">Otras alteraciones aparentes</label>
                    <input id="aparentes" name="aparentes_ExamenG" type="text" value="<?= e($valorAnterior('aparentes_ExamenG')) ?>">
                </div>

                <div class="field field-full">
                    <label for="grandes_funciones">Grandes funciones</label>
                    <textarea id="grandes_funciones" name="grandes_funciones_ExamenG" rows="2"><?= e($valorAnterior('grandes_funciones_ExamenG')) ?></textarea>
                </div>

                <div class="field field-full">
                    <label for="actitudes">Actitudes anómalas</label>
                    <textarea id="actitudes" name="actitudes_anomalas_ExamenG" rows="2"><?= e($valorAnterior('actitudes_anomalas_ExamenG')) ?></textarea>
                </div>

                <div class="field field-full">
                    <label for="ganglios">Ganglios linfáticos</label>
                    <textarea id="ganglios" name="ganglios_linfaticos_ExamenG" rows="2"><?= e($valorAnterior('ganglios_linfaticos_ExamenG')) ?></textarea>
                </div>
            </div>
        </section>

        <?php if ($especieSeleccionada === 'Equino'): ?>
            <section class="form-card">
                <div class="section-heading">
                    <div class="section-icon">06</div>
                    <div>
                        <h2>Examen específico equino</h2>
                        <p>Evaluación podal y hallazgos particulares del equino.</p>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="field">
                        <label for="temperatura_cascos">Temperatura de cascos</label>
                        <input id="temperatura_cascos"
                               name="temperatura_cascos_ExamenEq"
                               type="text"
                               value="<?= e($valorAnterior('temperatura_cascos_ExamenEq')) ?>"
                               placeholder="Registrar hallazgos">
                    </div>

                    <div class="field">
                        <label for="pulso_digital">Pulso digital</label>
                        <input id="pulso_digital"
                               name="pulso_digital_ExamenEq"
                               type="text"
                               value="<?= e($valorAnterior('pulso_digital_ExamenEq')) ?>"
                               placeholder="Registrar hallazgos">
                    </div>

                    <div class="field field-full">
                        <label for="senos">Senos</label>
                        <input id="senos" name="senos_ExamenEq" type="text" value="<?= e($valorAnterior('senos_ExamenEq')) ?>">
                    </div>
                </div>

                <h3 class="subsection-title">Evaluación de cascos</h3>

                <div class="form-grid form-grid-2">
                    <div class="field">
                        <label for="csd">CSD</label>
                        <input id="csd" name="csd_ExamenEq" type="text" value="<?= e($valorAnterior('csd_ExamenEq')) ?>">
                    </div>
                    <div class="field">
                        <label for="cid">CID</label>
                        <input id="cid" name="cid_ExamenEq" type="text" value="<?= e($valorAnterior('cid_ExamenEq')) ?>">
                    </div>
                    <div class="field">
                        <label for="csi">CSI</label>
                        <input id="csi" name="csi_ExamenEq" type="text" value="<?= e($valorAnterior('csi_ExamenEq')) ?>">
                    </div>
                    <div class="field">
                        <label for="cii">CII</label>
                        <input id="cii" name="cii_ExamenEq" type="text" value="<?= e($valorAnterior('cii_ExamenEq')) ?>">
                    </div>
                </div>
            </section>
        <?php elseif ($especieSeleccionada === 'Perro'): ?>
            <section class="form-card">
                <div class="section-heading">
                    <div class="section-icon">06</div>
                    <div>
                        <h2>Examen específico canino</h2>
                        <p>Sección preparada para incorporar los campos que defina el cliente.</p>
                    </div>
                </div>
                <p class="form-hint">
                    Por ahora se utiliza el examen general. Los campos específicos caninos
                    deben definirse con el equipo veterinario.
                </p>
            </section>
        <?php elseif ($especieSeleccionada === 'Gato'): ?>
            <section class="form-card">
                <div class="section-heading">
                    <div class="section-icon">06</div>
                    <div>
                        <h2>Examen específico felino</h2>
                        <p>Sección preparada para incorporar los campos que defina el cliente.</p>
                    </div>
                </div>
                <p class="form-hint">
                    Por ahora se utiliza el examen general. Los campos específicos felinos
                    deben definirse con el equipo veterinario.
                </p>
            </section>
        <?php endif; ?>

        <section class="form-card">
            <div class="section-heading">
                <div class="section-icon">07</div>
                <div>
                    <h2>Diagnóstico, paraclínicos y tratamiento</h2>
                    <p>Completá los hallazgos y las indicaciones asociadas al caso.</p>
                </div>
            </div>

            <h3 class="subsection-title">Diagnóstico</h3>
            <div class="form-grid">
                <div class="field">
                    <label for="diagnostico_provisorio">Provisorio</label>
                    <textarea id="diagnostico_provisorio" name="diagnostico_provisorio" rows="3"><?= e($valorAnterior('diagnostico_provisorio')) ?></textarea>
                </div>
                <div class="field">
                    <label for="diagnostico_definitivo">Definitivo</label>
                    <textarea id="diagnostico_definitivo" name="diagnostico_definitivo" rows="3"><?= e($valorAnterior('diagnostico_definitivo')) ?></textarea>
                </div>
            </div>

            <h3 class="subsection-title">Paraclínicos</h3>
            <div class="paraclinicos-lista" id="paraclinicos-lista">
                <?php foreach (array_values($paraclinicosAnteriores) as $indice => $paraclinico): ?>
                    <?php
                    $paraclinico = is_array($paraclinico) ? $paraclinico : [];
                    $tipoParaclinico = is_string($paraclinico['tipo'] ?? null) ? $paraclinico['tipo'] : '';
                    $resultadoParaclinico = is_string($paraclinico['resultado'] ?? null) ? $paraclinico['resultado'] : '';
                    ?>
                    <div class="paraclinico-item" data-paraclinico-item>
                        <div class="form-grid">
                            <div class="field">
                                <label for="paraclinico-tipo-<?= (int) $indice ?>">Estudio o análisis</label>
                                <input id="paraclinico-tipo-<?= (int) $indice ?>"
                                       name="paraclinicos[<?= (int) $indice ?>][tipo]"
                                       type="text"
                                       maxlength="100"
                                       value="<?= e($tipoParaclinico) ?>"
                                       placeholder="Ej. Hemograma">
                            </div>
                            <div class="field">
                                <label for="paraclinico-resultado-<?= (int) $indice ?>">Resultado / observaciones</label>
                                <textarea id="paraclinico-resultado-<?= (int) $indice ?>"
                                          name="paraclinicos[<?= (int) $indice ?>][resultado]"
                                          rows="2"><?= e($resultadoParaclinico) ?></textarea>
                            </div>
                            <div class="field field-full">
                                <label for="paraclinico-archivo-<?= (int) $indice ?>">Adjuntar estudio (opcional)</label>
                                <input id="paraclinico-archivo-<?= (int) $indice ?>"
                                       name="paraclinicos[<?= (int) $indice ?>][archivo]"
                                       type="file"
                                       accept="application/pdf,image/jpeg,image/png,image/webp">
                                <small class="field-warning">Un PDF o imagen JPG, PNG o WebP de hasta 5 MB.</small>
                            </div>
                        </div>
                        <button class="btn btn-secondary paraclinico-quitar" type="button" data-remove-paraclinico>Quitar estudio</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="btn btn-secondary mt-2" type="button" id="agregar-paraclinico">+ Agregar otro paraclínico</button>
            <small class="field-warning" id="paraclinico-maximo" hidden>Se alcanzó el máximo de 20 paraclínicos.</small>

            <h3 class="subsection-title">Tratamiento</h3>
            <div class="field">
                <label for="tratamiento">Indicaciones y tratamiento</label>
                <textarea id="tratamiento" name="tratamiento" rows="4"><?= e($valorAnterior('tratamiento')) ?></textarea>
            </div>
        </section>

        <section class="form-card">
            <div class="section-heading">
                <div class="section-icon">08</div>
                <div>
                    <h2>Pronóstico y reseña</h2>
                    <p>Elegí las categorías aplicables y adjuntá la reseña gráfica si fue marcada.</p>
                </div>
            </div>

            <?php
            $pronosticosAnteriores = $oldInput['pronosticos'] ?? [];
            if (!is_array($pronosticosAnteriores)) {
                $pronosticosAnteriores = [];
            }
            ?>
            <fieldset class="field">
                <legend class="field-label">Pronóstico</legend>
                <div class="pronostico-opciones">
                    <?php foreach (['Vital', 'Reproductivo', 'Deportivo', 'Reservado', 'Malo'] as $pronostico): ?>
                        <label class="pronostico-opcion">
                            <input type="checkbox"
                                   name="pronosticos[]"
                                   value="<?= e($pronostico) ?>"
                                   <?= in_array($pronostico, $pronosticosAnteriores, true) ? 'checked' : '' ?>>
                            <span><?= e($pronostico) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <div class="field mt-4">
                <label for="archivo_resenia">Reseña gráfica (imagen)</label>
                <input id="archivo_resenia"
                       name="archivo_resenia"
                       type="file"
                       accept="image/jpeg,image/png,image/webp">
                <small class="field-warning">Imagen JPG, PNG o WebP de hasta 5 MB. El archivo se guarda en forma privada y solo usuarios autenticados pueden abrirlo.</small>
            </div>
        </section>

        <footer class="form-actions">
            <a class="btn btn-secondary" href="/centro-consultas">
                Cancelar
            </a>
            <button class="btn btn-primary" type="submit">
                Registrar consulta
            </button>
        </footer>
    </form>
</main>
