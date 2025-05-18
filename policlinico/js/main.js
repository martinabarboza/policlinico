/**
 * JavaScript principal para el sitio del Policlínico Veterinario
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todas las funcionalidades
    setupMobileNav();
    setupTestimonialsSlider();
    setupScrollAnimations();
    setupFormValidation();
});

/**
 * Configuración del menú móvil
 */
function setupMobileNav() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('show');
            navToggle.classList.toggle('active');
        });
        
        // Cerrar menú al hacer clic en un enlace
        const navLinks = navMenu.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('show');
                navToggle.classList.remove('active');
            });
        });
        
        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!navMenu.contains(e.target) && !navToggle.contains(e.target)) {
                navMenu.classList.remove('show');
                navToggle.classList.remove('active');
            }
        });
    }
}

/**
 * Configuración del slider de testimonios
 */
function setupTestimonialsSlider() {
    const slider = document.getElementById('testimoniosSlider');
    if (!slider) return;
    
    const cards = slider.querySelectorAll('.testimonial-card');
    if (cards.length <= 1) return;
    
    let currentIndex = 0;
    let interval;
    
    // Crear controles del slider
    const controls = document.createElement('div');
    controls.className = 'slider-controls';
    
    const prevBtn = document.createElement('button');
    prevBtn.className = 'slider-prev';
    prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
    
    const nextBtn = document.createElement('button');
    nextBtn.className = 'slider-next';
    nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
    
    const dots = document.createElement('div');
    dots.className = 'slider-dots';
    
    cards.forEach((_, index) => {
        const dot = document.createElement('span');
        dot.className = 'slider-dot';
        if (index === 0) dot.classList.add('active');
        
        dot.addEventListener('click', () => {
            goToSlide(index);
        });
        
        dots.appendChild(dot);
    });
    
    controls.appendChild(prevBtn);
    controls.appendChild(dots);
    controls.appendChild(nextBtn);
    
    slider.parentNode.appendChild(controls);
    
    // Configurar navegación
    prevBtn.addEventListener('click', () => {
        goToSlide(currentIndex - 1);
    });
    
    nextBtn.addEventListener('click', () => {
        goToSlide(currentIndex + 1);
    });
    
    // Iniciar autoplay
    startAutoplay();
    
    // Pausar autoplay al hover
    slider.addEventListener('mouseenter', () => {
        clearInterval(interval);
    });
    
    slider.addEventListener('mouseleave', () => {
        startAutoplay();
    });
    
    // Funciones auxiliares
    function goToSlide(index) {
        clearInterval(interval);
        
        // Manejar límites
        if (index < 0) index = cards.length - 1;
        if (index >= cards.length) index = 0;
        
        // Actualizar posición
        const offset = -index * 100;
        slider.style.transform = `translateX(${offset}%)`;
        
        // Actualizar dots
        document.querySelectorAll('.slider-dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
        
        currentIndex = index;
        startAutoplay();
    }
    
    function startAutoplay() {
        clearInterval(interval);
        interval = setInterval(() => {
            goToSlide(currentIndex + 1);
        }, 5000);
    }
    
    // Configurar estilos iniciales
    slider.style.display = 'flex';
    slider.style.transition = 'transform 0.5s ease';
    slider.style.width = `${cards.length * 100}%`;
    
    cards.forEach(card => {
        card.style.flex = `0 0 ${100 / cards.length}%`;
    });
}

/**
 * Configuración de animaciones al scroll
 */
function setupScrollAnimations() {
    const animatedElements = document.querySelectorAll('.section-header, .service-card, .team-card, .about-content, .contact-card');
    
    if (animatedElements.length > 0) {
        // Verificar si los elementos son visibles en la carga inicial
        animatedElements.forEach(el => {
            if (isInViewport(el)) {
                el.classList.add('animate');
            }
        });
        
        // Verificar al hacer scroll
        window.addEventListener('scroll', function() {
            checkVisibility();
        });
        
        // Función para verificar si un elemento está en el viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.8 &&
                rect.bottom >= 0
            );
        }
        
        // Verificar visibilidad de elementos y animar
        function checkVisibility() {
            animatedElements.forEach(el => {
                if (isInViewport(el) && !el.classList.contains('animate')) {
                    el.classList.add('animate');
                }
            });
        }
    }
}

/**
 * Configuración de validación de formularios
 */
function setupFormValidation() {
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', validateContactForm);
    }
    
    const appointmentForm = document.getElementById('appointmentForm');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', validateAppointmentForm);
    }
}

/**
 * Validación del formulario de contacto
 */
function validateContactForm(event) {
    // Obtener campos
    const nameInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const messageInput = document.getElementById('mensaje');
    
    // Limpiar errores previos
    clearErrors(this);
    
    // Validar nombre
    if (!nameInput.value.trim()) {
        event.preventDefault();
        showError(nameInput, 'Por favor, ingrese su nombre');
    }
    
    // Validar email
    if (!emailInput.value.trim()) {
        event.preventDefault();
        showError(emailInput, 'Por favor, ingrese su email');
    } else if (!isValidEmail(emailInput.value)) {
        event.preventDefault();
        showError(emailInput, 'Por favor, ingrese un email válido');
    }
    
    // Validar mensaje
    if (!messageInput.value.trim()) {
        event.preventDefault();
        showError(messageInput, 'Por favor, ingrese su mensaje');
    }
}

/**
 * Validación del formulario de citas
 */
function validateAppointmentForm(event) {
    // Obtener campos
    const nameInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('telefono');
    const petNameInput = document.getElementById('mascota_nombre');
    const serviceSelect = document.getElementById('servicio_id');
    const dateInput = document.getElementById('fecha');
    
    // Limpiar errores previos
    clearErrors(this);
    
    // Validar nombre
    if (!nameInput.value.trim()) {
        event.preventDefault();
        showError(nameInput, 'Por favor, ingrese su nombre');
    }
    
    // Validar email
    if (!emailInput.value.trim()) {
        event.preventDefault();
        showError(emailInput, 'Por favor, ingrese su email');
    } else if (!isValidEmail(emailInput.value)) {
        event.preventDefault();
        showError(emailInput, 'Por favor, ingrese un email válido');
    }
    
    // Validar teléfono
    if (!phoneInput.value.trim()) {
        event.preventDefault();
        showError(phoneInput, 'Por favor, ingrese su teléfono');
    }
    
    // Validar nombre de la mascota
    if (!petNameInput.value.trim()) {
        event.preventDefault();
        showError(petNameInput, 'Por favor, ingrese el nombre de su mascota');
    }
    
    // Validar servicio
    if (!serviceSelect.value) {
        event.preventDefault();
        showError(serviceSelect, 'Por favor, seleccione un servicio');
    }
    
    // Validar fecha
    if (!dateInput.value) {
        event.preventDefault();
        showError(dateInput, 'Por favor, seleccione una fecha');
    }
}

/**
 * Validación de formato de email
 */
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Muestra un mensaje de error para un campo
 */
function showError(inputElement, message) {
    const formGroup = inputElement.closest('.form-group');
    
    // Agregar clase de error
    formGroup.classList.add('has-error');
    
    // Crear mensaje de error si no existe
    let errorElement = formGroup.querySelector('.error-message');
    
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        formGroup.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
    inputElement.setAttribute('aria-invalid', 'true');
}

/**
 * Limpia los errores de un formulario
 */
function clearErrors(form) {
    const errorMessages = form.querySelectorAll('.error-message');
    const errorFields = form.querySelectorAll('.has-error');
    
    errorMessages.forEach(el => el.remove());
    errorFields.forEach(el => el.classList.remove('has-error'));
    
    form.querySelectorAll('[aria-invalid]').forEach(el => {
        el.removeAttribute('aria-invalid');
    });
}