document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('toggle-password');
    const toggleLabel = toggleButton?.querySelector('[data-toggle-label]');

    if (!passwordInput || !toggleButton || !toggleLabel) {
        return;
    }

    toggleButton.addEventListener('click', () => {
        const mostrar = passwordInput.type === 'password';
        passwordInput.type = mostrar ? 'text' : 'password';
        toggleLabel.textContent = mostrar ? 'Ocultar' : 'Mostrar';
        toggleButton.setAttribute('aria-label', `${mostrar ? 'Ocultar' : 'Mostrar'} contraseña`);
        toggleButton.setAttribute('aria-pressed', String(mostrar));
    });
});