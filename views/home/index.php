<?php if (!isset($_SESSION['usuario_id'])): ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 mb-4">Bienvenido al Sistema de Tareas</h1>
            <p class="lead mb-5">Organiza y gestiona tus tareas de manera eficiente</p>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <i class="fas fa-tasks fa-3x text-primary mb-3"></i>
                            <h3>Gestión de Tareas</h3>
                            <p>Organiza y prioriza tus actividades diarias.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                            <h3>Seguimiento</h3>
                            <p>Monitorea el progreso de tus tareas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <i class="fas fa-calendar-alt fa-3x text-info mb-3"></i>
                            <h3>Calendario</h3>
                            <p>Visualiza y planifica tus actividades.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <a href="<?= BASE_URL ?>/usuario/registro" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-user-plus"></i> Registrarse
                </a>
                <a href="<?= BASE_URL ?>/usuario/login" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </a>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Panel de Control</h2>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Tareas Totales</h5>
                            <h2 class="card-text"><?= $estadisticas['total'] ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Pendientes</h5>
                            <h2 class="card-text"><?= $estadisticas['pendientes'] ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-4">
                        <div class="card-body">
                            <h5 class="card-title">En Progreso</h5>
                            <h2 class="card-text"><?= $estadisticas['en_progreso'] ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Completadas</h5>
                            <h2 class="card-text"><?= $estadisticas['completadas'] ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Tareas Recientes</h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($tareas_recientes) && !empty($tareas_recientes)): ?>
                                <div class="list-group">
                                    <?php foreach ($tareas_recientes as $tarea): ?>
                                        <a href="<?= BASE_URL ?>/tareas/editar/<?= $tarea['id'] ?>" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?= htmlspecialchars($tarea['titulo']) ?></h6>
                                                <small><?= $tarea['fecha_vencimiento'] ?></small>
                                            </div>
                                            <p class="mb-1"><?= htmlspecialchars($tarea['descripcion']) ?></p>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No hay tareas recientes.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Próximos Vencimientos</h5>
                        </div>
                        <div class="card-body">
                            <?php if (isset($proximos_vencimientos) && !empty($proximos_vencimientos)): ?>
                                <div class="list-group">
                                    <?php foreach ($proximos_vencimientos as $tarea): ?>
                                        <a href="<?= BASE_URL ?>/tareas/editar/<?= $tarea['id'] ?>" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?= htmlspecialchars($tarea['titulo']) ?></h6>
                                                <small class="text-danger"><?= $tarea['fecha_vencimiento'] ?></small>
                                            </div>
                                            <p class="mb-1"><?= htmlspecialchars($tarea['descripcion']) ?></p>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No hay próximos vencimientos.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>