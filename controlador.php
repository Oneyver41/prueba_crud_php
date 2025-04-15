<?php
class ControladorClientes {
    private $archivo = 'clientes.json';
    
    public function obtenerClientes() {
        if (file_exists($this->archivo)) {
            $contenido = file_get_contents($this->archivo);
            return json_decode($contenido, true) ?: [];
        }
        return [];
    }
    
    private function guardarClientes($clientes) {
        file_put_contents($this->archivo, json_encode($clientes, JSON_PRETTY_PRINT));
    }
    
    public function guardarCliente($datos) {
        $clientes = $this->obtenerClientes();
        
        if (empty($datos['id'])) {
            // Nuevo cliente
            $id = uniqid(); // Generamos un ID único
            $clientes[$id] = [
                'nombre' => $datos['nombre'],
                'correo' => $datos['correo'],
                'activo' => true
            ];
        } else {
            // Editar cliente existente
            $id = $datos['id'];
            if (isset($clientes[$id])) {
                $clientes[$id]['nombre'] = $datos['nombre'];
                $clientes[$id]['correo'] = $datos['correo'];
            }
        }
        
        $this->guardarClientes($clientes);
        return $id;
    }
    
    public function cambiarEstado($id) {
        $clientes = $this->obtenerClientes();
        if (isset($clientes[$id])) {
            $clientes[$id]['activo'] = !$clientes[$id]['activo'];
            $this->guardarClientes($clientes);
            return true;
        }
        return false;
    }
    
    public function eliminarCliente($id) {
        $clientes = $this->obtenerClientes();
        if (isset($clientes[$id])) {
            unset($clientes[$id]);
            $this->guardarClientes($clientes);
            return true;
        }
        return false;
    }
}

// Procesar las acciones
$controlador = new ControladorClientes();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    switch ($accion) {
        case 'guardar':
            $datos = [
                'id' => $_POST['id'] ?? '',
                'nombre' => $_POST['nombre'] ?? '',
                'correo' => $_POST['correo'] ?? ''
            ];
            $controlador->guardarCliente($datos);
            break;
            
        case 'cambiar_estado':
            if (isset($_POST['id'])) {
                $controlador->cambiarEstado($_POST['id']);
            }
            break;
            
        case 'eliminar':
            if (isset($_POST['id'])) {
                $controlador->eliminarCliente($_POST['id']);
            }
            break;
    }
    
    // Redirigir para evitar reenvío del formulario
    header('Location: index.php');
    exit;
}
?>