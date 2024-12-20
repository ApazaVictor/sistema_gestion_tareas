<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="mb-0"><?php echo $nombresMeses[(int)$mes] . ' ' . $anio; ?></h2>
                        </div>
                        <div class="col-auto">
                            <a href="?mes=<?php echo $mes-1 < 1 ? 12 : $mes-1; ?>&anio=<?php echo $mes-1 < 1 ? $anio-1 : $anio; ?>" class="btn btn-light">&lt; Anterior</a>
                            <a href="?mes=<?php echo $mes+1 > 12 ? 1 : $mes+1; ?>&anio=<?php echo $mes+1 > 12 ? $anio+1 : $anio; ?>" class="btn btn-light">Siguiente &gt;</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Domingo</th>
                                <th>Lunes</th>
                                <th>Martes</th>
                                <th>Miércoles</th>
                                <th>Jueves</th>
                                <th>Viernes</th>
                                <th>Sábado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $diaActual = 1;
                            $diaSemana = $diaSemanaInicio;

                            while ($diaActual <= $numeroDias) {
                                if ($diaSemana == 0) {
                                    echo '<tr>';
                                }

                                if ($diaActual == 1 && $diaSemana != 0) {
                                    for ($i = 0; $i < $diaSemana; $i++) {
                                        echo '<td class="bg-light"></td>';
                                    }
                                }

                                echo '<td class="position-relative" style="height: 120px; min-width: 120px;">';
                                echo '<div class="fw-bold">' . $diaActual . '</div>';
                                
                                if (isset($tareasPorDia[$diaActual]) && !empty($tareasPorDia[$diaActual])) {
                                    echo '<div class="task-list" style="max-height: 90px; overflow-y: auto;">';
                                    foreach ($tareasPorDia[$diaActual] as $tarea) {
                                        $colorClase = 'bg-info';
                                        if (isset($tarea['prioridad'])) {
                                            switch(strtolower($tarea['prioridad'])) {
                                                case 'alta':
                                                    $colorClase = 'bg-danger';
                                                    break;
                                                case 'media':
                                                    $colorClase = 'bg-warning';
                                                    break;
                                                case 'baja':
                                                    $colorClase = 'bg-success';
                                                    break;
                                            }
                                        }
                                        
                                        $estadoIcono = '<i class="fas fa-circle"></i>';
                                        if (isset($tarea['estado'])) {
                                            switch(strtolower($tarea['estado'])) {
                                                case 'pendiente':
                                                    $estadoIcono = '<i class="fas fa-clock"></i>';
                                                    break;
                                                case 'en_progreso':
                                                    $estadoIcono = '<i class="fas fa-spinner fa-spin"></i>';
                                                    break;
                                                case 'completada':
                                                    $estadoIcono = '<i class="fas fa-check"></i>';
                                                    break;
                                            }
                                        }
                                        
                                        echo '<div class="task-item p-1 mb-1 rounded ' . $colorClase . ' text-white" 
                                                  style="font-size: 0.8rem; cursor: pointer;" 
                                                  data-bs-toggle="tooltip" 
                                                  data-bs-placement="top" 
                                                  title="' . htmlspecialchars($tarea['descripcion'] ?? '') . '">';
                                        echo $estadoIcono . ' ' . htmlspecialchars(substr($tarea['titulo'] ?? 'Sin título', 0, 15));
                                        if(isset($tarea['titulo']) && strlen($tarea['titulo']) > 15) echo '...';
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                }
                                
                                echo '</td>';

                                if ($diaSemana == 6) {
                                    echo '</tr>';
                                    $diaSemana = 0;
                                } else {
                                    $diaSemana++;
                                }
                                $diaActual++;
                            }

                            if ($diaSemana != 0) {
                                while ($diaSemana < 7) {
                                    echo '<td class="bg-light"></td>';
                                    $diaSemana++;
                                }
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.task-list {
    scrollbar-width: thin;
    scrollbar-color: #888 #f0f0f0;
}

.task-list::-webkit-scrollbar {
    width: 6px;
}

.task-list::-webkit-scrollbar-track {
    background: #f0f0f0;
}

.task-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.task-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.task-item {
    transition: opacity 0.2s;
}

.task-item:hover {
    opacity: 0.9;
}
</style>

<!-- Inicializar los tooltips de Bootstrap -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
