<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Acerca del Sistema de Gestión de Tareas</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-tasks fa-4x text-primary"></i>
                    </div>
                    
                    <h4>Descripción</h4>
                    <p>
                        El Sistema de Gestión de Tareas es una aplicación web diseñada para ayudarte a organizar y gestionar tus tareas
                        de manera eficiente. Con una interfaz intuitiva y funciones útiles, podrás mantener un seguimiento efectivo de
                        todas tus actividades.
                    </p>

                    <h4 class="mt-4">Características Principales</h4>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i> Gestión completa de tareas</li>
                        <li><i class="fas fa-check text-success me-2"></i> Calendario interactivo</li>
                        <li><i class="fas fa-check text-success me-2"></i> Generación de reportes</li>
                        <li><i class="fas fa-check text-success me-2"></i> Interfaz responsive</li>
                        <li><i class="fas fa-check text-success me-2"></i> Sistema de usuarios</li>
                    </ul>

                    <h4 class="mt-4">Desarrollador</h4>
                    <p>
                        <strong>Victor Hugo</strong><br>
                        <small class="text-muted">Desarrollador Web Full Stack</small>
                    </p>

                    <h4 class="mt-4">Tecnologías Utilizadas</h4>
                    <div class="row text-center">
                        <div class="col-4 col-md-2">
                            <i class="fab fa-php fa-2x"></i>
                            <p class="mt-2">PHP</p>
                        </div>
                        <div class="col-4 col-md-2">
                            <i class="fab fa-js fa-2x"></i>
                            <p class="mt-2">JavaScript</p>
                        </div>
                        <div class="col-4 col-md-2">
                            <i class="fab fa-bootstrap fa-2x"></i>
                            <p class="mt-2">Bootstrap</p>
                        </div>
                        <div class="col-4 col-md-2">
                            <i class="fas fa-database fa-2x"></i>
                            <p class="mt-2">MySQL</p>
                        </div>
                        <div class="col-4 col-md-2">
                            <i class="fab fa-html5 fa-2x"></i>
                            <p class="mt-2">HTML5</p>
                        </div>
                        <div class="col-4 col-md-2">
                            <i class="fab fa-css3-alt fa-2x"></i>
                            <p class="mt-2">CSS3</p>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted">Versión 1.0.0</p>
                        <p class="text-muted">&copy; <?= date('Y') ?> Sistema de Gestión de Tareas. Todos los derechos reservados.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
