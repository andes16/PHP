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

// Funciones CRUD
// Este bloque hace que cuando se le unda al boton crear se creen los usuarios y se guarden en la base de datos 
function crearUsuario($pdo, $nombre, $apellido, $tipo_de_documento, $numero_de_domicilio, $telefono, $fecha_de_nacimiento) {
    try {
        $sql = "INSERT INTO cliente (nombre, apellido, tipo_de_documento, numero_de_domicilio, telefono, fecha_de_nacimiento) VALUES (:nombre, :apellido, :tipo_de_documento, :numero_de_domicilio, :telefono, :fecha_de_nacimiento)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':tipo_de_documento', $tipo_de_documento);
        $stmt->bindParam(':numero_de_domicilio', $numero_de_domicilio);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_de_nacimiento', $fecha_de_nacimiento);
        $stmt->execute();
        echo "Usuario creado exitosamente ✅.";
    } catch (PDOException $e) {
        echo "Error al crear usuario: " . $e->getMessage();
    }
}

// Este bloque los lee y los proyecta en la pantalla 
function leerUsuario($pdo, $nombre, $apellido, $tipo_de_documento, $numero_de_domicilio, $telefono, $fecha_de_nacimiento) {
    try {
        $sql = "SELECT * FROM cliente WHERE nombre = :nombre OR apellido = :apellido OR tipo_de_documento = :tipo_de_documento OR numero_de_domicilio = :numero_de_domicilio OR telefono = :telefono OR fecha_de_nacimiento = :fecha_de_nacimiento";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':tipo_de_documento', $tipo_de_documento);
        $stmt->bindParam(':numero_de_domicilio', $numero_de_domicilio);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_de_nacimiento', $fecha_de_nacimiento);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($usuarios) {
            foreach ($usuarios as $usuario) {
                echo "ID clientes: ". $usuario['id_cliente']. "<br>";
                echo "Nombre: " . $usuario['nombre'] . "<br>";
                echo "Apellido: " . $usuario['apellido'] . "<br>";
                echo "Tipo de Documento: " . $usuario['tipo_de_documento'] . "<br>";
                echo "Número de Domicilio: " . $usuario['numero_de_domicilio'] . "<br>";
                echo "Teléfono: " . $usuario['telefono'] . "<br>";
                echo "Fecha de Nacimiento: " . $usuario['fecha_de_nacimiento'] . "<br><br>";
            }
        } else {
            echo "No se encontraron usuarios con los datos proporcionados.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar usuario: " . $e->getMessage();
    }
}

// Aqui se actualizan los usuarios en la base de datos 
function actualizarUsuario($pdo, $nombre, $apellido, $tipo_de_documento, $numero_de_domicilio, $telefono, $fecha_de_nacimiento) {
    try {
        $sql = "UPDATE cliente SET apellido = :apellido, tipo_de_documento = :tipo_de_documento, numero_de_domicilio = :numero_de_domicilio, telefono = :telefono, fecha_de_nacimiento = :fecha_de_nacimiento WHERE nombre = :nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':tipo_de_documento', $tipo_de_documento);
        $stmt->bindParam(':numero_de_domicilio', $numero_de_domicilio);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':fecha_de_nacimiento', $fecha_de_nacimiento);

        if ($stmt->execute()) {
            echo "Usuario actualizado exitosamente ✅.";
        } else {
            echo "Error al actualizar usuario.";
        }
    } catch (PDOException $e) {
        echo "Error al actualizar usuario: " . $e->getMessage();
    }
}

// Aqui elimina los usuarios de la base de datos 
function eliminarUsuario($pdo, $nombre, $apellido, $tipo_de_documento, $numero_de_domicilio, $telefono, $fecha_de_nacimiento) {
    try {
        $sql = "DELETE FROM cliente WHERE nombre = :nombre OR apellido = :apellido OR telefono = :telefono";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);
        
        if ($stmt->execute()) {
            echo "Usuario eliminado exitosamente ✅.";
        } else {
            echo "Error al eliminar usuario.";
        }
    } catch (PDOException $e) {
        echo "Error al eliminar usuario: " . $e->getMessage();
    }
}

// Manejo de datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'] ?? '';
    $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
    $apellido = htmlspecialchars(trim($_POST['apellido'] ?? ''));
    $tipo_de_documento = htmlspecialchars(trim($_POST['tipo_de_documento'] ?? ''));
    $numero_de_domicilio = htmlspecialchars(trim($_POST['numero_de_domicilio'] ?? ''));
    $telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''));
    $fecha_de_nacimiento = htmlspecialchars(trim($_POST['fecha_de_nacimiento'] ?? ''));

    switch ($accion) {
        case 'create':
            if ($nombre && $apellido && $tipo_de_documento && $numero_de_domicilio && $telefono && $fecha_de_nacimiento) {
                crearUsuario($pdo, $nombre, $apellido, $tipo_de_documento, $numero_de_domicilio, $telefono, $fecha_de_nacimiento);
            } else {
                echo "Error: todos los campos son obligatorios para crear un usuario.";
            }
            break;

        case 'read':
            leerUsuario($pdo, $nombre, $apellido, $tipo_de_documento, $numero_de_domicilio, $telefono, $fecha_de_nacimiento);
            break;

        case 'update':
            if ($nombre && $apellido && $tipo_de_documento && $numero_de_domicilio && $telefono && $fecha_de_nacimiento) {
                actualizarUsuario($pdo, $nombre, $apellido, $tipo_de_documento, $numero_de_domicilio, $telefono, $fecha_de_nacimiento);
            } else {
                echo "Error: el nombre y al menos un dato adicional son obligatorios para actualizar.";
            }
            break;

        case 'delete':
            eliminarUsuario($pdo, $nombre, $apellido, $tipo_de_documento, $numero_de_domicilio, $telefono, $fecha_de_nacimiento);
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>

