<?php
class ControladorClientes {
    private $archivo_json = 'clientes.json';
    
    public function __construct() {
        // Procesar acciones si es una petición POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
            $this->procesarAccion();
        }
    }
    
    public function obtenerClientes() {
        if (file_exists($this->archivo_json)) {
            $json_data = file_get_contents($this->archivo_json);
            return json_decode($json_data, true) ?: [];
        }
        return [];
    }
    
    private function guardarClientes($clientes) {
        file_put_contents($this->archivo_json, json_encode($clientes, JSON_PRETTY_PRINT));
    }
    
    private function procesarAccion() {
        $clientes = $this->obtenerClientes();
        $accion = $_POST['accion'];
        $id = $_POST['id'] ?? null;
        
        switch ($accion) {
            case 'guardar':
                $nombre = trim($_POST['nombre']);
                $correo = trim($_POST['correo']);
                
                if (!empty($nombre) && !empty($correo)) {
                    $clientes[$id] = [
                        'nombre' => $nombre,
                        'correo' => $correo,
                        'activo' => $clientes[$id]['activo'] ?? true
                    ];
                    $this->guardarClientes($clientes);
                }
                break;
                
            case 'cambiar_estado':
                if (isset($clientes[$id])) {
                    $clientes[$id]['activo'] = !$clientes[$id]['activo'];
                    $this->guardarClientes($clientes);
                }
                break;
                
            case 'eliminar':
                if (isset($clientes[$id])) {
                    unset($clientes[$id]);
                    $this->guardarClientes($clientes);
                }
                break;
        }
        
        // Redirigir para evitar reenvío del formulario
        header('Location: index.php');
        exit;
    }
}

// Crear instancia del controlador
new ControladorClientes();
?>