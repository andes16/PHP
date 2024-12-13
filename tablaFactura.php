<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'ips_vacunate_origen';
$usuario = 'root';
$contraseña = '';

try {
    // Crear una nueva conexión PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $contraseña);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("¡Error en la conexión a la base de datos!: " . $e->getMessage());
}

// Función para crear una nueva factura
function crearFactura($pdo, $id_cliente, $id_producto, $cantidad, $valor) {
    try {
        $sql = "INSERT INTO factura (id_cliente, id_producto, cantidad, valor) VALUES (:id_cliente, :id_producto, :cantidad, :valor)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_producto', $id_producto);
        $stmt->bindParam(':cantidad', $cantidad);
        $stmt->bindParam(':valor', $valor);
        $stmt->execute();
        echo "Factura creada exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear factura: " . $e->getMessage();
    }
}

// Manejo de los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';
    $id_cliente = $_POST['id_cliente'] ?? null;
    $id_producto = $_POST['id_producto'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;
    $valor = $_POST['valor'] ?? null;

    // Depuración: Verificar los datos recibidos
    var_dump($_POST);

    switch ($accion) {
        case 'create':
            if ($id_cliente && $id_producto && $cantidad && $valor) {
                crearFactura($pdo, $id_cliente, $id_producto, $cantidad, $valor);
            } else {
                echo "Error: todos los campos son obligatorios para crear una factura.";
            }
            break;

        
    }
}
?>
