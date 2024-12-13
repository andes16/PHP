<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'ips_vacunate';
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
function crearUsuario($pdo, $nombre, $apellido, $telefono) {
    try {
        $sql = "INSERT INTO factura (nombre, apellido, telefono) VALUES (:nombre, :apellido, :telefono)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->execute();
        echo "Usuario creado exitosamente.";
    } catch (PDOException $e) {
        echo "Error al crear usuario: " . $e->getMessage();
    }
}

function leerUsuario($pdo, $nombre, $apellido, $telefono) {
    try {
        $sql = "SELECT * FROM factura WHERE nombre = :nombre OR apellido = :apellido OR telefono = :telefono";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($usuarios) {
            foreach ($usuarios as $usuario) {
                echo "ID: " . $usuario['id'] . "<br>";
                echo "Nombre: " . $usuario['nombre'] . "<br>";
                echo "Apellido: " . $usuario['apellido'] . "<br>";
                echo "Teléfono: " . $usuario['telefono'] . "<br><br>";
            }
        } else {
            echo "No se encontraron usuarios con los datos proporcionados.";
        }
    } catch (PDOException $e) {
        echo "Error al buscar usuario: " . $e->getMessage();
    }
}

function actualizarUsuario($pdo, $nombre, $apellido, $telefono) {
    try {
        $sql = "UPDATE factura SET apellido = :apellido, telefono = :telefono WHERE nombre = :nombre";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);

        if ($stmt->execute()) {
            echo "Usuario actualizado exitosamente.";
        } else {
            echo "Error al actualizar usuario.";
        }
    } catch (PDOException $e) {
        echo "Error al actualizar usuario: " . $e->getMessage();
    }
}

function eliminarUsuario($pdo, $nombre, $apellido, $telefono) {
    try {
        $sql = "DELETE FROM factura WHERE nombre = :nombre OR apellido = :apellido OR telefono = :telefono";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);

        if ($stmt->execute()) {
            echo "Usuario eliminado exitosamente.";
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
    $telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''));

    switch ($accion) {
        case 'create':
            if ($nombre && $apellido && $telefono) {
                crearUsuario($pdo, $nombre, $apellido, $telefono);
            } else {
                echo "Error: todos los campos son obligatorios para crear un usuario.";
            }
            break;

        case 'read':
            leerUsuario($pdo, $nombre, $apellido, $telefono);
            break;

        case 'update':
            if ($nombre && ($apellido || $telefono)) {
                actualizarUsuario($pdo, $nombre, $apellido, $telefono);
            } else {
                echo "Error: el nombre y al menos un dato adicional son obligatorios para actualizar.";
            }
            break;

        case 'delete':
            eliminarUsuario($pdo, $nombre, $apellido, $telefono);
            break;

        default:
            echo "Error: acción no reconocida.";
            break;
    }
}
?>

<?php
require_once 'conexion.php'; // Asegúrate de incluir tu archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id_cliente = $_POST['cliente'] ?? null; // Asegúrate de que el nombre sea correcto
    $nombre_producto = $_POST['nombre_producto'] ?? null;
    $valor_producto = $_POST['valor'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;
    $valor_total = $_POST['valor_total'] ?? null;

    // Depuración: Verificar qué datos estamos recibiendo
    var_dump($_POST);

    switch ($accion) {
        case 'create':
            if ($id_cliente && $id_producto && $cantidad && $valor_total) {
                $query = "INSERT INTO facturas (id_cliente, id_producto, cantidad, valor_total) VALUES (:id_cliente, :id_producto, :cantidad, :valor_total)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    ':id_cliente' => $id_cliente,
                    ':id_producto' => $id_producto,
                    ':cantidad' => $cantidad,
                    ':valor_total' => $valor_total,
                ]);
                echo "Factura creada correctamente.";
            } else {
                echo "Por favor, complete todos los campos.";
            }
            break;

        case 'read':
            $query = "SELECT f.id_factura, c.nombre AS cliente, p.nombre_producto AS producto, f.cantidad, f.valor_total 
                      FROM facturas f
                      JOIN cliente c ON f.id_cliente = c.id_cliente
                      JOIN productos p ON f.id_producto = p.id_producto";
            $stmt = $pdo->query($query);
            $facturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<h3>Listado de Facturas:</h3>";
            echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Cliente</th><th>Producto</th><th>Cantidad</th><th>Valor Total</th></tr>";
            foreach ($facturas as $factura) {
                echo "<tr>
                        <td>{$factura['id_factura']}</td>
                        <td>{$factura['cliente']}</td>
                        <td>{$factura['producto']}</td>
                        <td>{$factura['cantidad']}</td>
                        <td>{$factura['valor_total']}</td>
                      </tr>";
            }
            echo "</table>";
            break;

        case 'update':
            if ($id_cliente && $id_producto && $cantidad && $valor_total) {
                $id_factura = $_POST['id_factura'] ?? null;
                if ($id_factura) {
                    $query = "UPDATE facturas SET id_cliente = :id_cliente, id_producto = :id_producto, cantidad = :cantidad, valor_total = :valor_total WHERE id_factura = :id_factura";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute([
                        ':id_cliente' => $id_cliente,
                        ':id_producto' => $id_producto,
                        ':cantidad' => $cantidad,
                        ':valor_total' => $valor_total,
                        ':id_factura' => $id_factura,
                    ]);
                    echo "Factura actualizada correctamente.";
                } else {
                    echo "Por favor, proporcione el ID de la factura a actualizar.";
                }
            } else {
                echo "Por favor, complete todos los campos.";
            }
            break;

        case 'delete':
            $id_factura = $_POST['id_factura'] ?? null;
            if ($id_factura) {
                $query = "DELETE FROM facturas WHERE id_factura = :id_factura";
                $stmt = $pdo->prepare($query);
                $stmt->execute([':id_factura' => $id_factura]);
                echo "Factura eliminada correctamente.";
            } else {
                echo "Por favor, proporcione el ID de la factura a eliminar.";
            }
            break;

        default:
            echo "Acción no válida.";
            break;
    }
}
?>