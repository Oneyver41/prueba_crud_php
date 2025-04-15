<?php
// Incluimos el controlador
require_once 'controlador.php';
$controlador = new ControladorClientes();
$clientes = $controlador->obtenerClientes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Clientes</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="container">
        <h1>Gestión de Clientes</h1>
        
        <!-- Formulario para agregar/editar clientes -->
        <form action="controlador.php" method="post" class="client-form">
            <input type="hidden" name="id" id="cliente_id" value="">
            <input type="hidden" name="accion" id="accion" value="guardar">
            
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required>
            </div>
            
            <button type="submit" class="btn">Guardar</button>
        </form>
        
        <!-- Lista de clientes -->
        <h2>Lista de Clientes</h2>
        <?php if (empty($clientes)): ?>
            <p>No hay clientes registrados.</p>
        <?php else: ?>
            <table class="client-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $id => $cliente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['correo']); ?></td>
                            <td>
                                <span class="status <?php echo $cliente['activo'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $cliente['activo'] ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </td>
                            <td class="actions">
                                <button onclick="editarCliente(
                                    '<?php echo $id; ?>',
                                    '<?php echo addslashes($cliente['nombre']); ?>',
                                    '<?php echo addslashes($cliente['correo']); ?>'
                                )" class="btn edit">Editar</button>
                                
                                <form action="controlador.php" method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="accion" value="cambiar_estado">
                                    <button type="submit" class="btn status-btn">
                                        <?php echo $cliente['activo'] ? 'Desactivar' : 'Activar'; ?>
                                    </button>
                                </form>
                                
                                <form action="controlador.php" method="post" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <button type="submit" class="btn delete" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        function editarCliente(id, nombre, correo) {
            document.getElementById('cliente_id').value = id;
            document.getElementById('nombre').value = nombre;
            document.getElementById('correo').value = correo;
            document.getElementById('accion').value = 'guardar';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>