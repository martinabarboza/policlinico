<?php
// Incluir configuración y funciones
include 'includes/config.php';
include 'includes/functions.php';

// Conectar a la base de datos
$conn = conectarDB();

// Verificar si se solicita un miembro específico
$miembro_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$miembro_detalle = null;

if ($miembro_id > 0) {
    // Obtener información del miembro específico
    $sql = "SELECT p.*, u.nombre_completo, u.email, u.rol 
            FROM personal p 
            INNER JOIN usuarios u ON p.usuario_id = u.id 
            WHERE p.id = ? AND p.activo = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $miembro_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $miembro_detalle = $resultado->fetch_assoc();
    }
}

// Obtener todos los miembros del personal activos
$sql = "SELECT p.*, u.nombre_completo, u.email, u.rol 
        FROM personal p 
        INNER JOIN usuarios u ON p.usuario_id = u.id 
        WHERE p.activo = 1 
        ORDER BY u.rol, u.nombre_completo";
$resultado = $conn->query($sql);

$personal = [];
if ($resultado->num_rows > 0) {
    while ($miembro = $resultado->fetch_assoc()) {
        $personal[] = $miembro;
    }
}

// Cerrar conexión
$conn->close();

// Agrupar personal por rol para mostrar en secciones
$veterinarios = array_filter($personal, function($p) { return $p['rol'] === 'vet'; });
$administrativos = array_filter($personal, function($p) { return $p['rol'] === 'assistant' || $p['rol'] === 'receptionist'; });
$directivos = array_filter($personal, function($p) { return $p['rol'] === 'admin'; });
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $miembro_detalle ? $miembro_detalle['nombre_completo'] : 'Nuestro Equipo'; ?> - Policlínico Veterinario</title>
    <meta name="description" content="<?php echo $miembro_detalle ? "Conozca a {$miembro_detalle['nombre_completo']}, {$miembro_detalle['especialidad']} en el Policlínico Veterinario" : 'Conozca a nuestro equipo de profesionales veterinarios y personal administrativo dedicados al cuidado y bienestar de su mascota.'; ?>">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/favicon.ico">
    
    <style>
        .staff-detail {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }
        
        .staff-photo {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            height: 400px;
        }
        
        .staff-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .staff-info h1 {
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }
        
        .staff-role {
            color: var(--primary-green);
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .staff-contact {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .staff-section {
            margin-bottom: 4rem;
        }
        
        .staff-section h2 {
            color: var(--primary-blue);
            margin-bottom: 2rem;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .staff-detail {
                grid-template-columns: 1fr;
            }
            
            .staff-photo {
                max-height: 350px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <div class="container">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <?php if ($miembro_detalle): ?>
                    <li><a href="equipo.php">Equipo</a></li>
                    <li><?php echo $miembro_detalle['nombre_completo']; ?></li>
                <?php else: ?>
                    <li>Equipo</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <!-- Contenido Principal -->
    <div class="container">
        <?php if ($miembro_detalle): ?>
            <!-- Detalle de Miembro del Personal -->
            <section class="staff-detail-section section-padding">
                <div class="staff-detail">
                    <div class="staff-photo">
                        <?php if ($miembro_detalle['foto']): ?>
                            <img src="<?php echo $miembro_detalle['foto']; ?>" alt="<?php echo $miembro_detalle['nombre_completo']; ?>">
                        <?php else: ?>
                            <img src="assets/images/default-profile.jpg" alt="<?php echo $miembro_detalle['nombre_completo']; ?>">
                        <?php endif; ?>
                    </div>
                    
                    <div class="staff-info">
                        <h1><?php echo $miembro_detalle['nombre_completo']; ?></h1>
                        <div class="staff-role"><?php echo $miembro_detalle['especialidad']; ?></div>
                        
                        <?php if ($miembro_detalle['bio']): ?>
                            <div class="staff-bio">
                                <?php echo nl2br($miembro_detalle['bio']); ?>
                            </div>
                        <?php else: ?>
                            <div class="staff-bio">
                                <p>Profesional dedicado a la salud y bienestar animal. Forma parte del equipo del Policlínico Veterinario, centro asociado a la Universidad de la República y la Facultad de Veterinaria.</p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="staff-contact">
                            <a href="mailto:<?php echo $miembro_detalle['email']; ?>" class="btn btn-outline">
                                <i class="fas fa-envelope"></i> Contactar
                            </a>
                            <a href="citas.php" class="btn btn-primary">Solicitar Cita</a>
                        </div>
                    </div>
                </div>
                
                <div class="other-staff">
                    <h2>Otros Miembros del Equipo</h2>
                    
                    <div class="team-grid">
                        <?php 
                        $count = 0;
                        foreach ($personal as $miembro):
                            // Mostrar solo personal diferente al actual y limitar a 3
                            if ($miembro['id'] != $miembro_detalle['id'] && $count < 3):
                                $count++;
                        ?>
                            <div class="team-card">
                                <div class="team-image">
                                    <?php if ($miembro['foto']): ?>
                                        <img src="<?php echo $miembro['foto']; ?>" alt="<?php echo $miembro['nombre_completo']; ?>">
                                    <?php else: ?>
                                        <img src="assets/images/default-profile.jpg" alt="<?php echo $miembro['nombre_completo']; ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="team-info">
                                    <h3><?php echo $miembro['nombre_completo']; ?></h3>
                                    <p class="team-role"><?php echo $miembro['especialidad']; ?></p>
                                    <?php if ($miembro['bio']): ?>
                                        <p class="team-bio"><?php echo truncarTexto($miembro['bio'], 100); ?></p>
                                    <?php endif; ?>
                                    <div class="team-contact">
                                        <a href="equipo.php?id=<?php echo $miembro['id']; ?>" class="btn btn-sm">Ver Perfil</a>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <!-- Lista de Personal -->
            <section class="team-section section-padding">
                <div class="section-header">
                    <h1>Nuestro Equipo</h1>
                    <p>Conozca a los profesionales que conforman el Policlínico Veterinario, dedicados a brindar la mejor atención a su mascota.</p>
                </div>
                
                <?php if (empty($personal)): ?>
                    <p class="empty-message">No hay personal disponible en este momento.</p>
                <?php else: ?>
                    <!-- Sección de Veterinarios -->
                    <?php if (!empty($veterinarios)): ?>
                        <div class="staff-section">
                            <h2>Médicos Veterinarios</h2>
                            
                            <div class="team-grid">
                                <?php foreach ($veterinarios as $veterinario): ?>
                                    <div class="team-card">
                                        <div class="team-image">
                                            <?php if ($veterinario['foto']): ?>
                                                <img src="<?php echo $veterinario['foto']; ?>" alt="<?php echo $veterinario['nombre_completo']; ?>">
                                            <?php else: ?>
                                                <img src="assets/images/default-profile.jpg" alt="<?php echo $veterinario['nombre_completo']; ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="team-info">
                                            <h3><?php echo $veterinario['nombre_completo']; ?></h3>
                                            <p class="team-role"><?php echo $veterinario['especialidad']; ?></p>
                                            <?php if ($veterinario['bio']): ?>
                                                <p class="team-bio"><?php echo truncarTexto($veterinario['bio'], 100); ?></p>
                                            <?php endif; ?>
                                            <div class="team-contact">
                                                <a href="equipo.php?id=<?php echo $veterinario['id']; ?>" class="btn btn-sm">Ver Perfil</a>
                                                <a href="mailto:<?php echo $veterinario['email']; ?>" class="btn-icon"><i class="fas fa-envelope"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Sección de Directivos -->
                    <?php if (!empty($directivos)): ?>
                        <div class="staff-section">
                            <h2>Dirección</h2>
                            
                            <div class="team-grid">
                                <?php foreach ($directivos as $directivo): ?>
                                    <div class="team-card">
                                        <div class="team-image">
                                            <?php if ($directivo['foto']): ?>
                                                <img src="<?php echo $directivo['foto']; ?>" alt="<?php echo $directivo['nombre_completo']; ?>">
                                            <?php else: ?>
                                                <img src="assets/images/default-profile.jpg" alt="<?php echo $directivo['nombre_completo']; ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="team-info">
                                            <h3><?php echo $directivo['nombre_completo']; ?></h3>
                                            <p class="team-role"><?php echo $directivo['especialidad']; ?></p>
                                            <?php if ($directivo['bio']): ?>
                                                <p class="team-bio"><?php echo truncarTexto($directivo['bio'], 100); ?></p>
                                            <?php endif; ?>
                                            <div class="team-contact">
                                                <a href="equipo.php?id=<?php echo $directivo['id']; ?>" class="btn btn-sm">Ver Perfil</a>
                                                <a href="mailto:<?php echo $directivo['email']; ?>" class="btn-icon"><i class="fas fa-envelope"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Sección de Personal Administrativo -->
                    <?php if (!empty($administrativos)): ?>
                        <div class="staff-section">
                            <h2>Personal Administrativo</h2>
                            
                            <div class="team-grid">
                                <?php foreach ($administrativos as $administrativo): ?>
                                    <div class="team-card">
                                        <div class="team-image">
                                            <?php if ($administrativo['foto']): ?>
                                                <img src="<?php echo $administrativo['foto']; ?>" alt="<?php echo $administrativo['nombre_completo']; ?>">
                                            <?php else: ?>
                                                <img src="assets/images/default-profile.jpg" alt="<?php echo $administrativo['nombre_completo']; ?>">
                                            <?php endif; ?>
                                        </div>
                                        <div class="team-info">
                                            <h3><?php echo $administrativo['nombre_completo']; ?></h3>
                                            <p class="team-role"><?php echo $administrativo['especialidad']; ?></p>
                                            <?php if ($administrativo['bio']): ?>
                                                <p class="team-bio"><?php echo truncarTexto($administrativo['bio'], 100); ?></p>
                                            <?php endif; ?>
                                            <div class="team-contact">
                                                <a href="equipo.php?id=<?php echo $administrativo['id']; ?>" class="btn btn-sm">Ver Perfil</a>
                                                <a href="mailto:<?php echo $administrativo['email']; ?>" class="btn-icon"><i class="fas fa-envelope"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <div class="team-info-block">
                    <h2>Trabajamos juntos por la salud animal</h2>
                    <p>Nuestro equipo multidisciplinario colabora para ofrecer la mejor atención a su mascota. Combinamos la formación académica de excelencia con la pasión por el bienestar animal.</p>
                    
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h3>Formación Académica</h3>
                            <p>Nuestro personal cuenta con formación universitaria de primer nivel y actualización constante.</p>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <h3>Trabajo en Equipo</h3>
                            <p>Trabajamos de forma coordinada para ofrecer una atención integral a cada paciente.</p>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-certificate"></i>
                            </div>
                            <h3>Especialización</h3>
                            <p>Contamos con especialistas en distintas áreas de la medicina veterinaria.</p>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3>Compromiso</h3>
                            <p>Nuestra pasión es el bienestar animal y la satisfacción de nuestros clientes.</p>
                        </div>
                    </div>
                </div>
                
                <div class="cta-block">
                    <h2>¿Necesita atención para su mascota?</h2>
                    <p>Nuestro equipo está a su disposición. No dude en contactarnos o solicitar una cita.</p>
                    <div class="cta-buttons">
                        <a href="citas.php" class="btn btn-primary">Solicitar Cita</a>
                        <a href="contacto.php" class="btn btn-secondary">Contactar</a>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </div>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="js/main.js"></script>
</body>
</html>