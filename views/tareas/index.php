<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-tasks"></i> Mis Tareas</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?= BASE_URL ?>/tareas/generar-pdf" class="btn btn-info me-2" title="Generar PDF">
                <i class="fas fa-file-pdf"></i> Generar PDF
            </a>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nuevaTareaModal">
                <i class="fas fa-plus"></i> Nueva Tarea
            </button>
        </div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if (empty($tareas)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="lead">No tienes tareas pendientes</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaTareaModal">
                        <i class="fas fa-plus"></i> Crear Primera Tarea
                    </button>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Descripción</th>
                                <th>Fecha de Creación</th>
                                <th>Fecha de Vencimiento</th>
                                <th>Prioridad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tareas as $tarea): ?>
                                <tr>
                                    <td><?= htmlspecialchars($tarea['titulo']) ?></td>
                                    <td><?= htmlspecialchars($tarea['descripcion']) ?></td>
                                    <td><?= $tarea['fecha_creacion_formato'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($tarea['fecha_vencimiento'])) ?></td>
                                    <td>
                                        <span class="badge <?php
                                            switch(strtolower($tarea['prioridad'])) {
                                                case 'alta':
                                                    echo 'bg-danger';
                                                    break;
                                                case 'media':
                                                    echo 'bg-warning';
                                                    break;
                                                case 'baja':
                                                    echo 'bg-success';
                                                    break;
                                                default:
                                                    echo 'bg-secondary';
                                            }
                                        ?>">
                                            <?= htmlspecialchars($tarea['prioridad']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php
                                            switch(strtolower($tarea['estado'])) {
                                                case 'pendiente':
                                                    echo 'bg-warning';
                                                    break;
                                                case 'en_progreso':
                                                    echo 'bg-info';
                                                    break;
                                                case 'completada':
                                                    echo 'bg-success';
                                                    break;
                                                default:
                                                    echo 'bg-secondary';
                                            }
                                        ?>">
                                            <?= htmlspecialchars($tarea['estado']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/tareas/editar/<?= $tarea['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/tareas/eliminar/<?= $tarea['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarea?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para Nueva Tarea -->
<div class="modal fade" id="nuevaTareaModal" tabindex="-1" aria-labelledby="nuevaTareaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevaTareaModalLabel">Nueva Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= BASE_URL ?>/tareas/crear" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título</label>
                        <input type="text" class="form-control" id="titulo" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                    </div>
                    <div class="mb-3">
                        <label for="prioridad" class="form-label">Prioridad</label>
                        <select class="form-select" id="prioridad" name="prioridad" required>
                            <option value="baja">Baja</option>
                            <option value="media">Media</option>
                            <option value="alta">Alta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="en_progreso">En Progreso</option>
                            <option value="completada">Completada</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
