<main>
 
<div class="top-bar"></div>
 
<div class="container d-flex justify-content-center align-items-center py-5" style="min-height: 90vh;">
  <div class="w-100">
    <div class="card login-card mx-auto p-4 p-md-5">
      <div class="text-center mb-4">
        <img src="<?= ASSETS_URL ?>/imgs/logo-petcraft-inverso.png" alt="PetCraft" class="logo-circle mb-3">
        <h3 class="fw-bold mb-2">Bienvenido a <span class="brand-green fw-bold">PetCraft</span></h3>
        <p class="text-secondary mb-0">Ingresa tus credenciales para continuar</p>
      </div>
 
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form action="<?=url('login')?>" method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <div class="mb-3">
          <label for="cedula" class="form-label fw-semibold">C.I.</label>
          <input
            type="text"
            inputmode="numeric"
            pattern="[0-9]*"
            class="form-control form-control-lg"
            id="cedula"
            name="cedula"
            value="<?= htmlspecialchars($oldCedula ?? '') ?>"
            placeholder="Ingresa tu cédula de identidad"
            required
            autofocus>
        </div>
 
        <div class="mb-3">
          <label for="password" class="form-label fw-semibold">Contraseña</label>
          <input
            type="password"
            class="form-control form-control-lg"
            id="password"
            name="password"
            placeholder="● ● ● ● ● ●"
            required>
        </div>
 
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label" for="remember">
              Recordarme
            </label>
          </div>
          <a href="#" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
        </div>
 
        <button type="submit" class="btn btn-brand btn-lg w-100 text-white fw-semibold">Iniciar sesión</button>
      </form>
    </div>
 
    <p class="text-center mt-4 mb-0">
      ¿No tienes cuenta? <a href="#" class="text-decoration-none">Contacta con el administrador</a>
    </p>
  </div>
</div>
</main>