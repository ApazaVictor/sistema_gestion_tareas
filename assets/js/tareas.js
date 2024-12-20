document.addEventListener('DOMContentLoaded', function() {
    // Manejador para cambios de estado de tareas
    document.querySelectorAll('.cambiar-estado').forEach(select => {
        select.addEventListener('change', function() {
            const tareaId = this.dataset.tareaId;
            const nuevoEstado = this.value;
            
            fetch(`${BASE_URL}/tarea/cambiarEstado`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${tareaId}&estado=${nuevoEstado}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarMensaje('Estado actualizado correctamente', 'success');
                    actualizarEstadisticas();
                } else {
                    mostrarMensaje('Error al actualizar el estado', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('Error al actualizar el estado', 'danger');
            });
        });
    });

    // Manejador para eliminar tareas
    document.querySelectorAll('.eliminar-tarea').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
                const tareaId = this.dataset.tareaId;
                window.location.href = `${BASE_URL}/tarea/eliminar/${tareaId}`;
            }
        });
    });

    // Función para actualizar estadísticas
    function actualizarEstadisticas() {
        fetch(`${BASE_URL}/tarea/obtenerEstadisticas`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-tareas').textContent = data.total;
                document.getElementById('tareas-pendientes').textContent = data.pendientes;
                document.getElementById('tareas-en-progreso').textContent = data.en_progreso;
                document.getElementById('tareas-completadas').textContent = data.completadas;
                
                // Actualizar gráfico si existe
                if (window.myChart) {
                    window.myChart.data.datasets[0].data = [
                        data.pendientes,
                        data.en_progreso,
                        data.completadas
                    ];
                    window.myChart.update();
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Función para mostrar mensajes
    function mostrarMensaje(mensaje, tipo) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const container = document.querySelector('.container');
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-cerrar después de 3 segundos
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }

    // Inicializar Chart.js si existe el canvas
    const ctx = document.getElementById('estadisticasChart');
    if (ctx) {
        window.myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pendientes', 'En Progreso', 'Completadas'],
                datasets: [{
                    data: [
                        parseInt(document.getElementById('tareas-pendientes').textContent),
                        parseInt(document.getElementById('tareas-en-progreso').textContent),
                        parseInt(document.getElementById('tareas-completadas').textContent)
                    ],
                    backgroundColor: [
                        '#ffc107', // Amarillo para pendientes
                        '#17a2b8', // Azul para en progreso
                        '#28a745'  // Verde para completadas
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Validación de formularios
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Función para editar tarea
    function editarTarea(id, nombre, descripcion, estado) {
        // Llenar el modal con los datos de la tarea
        document.getElementById('editar_id').value = id;
        document.getElementById('editar_nombre').value = nombre;
        document.getElementById('editar_descripcion').value = descripcion;
        document.getElementById('editar_estado').value = estado;
        
        // Mostrar el modal
        var modal = new bootstrap.Modal(document.getElementById('editarTareaModal'));
        modal.show();
    }

    // Función para confirmar eliminación
    function confirmarEliminar(id) {
        if (confirm('¿Está seguro de que desea eliminar esta tarea?')) {
            // Crear un formulario dinámicamente
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = BASE_URL + '/tareas/eliminar';
            
            // Agregar el ID de la tarea
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = id;
            form.appendChild(idInput);
            
            // Agregar el formulario al documento y enviarlo
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Manejar el envío del formulario de edición
    document.getElementById('editarTareaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(BASE_URL + '/tareas/editar', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cerrar el modal
                bootstrap.Modal.getInstance(document.getElementById('editarTareaModal')).hide();
                
                // Recargar la página para mostrar los cambios
                location.reload();
            } else {
                alert('Error al actualizar la tarea: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al actualizar la tarea');
        });
    });
});
