document.addEventListener('DOMContentLoaded', function () {
    var paraclinicos = document.getElementById('paraclinicos-lista');
    var agregarParaclinico = document.getElementById('agregar-paraclinico');
    var avisoMaximoParaclinicos = document.getElementById('paraclinico-maximo');

    if (paraclinicos && agregarParaclinico) {
        function actualizarLimiteParaclinicos() {
            var alcanzoLimite = paraclinicos.querySelectorAll('[data-paraclinico-item]').length >= 20;
            agregarParaclinico.disabled = alcanzoLimite;
            if (avisoMaximoParaclinicos) {
                avisoMaximoParaclinicos.hidden = !alcanzoLimite;
            }
        }

        function actualizarIndicesParaclinicos() {
            paraclinicos.querySelectorAll('[data-paraclinico-item]').forEach(function (item, index) {
                item.querySelectorAll('input, textarea').forEach(function (control) {
                    if (control.type === 'hidden') {
                        control.name = 'paraclinicos[' + index + '][id]';
                        control.id = 'paraclinico-id-' + index;
                        return;
                    }
                    var campo = control.type === 'file'
                        ? 'archivo'
                        : (control.name.includes('[tipo]') ? 'tipo' : 'resultado');
                    control.name = 'paraclinicos[' + index + '][' + campo + ']';
                    control.id = 'paraclinico-' + campo + '-' + index;
                    var label = item.querySelector('label[for^="paraclinico-' + campo + '-"]');
                    if (label) {
                        label.htmlFor = control.id;
                    }
                });
            });
        }

        agregarParaclinico.addEventListener('click', function () {
            var plantilla = paraclinicos.querySelector('[data-paraclinico-item]');
            if (!plantilla || paraclinicos.querySelectorAll('[data-paraclinico-item]').length >= 20) {
                return;
            }

            var nuevo = plantilla.cloneNode(true);
            nuevo.querySelectorAll('input, textarea').forEach(function (control) {
                control.value = '';
            });
            paraclinicos.appendChild(nuevo);
            actualizarIndicesParaclinicos();
            actualizarLimiteParaclinicos();
            nuevo.querySelector('input').focus();
        });

        paraclinicos.addEventListener('click', function (event) {
            var boton = event.target.closest('[data-remove-paraclinico]');
            if (!boton) {
                return;
            }

            var items = paraclinicos.querySelectorAll('[data-paraclinico-item]');
            var item = boton.closest('[data-paraclinico-item]');
            if (items.length === 1) {
                item.querySelectorAll('input, textarea').forEach(function (control) {
                    control.value = '';
                });
            } else {
                item.remove();
            }
            actualizarIndicesParaclinicos();
            actualizarLimiteParaclinicos();
        });

        actualizarLimiteParaclinicos();
    }

    document.querySelectorAll('[data-filter-list]').forEach(function (input) {
        var list = document.getElementById(input.dataset.filterList);
        if (!list) {
            return;
        }

        var options = Array.from(list.querySelectorAll('.participante-opcion'));
        var noResults = list.querySelector('.participante-lista__sin-resultados');

        function updateOptions() {
            var query = input.value.trim().toLocaleLowerCase();
            var visibleCount = 0;

            options.forEach(function (option) {
                var matches = (option.dataset.search || '').toLocaleLowerCase().includes(query);
                option.hidden = !matches;
                if (matches) {
                    visibleCount += 1;
                }

                var control = option.querySelector('input');
                option.classList.toggle('is-selected', Boolean(control && control.checked));
            });

            if (noResults) {
                noResults.hidden = visibleCount > 0;
            }
        }

        input.addEventListener('input', updateOptions);
        list.addEventListener('change', updateOptions);
        updateOptions();
    });

    var formConsulta = document.getElementById('formConsulta');
    var botonesPestana = Array.from(document.querySelectorAll('[data-consulta-tab]'));
    var panelesPestana = Array.from(document.querySelectorAll('[data-consulta-panel]'));
    var anterior = document.querySelector('[data-tab-previous]');
    var siguiente = document.querySelector('[data-tab-next]');
    var indicadorEtapa = document.querySelector('[data-step-label]');
    var pestanaActiva = document.getElementById('consulta-tab-activa');
    var selectorPaciente = document.getElementById('id_Paciente');
    var resumenPaciente = document.getElementById('paciente-resumen');
    var resumenTutor = document.getElementById('tutor-resumen');

    if (formConsulta && botonesPestana.length && panelesPestana.length) {
        var indexActivo = Math.max(0, botonesPestana.findIndex(function (boton) {
            return boton.dataset.consultaTab === (pestanaActiva && pestanaActiva.value);
        }));

        function activarPestana(indice, enfocar) {
            indexActivo = Math.max(0, Math.min(indice, botonesPestana.length - 1));
            botonesPestana.forEach(function (boton, index) {
                var activa = index === indexActivo;
                boton.classList.toggle('is-active', activa);
                boton.setAttribute('aria-selected', activa ? 'true' : 'false');
                boton.tabIndex = activa ? 0 : -1;
            });
            panelesPestana.forEach(function (panel) {
                var activa = panel.dataset.consultaPanel === botonesPestana[indexActivo].dataset.consultaTab;
                panel.hidden = !activa;
                panel.classList.toggle('is-active', activa);
            });
            if (pestanaActiva) {
                pestanaActiva.value = botonesPestana[indexActivo].dataset.consultaTab;
            }
            if (anterior) {
                anterior.disabled = indexActivo === 0;
            }
            if (siguiente) {
                siguiente.hidden = indexActivo === botonesPestana.length - 1;
            }
            if (indicadorEtapa) {
                indicadorEtapa.textContent = 'Etapa ' + (indexActivo + 1) + ' de ' + botonesPestana.length;
            }
            if (enfocar) {
                botonesPestana[indexActivo].focus();
            }
        }

        botonesPestana.forEach(function (boton, indice) {
            boton.addEventListener('click', function () {
                activarPestana(indice, false);
            });
            boton.addEventListener('keydown', function (event) {
                if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
                    event.preventDefault();
                    activarPestana(indice + (event.key === 'ArrowRight' ? 1 : -1), true);
                }
            });
        });

        if (anterior) {
            anterior.addEventListener('click', function () {
                activarPestana(indexActivo - 1, false);
            });
        }
        if (siguiente) {
            siguiente.addEventListener('click', function () {
                activarPestana(indexActivo + 1, false);
                var panelActivo = panelesPestana.find(function (panel) {
                    return !panel.hidden;
                });
                if (panelActivo) {
                    var primerControl = panelActivo.querySelector('input, select, textarea, button');
                    if (primerControl) {
                        primerControl.focus();
                    }
                }
            });
        }

        activarPestana(indexActivo, false);

        if (selectorPaciente && resumenPaciente && resumenTutor) {
            function actualizarResumenPaciente() {
                var opcion = selectorPaciente.options[selectorPaciente.selectedIndex];
                var seleccionado = selectorPaciente.value !== '' && opcion;
                resumenPaciente.textContent = seleccionado
                    ? (opcion.dataset.nombre || opcion.text.trim())
                    : 'Seleccioná un paciente';
                resumenTutor.textContent = seleccionado
                    ? (opcion.dataset.tutor || 'Tutor no registrado')
                        + (opcion.dataset.chip ? ' · Chip ' + opcion.dataset.chip : '')
                    : 'El resumen aparecerá aquí.';
            }
            selectorPaciente.addEventListener('change', actualizarResumenPaciente);
            actualizarResumenPaciente();
        }

        formConsulta.noValidate = true;
        var ultimaAccion = '';
        document.querySelectorAll('button[name="accion"]').forEach(function (boton) {
            boton.addEventListener('click', function () {
                ultimaAccion = boton.value;
            });
        });

        formConsulta.addEventListener('submit', function (event) {
            var submitter = event.submitter;
            var accion = submitter && submitter.name === 'accion' ? submitter.value : ultimaAccion;
            var accionAutomatica = null;
            if (!accion) {
                accion = 'finalizar';
                accionAutomatica = document.createElement('input');
                accionAutomatica.type = 'hidden';
                accionAutomatica.name = 'accion';
                accionAutomatica.value = accion;
                formConsulta.appendChild(accionAutomatica);
            }
            var finalizando = accion === 'finalizar';
            var paciente = document.getElementById('id_Paciente');
            var fecha = document.getElementById('fecha_hora');
            var tipo = document.getElementById('tipo');
            var motivo = document.getElementById('motivo');
            var campoInvalido = null;

            [paciente, fecha, tipo, motivo].forEach(function (campo) {
                if (campo) {
                    campo.setCustomValidity('');
                }
            });
            document.querySelectorAll('[data-final-required]').forEach(function (marca) {
                marca.hidden = !finalizando;
            });

            if (!paciente.value) {
                paciente.setCustomValidity('Seleccioná un paciente para guardar la consulta.');
                campoInvalido = paciente;
            } else if (!fecha.value || !fecha.checkValidity()) {
                fecha.setCustomValidity('Ingresá una fecha y hora válidas.');
                campoInvalido = fecha;
            } else if (finalizando && !tipo.value) {
                tipo.setCustomValidity('Seleccioná el tipo de consulta antes de finalizar.');
                campoInvalido = tipo;
            } else if (finalizando && !motivo.value.trim()) {
                motivo.setCustomValidity('Describí el motivo de la consulta antes de finalizar.');
                campoInvalido = motivo;
            }

            if (campoInvalido) {
                event.preventDefault();
                if (accionAutomatica) {
                    accionAutomatica.remove();
                }
                activarPestana(0, false);
                campoInvalido.focus();
                campoInvalido.reportValidity();
            }
        });

        var consultaModificada = false;
        formConsulta.addEventListener('input', function () {
            consultaModificada = true;
        });
        formConsulta.addEventListener('change', function () {
            consultaModificada = true;
        });
        document.querySelectorAll('[data-cancel-consulta]').forEach(function (enlace) {
            enlace.addEventListener('click', function (event) {
                if (consultaModificada && !window.confirm('Hay cambios sin guardar. ¿Querés cancelar la consulta y descartarlos?')) {
                    event.preventDefault();
                }
            });
        });
    }
});
