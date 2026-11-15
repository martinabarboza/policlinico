document.addEventListener('DOMContentLoaded', () => {
    const paciente = document.getElementById('hospitalizacion-paciente');
    const consulta = document.getElementById('hospitalizacion-consulta');
    const ayuda = document.getElementById('hospitalizacion-consulta-ayuda');

    if (!paciente || !consulta || !ayuda) {
        return;
    }

    const opcionesConsulta = Array.from(consulta.querySelectorAll('option[data-paciente-id]'));
    const opcionVacia = consulta.querySelector('option[value=""]');

    const actualizarConsultas = () => {
        const pacienteId = paciente.value;
        let tieneConsultas = false;

        opcionesConsulta.forEach((opcion) => {
            const corresponde = pacienteId !== '' && opcion.dataset.pacienteId === pacienteId;
            opcion.hidden = !corresponde;
            opcion.disabled = !corresponde;
            tieneConsultas = tieneConsultas || corresponde;
        });

        consulta.disabled = pacienteId === '';
        if (opcionVacia) {
            opcionVacia.textContent = pacienteId === ''
                ? 'Seleccioná un paciente primero'
                : 'Sin consulta asociada';
        }

        const consultaActual = consulta.selectedOptions[0];
        if (
            consultaActual
            && consultaActual !== opcionVacia
            && consultaActual.dataset.pacienteId !== pacienteId
        ) {
            consulta.value = '';
        }

        ayuda.textContent = pacienteId === ''
            ? 'Seleccioná un paciente para ver sus consultas previas.'
            : tieneConsultas
                ? 'Elegí una consulta del paciente o dejá la opción sin asociar.'
                : 'Este paciente no tiene consultas registradas; podés continuar sin asociar una.';
    };

    paciente.addEventListener('change', actualizarConsultas);
    actualizarConsultas();
});
