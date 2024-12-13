<html>
<body>
<?php
echo "Tu Nombre es: ". $_POST["nombre"]."<br>";
echo "Tu Apellido es: ". $_POST["apellido"]. "<br>";
echo "Tu Numero de Telefono es: ". $_POST["telefono"]. "<br>";

?>
<?php
$servername = "localhost";
$username = "root";
$password = "";

// crea la conexion
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Conexion Fallida: " . $conn->connect_error);
}
echo "Conexion Correcta, con MySQL orientado a Objetos </br>";
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
  die("Conexion Fallida: " . mysqli_connect_error());
}
echo "Conexion Exitosa, con MySQL orientado a Procedimientos </br>";
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
  $conn = new PDO("mysql:host=$servername;dbname=", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Conexion Exitosa, con PDO Orientada a Objetos, extencion de PHP </br> :";
} catch(PDOException $e) {
  echo "Conexion Fallida, con PDO Orientada a Objetos, extencion de PHP </br> " . $e->getMessage();
}
?>
</body>
</html>