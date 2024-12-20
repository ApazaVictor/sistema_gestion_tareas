<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/tareas">Tareas</a></li>
                    <li class="breadcrumb-item active">Editar Tarea</li>
                </ol>
            </nav>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-edit"></i> Editar Tarea</h4>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/tareas/editar/<?= $tarea['id'] ?>" method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="titulo" name="titulo" 
                                       value="<?= htmlspecialchars($tarea['titulo']) ?>" required>
                                <div class="invalid-feedback">
                                    Por favor ingrese un título para la tarea.
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" 
                                          rows="4" style="resize: none;"><?= htmlspecialchars($tarea['descripcion']) ?></textarea>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="fecha_vencimiento" 
                                       name="fecha_vencimiento" value="<?= date('Y-m-d', strtotime($tarea['fecha_vencimiento'])) ?>" required>
                                <div class="invalid-feedback">
                                    Por favor seleccione una fecha de vencimiento.
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="prioridad" class="form-label">Prioridad <span class="text-danger">*</span></label>
                                <select class="form-select" id="prioridad" name="prioridad" required>
                                    <option value="baja" <?= $tarea['prioridad'] == 'baja' ? 'selected' : '' ?>>Baja</option>
                                    <option value="media" <?= $tarea['prioridad'] == 'media' ? 'selected' : '' ?>>Media</option>
                                    <option value="alta" <?= $tarea['prioridad'] == 'alta' ? 'selected' : '' ?>>Alta</option>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor seleccione una prioridad.
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select" id="estado" name="estado" required>
                                    <option value="pendiente" <?= $tarea['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="en_progreso" <?= $tarea['estado'] == 'en_progreso' ? 'selected' : '' ?>>En Progreso</option>
                                    <option value="completada" <?= $tarea['estado'] == 'completada' ? 'selected' : '' ?>>Completada</option>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor seleccione un estado.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= BASE_URL ?>/tareas" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Información Adicional</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Fecha de Creación:</strong></p>
                            <p><?= $tarea['fecha_creacion_formato'] ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Última Modificación:</strong></p>
                            <p><?= isset($tarea['fecha_modificacion']) ? $tarea['fecha_modificacion'] : 'No modificada' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validación del formulario
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()

// Establecer la fecha mínima en el campo de fecha de vencimiento
document.getElementById('fecha_vencimiento').min = new Date().toISOString().split('T')[0];
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
