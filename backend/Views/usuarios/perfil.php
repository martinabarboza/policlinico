<?php
$nombreCompleto = trim((string) ($usuario['nombre'] ?? '') . ' ' . (string) ($usuario['apellido'] ?? ''));
$nombreCompleto = $nombreCompleto !== '' ? $nombreCompleto : 'Usuario';
$estado = (string) ($usuario['estado'] ?? 'Sin estado');
$estadoActivo = in_array(strtolower(trim($estado)), ['activo', 'activa', 'active'], true);
?>

<div class="container-fluid perfil-page pt-4 px-4 pb-4">
    <div class="perfil-header">
        <div class="perfil-header__title">
            <span class="perfil-header__icon" aria-hidden="true">
                <i class="fa-solid fa-user"></i>
            </span>
            <div>
                <h1 class="h4 mb-1">Perfil de usuario</h1>
                <p class="text-muted mb-0">Información de tu cuenta en el sistema</p>
            </div>
        </div>
    </div>

    <section class="perfil-card card border-0" aria-labelledby="perfil-datos-titulo">
        <aside class="perfil-card__profile">
            <span class="perfil-card__icon" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
            <h2 class="h5 mb-2" id="perfil-datos-titulo"><?= htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8') ?></h2>
            <span class="perfil-role-badge">
                <?= htmlspecialchars((string) ($usuario['rol'] ?? 'Sin rol'), ENT_QUOTES, 'UTF-8') ?>
            </span>
        </aside>

        <div class="perfil-card__details">
            <div class="perfil-detail">
                <span class="perfil-detail__icon" aria-hidden="true"><i class="fa-solid fa-id-card"></i></span>
                <div class="perfil-detail__content">
                    <span class="perfil-detail__label">Cédula</span>
                    <strong class="perfil-detail__value"><?= htmlspecialchars((string) ($usuario['cedula'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
            </div>

            <div class="perfil-detail">
                <span class="perfil-detail__icon" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                <div class="perfil-detail__content">
                    <span class="perfil-detail__label">Nombre completo</span>
                    <strong class="perfil-detail__value"><?= htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
            </div>

            <div class="perfil-detail">
                <span class="perfil-detail__icon" aria-hidden="true"><i class="fa-solid fa-envelope"></i></span>
                <div class="perfil-detail__content">
                    <span class="perfil-detail__label">Correo electrónico</span>
                    <strong class="perfil-detail__value"><?= htmlspecialchars((string) ($usuario['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
            </div>

            <div class="perfil-detail">
                <span class="perfil-detail__icon" aria-hidden="true"><i class="fa-solid fa-shield-halved"></i></span>
                <div class="perfil-detail__content">
                    <span class="perfil-detail__label">Rol</span>
                    <strong class="perfil-detail__value"><?= htmlspecialchars((string) ($usuario['rol'] ?? ''), ENT_QUOTES, 'UTF-8') ?></strong>
                </div>
            </div>

            <div class="perfil-detail perfil-detail--status">
                <span class="perfil-detail__icon" aria-hidden="true"><i class="fa-solid fa-circle-check"></i></span>
                <div class="perfil-detail__content">
                    <span class="perfil-detail__label">Estado de la cuenta</span>
                    <strong class="perfil-detail__value perfil-detail__status <?= $estadoActivo ? 'perfil-detail__status--active' : 'perfil-detail__status--inactive' ?>">
                        <span aria-hidden="true"></span>
                        <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?>
                    </strong>
                </div>
            </div>
        </div>
    </section>
</div>