// Función para mostrar alertas
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const alertContainer = document.getElementById('alertContainer');
    alertContainer.appendChild(alertDiv);
    
    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 150);
    }, 5000);
}

// Función para confirmar eliminación
function confirmDelete(message = '¿Estás seguro de que deseas eliminar este elemento?') {
    return confirm(message);
}

// Función para validar formularios
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
            
            // Crear mensaje de error si no existe
            if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'Este campo es requerido';
                field.parentNode.insertBefore(feedback, field.nextSibling);
            }
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Manejar cambios de estado en tareas
document.addEventListener('DOMContentLoaded', function() {
    const estadoSelects = document.querySelectorAll('.estado-tarea');
    
    estadoSelects.forEach(select => {
        select.addEventListener('change', function() {
            const tareaId = this.dataset.tareaId;
            const nuevoEstado = this.value;
            const estadoAnterior = this.dataset.estadoActual;
            
            // Crear FormData
            const formData = new FormData();
            formData.append('id', tareaId);
            formData.append('estado', nuevoEstado);

            // Enviar solicitud AJAX
            fetch('/tarea/cambiarEstado', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.dataset.estadoActual = nuevoEstado;
                    showAlert('Estado actualizado correctamente', 'success');
                } else {
                    this.value = estadoAnterior;
                    showAlert(data.message || 'Error al actualizar el estado', 'danger');
                }
            })
            .catch(error => {
                this.value = estadoAnterior;
                showAlert('Error de conexión', 'danger');
                console.error('Error:', error);
            });
        });
    });
});

// Validar formularios antes de enviar
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!validateForm(this.id)) {
                event.preventDefault();
            }
        });
    });
});

// Inicializar tooltips de Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Manejar el modo oscuro
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
}

// Aplicar modo oscuro si estaba activo
document.addEventListener('DOMContentLoaded', function() {
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
    }
});
