<?php include __DIR__ . '/../layouts/header_landing.php'; ?>

<body>
    <?php include __DIR__ . '/../layouts/navbar_landing.php'; ?>
    <div class=" container-fluid w-100 px-4 py-5 hero">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
            <div class="col-12 col-lg-6">
                <img src="<?= ASSETS_URL ?>/imgs/policlinico.png" class="d-block mx-lg-auto img-fluid rounded rounded-3 img-policlinico" alt="Policlinico Veterinario CENUR" loading="lazy">
            </div>
            <div class="col-lg-6">
                <h1 class="display-5 hero-titulo lh-1 mb-3">Gestión clínica e investigación veterinaria</h1>
                <p class="lead hero-parrafo text-justify">Optimizando la administración de pacientes, profesionales veterinarios y datos clínicos de la Policlínica CENUR para mejorar la atención y potenciar la investigación epidemiológica.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <button type="button" class="btn btn-primary btn-lg px-4 me-md-2 hero-btn">INGRESAR AL PANEL DE CONTROL</button>
                </div>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../layouts/footer_landing.php'; ?>
</body>

</html>