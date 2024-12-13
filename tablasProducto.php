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
function crearUsuario($pdo, $nombre_de_producto, $lote_de_producto, $valor) {
    try {
        $sql = "INSERT INTO producto (nombre_de_producto, lote_de_producto, valor) VALUES (:nombre_de_producto, :lote_de_producto, :valor)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_de_producto', $nombre_de_producto);
        $stmt->bindParam(':lote_de_producto', $lote_de_producto);
        $stmt->bindParam(':valor', $valor);
        $stmt->execute();
        echo "Usuario creado exitosamente ✅.";
    } catch (PDOException $e) {
        echo "Error al crear usuario: " . $e->getMessage();
    }
}

function leerUsuario($pdo, $nombre_de_producto, $lote_de_producto, $valor) {
    try {
        $sql = "SELECT * FROM producto WHERE nombre_de_producto = :nombre_de_producto OR lote_de_producto = :lote_de_producto OR valor = :valor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_de_producto', $nombre_de_producto);
        $stmt->bindParam(':lote_de_producto', $lote_de_producto);
        $stmt->bindParam(':valor', $valor);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($usuarios) {
            foreach ($usuarios as $usuario) {
                echo "ID clientes: ". $usuario['id_producto']. "<br>";
                echo "nombre_de_producto: " . $usuario['nombre_de_producto'] . "<br>";
                echo "lote_de_producto: " . $usuario['lote_de_producto'] . "<br>";
                echo "Teléfono: " . $usuario['valor'] . "<br><br>";
            }
        } else {
            echo "No se encontraron usuarios con los datos proporcionados.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar usuario: " . $e->getMessage();
    }
}

function actualizarUsuario($pdo, $nombre_de_producto, $lote_de_producto, $valor) {
    try {
        $sql = "UPDATE producto SET lote_de_producto = :lote_de_producto, valor = :valor WHERE nombre_de_producto = :nombre_de_producto";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_de_producto', $nombre_de_producto);
        $stmt->bindParam(':lote_de_producto', $lote_de_producto);
        $stmt->bindParam(':valor', $valor);

        if ($stmt->execute()) {
            echo "Usuario actualizado exitosamente ✅.";
        } else {
            echo "Error al actualizar usuario.";
        }
    } catch (PDOException $e) {
        echo "Error al actualizar usuario: " . $e->getMessage();
    }
}

function eliminarUsuario($pdo, $nombre_de_producto, $lote_de_producto, $valor) {
    try {
        $sql = "DELETE FROM producto WHERE nombre_de_producto = :nombre_de_producto OR lote_de_producto = :lote_de_producto OR valor = :valor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_de_producto', $nombre_de_producto);
        $stmt->bindParam(':lote_de_producto', $lote_de_producto);
        $stmt->bindParam(':valor', $valor);

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
    $nombre_de_producto = htmlspecialchars(trim($_POST['nombre_de_producto'] ?? ''));
    $lote_de_producto = htmlspecialchars(trim($_POST['lote_de_producto'] ?? ''));
    $valor = htmlspecialchars(trim($_POST['valor'] ?? ''));

    switch ($accion) {
        case 'create':
            if ($nombre_de_producto && $lote_de_producto && $valor) {
                crearUsuario($pdo, $nombre_de_producto, $lote_de_producto, $valor);
            } else {
                echo "Error: todos los campos son obligatorios para crear un usuario.";
            }
            break;

        case 'read':
            leerUsuario($pdo, $nombre_de_producto, $lote_de_producto, $valor);
            break;

        case 'update':
            if ($nombre_de_producto && ($lote_de_producto || $valor)) {
                actualizarUsuario($pdo, $nombre_de_producto, $lote_de_producto, $valor);
            } else {
                echo "Error: el nombre_de_producto y al menos un dato adicional son obligatorios para actualizar.";
            }
            break;

        case 'delete':
            eliminarUsuario($pdo, $nombre_de_producto, $lote_de_producto, $valor);
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>
