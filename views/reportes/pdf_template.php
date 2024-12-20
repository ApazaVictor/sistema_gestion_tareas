<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin-bottom: 5px;
        }
        .info {
            text-align: right;
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        .estado {
            padding: 3px 8px;
            border-radius: 3px;
            color: white;
            font-size: 12px;
            display: inline-block;
        }
        .pendiente {
            background-color: #ffc107;
        }
        .en_progreso {
            background-color: #0d6efd;
        }
        .completada {
            background-color: #28a745;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Tareas</h1>
        <p>Sistema de Gestión de Tareas</p>
    </div>

    <div class="info">
        <p>Fecha de generación: <?= date('d/m/Y H:i:s') ?></p>
        <p>Usuario: <?= htmlspecialchars($_SESSION['usuario_nombre']) ?></p>
    </div>

    <table>
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
                        <span class="estado <?= $tarea['estado'] ?>">
                            <?= ucfirst($tarea['estado']) ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($tarea['fecha_creacion'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>© <?= date('Y') ?> Sistema de Gestión de Tareas - Todos los derechos reservados</p>
    </div>
</body>
</html>
