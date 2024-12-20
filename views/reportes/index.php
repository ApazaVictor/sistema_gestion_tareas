<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Reportes de Tareas</h3>
                    <button class="btn btn-primary" onclick="generarReporte()">
                        <i class="fas fa-file-pdf"></i> Generar PDF
                    </button>
                </div>
                <div class="card-body">
                    <!-- Alertas -->
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $_SESSION['error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $_SESSION['mensaje'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['mensaje']); ?>
                    <?php endif; ?>

                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="filtroEstado" class="form-label">Filtrar por Estado:</label>
                            <select class="form-select" id="filtroEstado">
                                <option value="">Todos</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="en_progreso">En Progreso</option>
                                <option value="completada">Completada</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tabla de Tareas -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Fecha de Creación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tareas as $tarea): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($tarea['nombre']) ?></td>
                                        <td><?= htmlspecialchars($tarea['descripcion']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= 
                                                $tarea['estado'] === 'pendiente' ? 'warning' : 
                                                ($tarea['estado'] === 'en_progreso' ? 'primary' : 'success') 
                                            ?>">
                                                <?= ucfirst($tarea['estado']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generarReporte() {
    const estado = document.getElementById('filtroEstado').value;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= BASE_URL ?>/reportes/generar';
    
    const estadoInput = document.createElement('input');
    estadoInput.type = 'hidden';
    estadoInput.name = 'estado';
    estadoInput.value = estado;
    
    form.appendChild(estadoInput);
    document.body.appendChild(form);
    form.submit();
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
