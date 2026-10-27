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
});
