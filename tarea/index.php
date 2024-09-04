<?php

$host = 'localhost';
$dbname = 'imagen';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['precio']) && isset($_FILES['image'])) {
    $nombreImagen = basename($_FILES["image"]["name"]);
    $precio = $_POST['precio'];
    $fecha = date('Y-m-d H:i:s');

   
    $targetFile = __DIR__ . '/' . $nombreImagen;

    
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo "Error al subir la imagen: " . $_FILES['image']['error'];
        exit;
    }

   
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
       
        $stmt = $pdo->prepare("INSERT INTO imagen (nombre, descripcion, precio, fecha, imagen) VALUES (:nombre, '', :precio, :fecha, :imagen)");
        $stmt->execute([
            ':nombre' => $nombreImagen,
            ':precio' => $precio,
            ':fecha' => $fecha,
            ':imagen' => $nombreImagen,
        ]);

        echo "Subido correctamente";
    } else {
        echo "Lo siento, hubo un error al subir tu archivo.";
    }
    exit;
}


if (isset($_GET['action']) && $_GET['action'] == 'getItems') {
    header('Content-Type: application/json');
    $stmt = $pdo->query("SELECT * FROM imagen");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Imágenes</title>
    <script src="ajax.js" defer></script>
</head>
<body>
    <h1>Subir Imagen</h1>
    <form id="uploadForm" action="index.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <input type="number" name="precio" placeholder="Precio" required>
        <button type="submit">Subir Imagen</button>
    </form>
    <div id="uploadStatus"></div>

    <h2>Lista de Imágenes Disponibles para Comprar</h2>
    <div id="item-list"></div>
</body>
</html>
