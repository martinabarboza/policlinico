document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('servicioDetalleModal');
    if (!modal) {
        return;
    }

    modal.addEventListener('show.bs.modal', (event) => {
        const trigger = event.relatedTarget;
        if (!(trigger instanceof HTMLElement)) {
            return;
        }

        const titulo = trigger.dataset.servicioTitulo || 'Servicio veterinario';
        const descripcion = trigger.dataset.servicioDescripcion || 'Consultá por los detalles de este servicio.';
        const imagen = trigger.dataset.servicioImagen || '';

        modal.querySelector('#servicioDetalleTitulo').textContent = titulo;
        modal.querySelector('#servicioDetalleDescripcion').textContent = descripcion;
        modal.querySelector('#servicioDetallePrecio').textContent = trigger.dataset.servicioPrecio || 'Consultar';
        modal.querySelector('#servicioDetalleDuracion').textContent = trigger.dataset.servicioDuracion || 'A confirmar';

        const imagenDetalle = modal.querySelector('#servicioDetalleImagen');
        imagenDetalle.src = imagen;
        imagenDetalle.alt = `Imagen de ${titulo}`;
        imagenDetalle.hidden = imagen === '';
    });
});
