<!-- views/layouts/footer.php -->
</main>
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <span class="text-muted"> <?= date('Y') ?> Sistema de Gestión de Tareas. Todos los derechos reservados.</span>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?= BASE_URL ?>/assets/js/main.js"></script>

    <script>
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

        // Mostrar mensajes de sesión si existen
        <?php if (isset($_SESSION['mensaje'])): ?>
            showAlert('<?= $_SESSION['mensaje'] ?>', 'success');
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            showAlert('<?= $_SESSION['error'] ?>', 'danger');
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>