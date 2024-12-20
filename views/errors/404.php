<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center">
        <div class="error-image mb-4">
            <i class="fas fa-exclamation-circle text-danger" style="font-size: 8rem;"></i>
        </div>
        
        <div class="error-content">
            <h1 class="display-1 fw-bold text-danger">404</h1>
            <h2 class="display-6 mb-4">¡Página no encontrada!</h2>
            <p class="lead mb-4">
                Lo sentimos, la página que estás buscando no existe o ha sido movida.
            </p>
            
            <div class="error-actions">
                <a href="<?= BASE_URL ?>/" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-home me-2"></i>Ir al Inicio
                </a>
                <button onclick="window.history.back()" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Volver Atrás
                </button>
            </div>

            <div class="mt-4">
                <p class="text-muted">
                    Si crees que esto es un error, por favor contacta al 
                    <a href="mailto:soporte@tudominio.com" class="text-decoration-none">soporte técnico</a>.
                </p>
            </div>
        </div>

        <!-- Animación de números flotantes para efecto visual -->
        <style>
            .error-page {
                position: relative;
                overflow: hidden;
            }
            
            @keyframes float {
                0% { transform: translateY(0px); opacity: 0.8; }
                50% { transform: translateY(-20px); opacity: 0.4; }
                100% { transform: translateY(0px); opacity: 0.8; }
            }

            .error-image i {
                animation: float 3s ease-in-out infinite;
            }

            .btn {
                transition: all 0.3s ease;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }

            .display-1 {
                text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            }
        </style>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>