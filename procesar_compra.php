<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambiar si es necesario
$password = ""; // Cambiar si es necesario
$dbname = "juguete"; // Cambiar por el nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false];

if (!empty($data)) {
    $conn->begin_transaction();

    try {
        foreach ($data as $id => $product) {
            $quantity = $product['quantity'];
            $sql = "UPDATE productos SET cantidad = cantidad - ? WHERE id = ? AND cantidad >= ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iii', $quantity, $id, $quantity);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                throw new Exception("Stock insuficiente para el producto con ID: $id");
            }
        }

        $conn->commit();
        $response['success'] = true;
    } catch (Exception $e) {
        $conn->rollback();
        $response['message'] = $e->getMessage();
    }
}

$conn->close();
echo json_encode($response);
?>
