<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/tareas">Inicio</a></li>
                    <li class="breadcrumb-item active">Configuración</li>
                </ol>
            </nav>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-cog"></i> Configuración</h4>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/usuario/configuracion" method="POST">
                        <h5 class="mb-4">Preferencias de Notificaciones</h5>
                        
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notif_email" name="notif_email" checked>
                                <label class="form-check-label" for="notif_email">
                                    Recibir notificaciones por email
                                </label>
                            </div>
                            <div class="form-text">Te enviaremos emails sobre tus tareas próximas a vencer.</div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notif_diaria" name="notif_diaria">
                                <label class="form-check-label" for="notif_diaria">
                                    Resumen diario de tareas
                                </label>
                            </div>
                            <div class="form-text">Recibe un resumen diario de tus tareas pendientes.</div>
                        </div>

                        <h5 class="mb-4">Visualización</h5>
                        
                        <div class="mb-4">
                            <label class="form-label">Vista predeterminada de tareas</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="vista_default" id="vista_lista" value="lista" checked>
                                <label class="form-check-label" for="vista_lista">
                                    Vista de lista
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="vista_default" id="vista_calendario" value="calendario">
                                <label class="form-check-label" for="vista_calendario">
                                    Vista de calendario
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="items_por_pagina" class="form-label">Tareas por página</label>
                            <select class="form-select" id="items_por_pagina" name="items_por_pagina">
                                <option value="10">10 tareas</option>
                                <option value="25">25 tareas</option>
                                <option value="50">50 tareas</option>
                                <option value="100">100 tareas</option>
                            </select>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-4 text-danger">Zona de Peligro</h5>
                        
                        <div class="mb-4">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarCuentaModal">
                                <i class="fas fa-trash"></i> Eliminar Cuenta
                            </button>
                            <div class="form-text text-danger">
                                Esta acción es irreversible y eliminará todos tus datos.
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Eliminar Cuenta -->
<div class="modal fade" id="eliminarCuentaModal" tabindex="-1" aria-labelledby="eliminarCuentaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="eliminarCuentaModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Eliminar Cuenta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="fw-bold">¿Estás seguro de que deseas eliminar tu cuenta?</p>
                <p>Esta acción:</p>
                <ul>
                    <li>Eliminará permanentemente tu cuenta</li>
                    <li>Eliminará todas tus tareas y configuraciones</li>
                    <li>No se puede deshacer</li>
                </ul>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i> Para confirmar, escribe "ELIMINAR" en el campo de abajo:
                </div>
                <input type="text" class="form-control" id="confirmDelete" placeholder="Escribe ELIMINAR">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmDelete" disabled>
                    <i class="fas fa-trash"></i> Eliminar Cuenta
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Habilitar/deshabilitar botón de eliminar cuenta
document.getElementById('confirmDelete').addEventListener('input', function() {
    var deleteBtn = document.getElementById('btnConfirmDelete');
    deleteBtn.disabled = this.value !== 'ELIMINAR';
});

// Manejar eliminación de cuenta
document.getElementById('btnConfirmDelete').addEventListener('click', function() {
    window.location.href = '<?= BASE_URL ?>/usuario/eliminar';
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
