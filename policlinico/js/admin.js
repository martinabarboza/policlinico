/**
 * JavaScript para el panel de administración del Policlínico Veterinario
 */

document.addEventListener('DOMContentLoaded', function() {
    // Activar pestañas si existen
    const tabs = document.querySelectorAll('.admin-tab');
    if (tabs.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Eliminar clase active de todas las pestañas
                document.querySelectorAll('.admin-tab').forEach(t => {
                    t.classList.remove('active');
                });
                
                // Eliminar clase active de todos los contenidos
                document.querySelectorAll('.tab-content').forEach(c => {
                    c.classList.remove('active');
                });
                
                // Activar pestaña actual
                this.classList.add('active');
                
                // Activar contenido correspondiente
                const target = this.getAttribute('data-target');
                document.getElementById(target).classList.add('active');
            });
        });
    }
    
    // Confirmar eliminaciones
    const deleteButtons = document.querySelectorAll('.btn-delete, .delete-confirm');
    if (deleteButtons.length > 0) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('¿Está seguro de que desea eliminar este elemento? Esta acción no se puede deshacer.')) {
                    e.preventDefault();
                }
            });
        });
    }
    
    // Autocompletar búsqueda de propietarios
    const ownerSearch = document.getElementById('owner-search');
    if (ownerSearch) {
        ownerSearch.addEventListener('input', debounce(function() {
            const searchTerm = this.value;
            if (searchTerm.length < 3) return;
            
            fetch(`buscar_propietarios.php?term=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    const resultsContainer = document.getElementById('search-results');
                    resultsContainer.innerHTML = '';
                    
                    if (data.length === 0) {
                        resultsContainer.innerHTML = '<p class="no-results">No se encontraron resultados.</p>';
                        return;
                    }
                    
                    data.forEach(owner => {
                        const item = document.createElement('div');
                        item.classList.add('search-result-item');
                        item.innerHTML = `
                            <div class="result-name">${owner.nombre_completo}</div>
                            <div class="result-info">${owner.email} | ${owner.telefono}</div>
                        `;
                        
                        item.addEventListener('click', function() {
                            document.getElementById('propietario_id').value = owner.id;
                            document.getElementById('owner-search').value = owner.nombre_completo;
                            resultsContainer.innerHTML = '';
                            
                            // Cargar mascotas del propietario si existe el campo
                            if (document.getElementById('mascota_id')) {
                                cargarMascotasPropietario(owner.id);
                            }
                        });
                        
                        resultsContainer.appendChild(item);
                    });
                    
                    resultsContainer.style.display = 'block';
                })
                .catch(error => console.error('Error al buscar propietarios:', error));
        }, 300));
    }
    
    // Ocultar resultados de búsqueda al hacer clic fuera
    document.addEventListener('click', function(e) {
        const searchResults = document.getElementById('search-results');
        const ownerSearch = document.getElementById('owner-search');
        
        if (searchResults && ownerSearch && !ownerSearch.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    // Selección de fecha en formularios
    const dateInputs = document.querySelectorAll('.date-picker');
    if (dateInputs.length > 0) {
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Si hay un campo de hora y uno de veterinario, cargar disponibilidad
                const timeSelect = document.getElementById('hora');
                const vetSelect = document.getElementById('veterinario_id');
                
                if (timeSelect && vetSelect) {
                    const date = this.value;
                    const vetId = vetSelect.value;
                    
                    if (date && vetId) {
                        cargarHorasDisponibles(date, vetId);
                    }
                }
            });
        });
    }
    
    // Cambio de veterinario en formulario de citas
    const vetSelect = document.getElementById('veterinario_id');
    if (vetSelect) {
        vetSelect.addEventListener('change', function() {
            const dateInput = document.querySelector('.date-picker');
            if (dateInput && dateInput.value) {
                cargarHorasDisponibles(dateInput.value, this.value);
            }
        });
    }
    
    // Inicialización de editores de texto enriquecido
    const richTextareas = document.querySelectorAll('.rich-textarea');
    if (richTextareas.length > 0 && typeof tinymce !== 'undefined') {
        richTextareas.forEach(textarea => {
            tinymce.init({
                selector: '#' + textarea.id,
                height: 300,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
            });
        });
    }
});

// Función para cargar mascotas de un propietario
function cargarMascotasPropietario(propietarioId) {
    fetch(`obtener_mascotas.php?propietario_id=${propietarioId}`)
        .then(response => response.json())
        .then(data => {
            const mascotaSelect = document.getElementById('mascota_id');
            mascotaSelect.innerHTML = '<option value="">Seleccione una mascota</option>';
            
            data.forEach(mascota => {
                const option = document.createElement('option');
                option.value = mascota.id;
                option.textContent = mascota.nombre;
                mascotaSelect.appendChild(option);
            });
            
            // Habilitar el campo
            mascotaSelect.disabled = false;
        })
        .catch(error => console.error('Error al cargar las mascotas:', error));
}

// Función para cargar horas disponibles
function cargarHorasDisponibles(fecha, veterinarioId) {
    const appointmentId = document.getElementById('cita_id') ? document.getElementById('cita_id').value : '';
    
    fetch(`horas_disponibles.php?fecha=${fecha}&veterinario_id=${veterinarioId}&cita_id=${appointmentId}`)
        .then(response => response.json())
        .then(data => {
            const horaSelect = document.getElementById('hora');
            const currentValue = horaSelect.value;
            horaSelect.innerHTML = '<option value="">Seleccione una hora</option>';
            
            data.forEach(hora => {
                const option = document.createElement('option');
                option.value = hora;
                option.textContent = hora;
                
                if (hora === currentValue) {
                    option.selected = true;
                }
                
                horaSelect.appendChild(option);
            });
            
            // Habilitar el campo
            horaSelect.disabled = false;
        })
        .catch(error => console.error('Error al cargar horas disponibles:', error));
}

// Función utilitaria debounce para evitar múltiples llamadas
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}