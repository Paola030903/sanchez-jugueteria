<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root"; // Cambiar si es necesario
$password = ""; // Cambiar si es necesario
$dbname = "juguete"; // Cambiar por el nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff;
            margin: 0;
            padding: 20px;
        }
        h1, h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        #cart {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }
        #total-items, #total-price {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Juguetería Sánchez</h1>

    <!-- Tabla de productos -->
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Cantidad Disponible</th>
            <th>Imagen</th>
            <th>Carrito</th>
        </tr>
        <?php
        $sql = "SELECT id, nombre, precio, cantidad, imagen FROM productos";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row["id"];
                $nombre = $row["nombre"];
                $precio = $row["precio"];
                $cantidad = $row["cantidad"];
                $imagen = $row["imagen"];
                $srcImagen = !empty($imagen) ? "data:image/jpeg;base64," . base64_encode($imagen) : "ruta/a/imagen/default.jpg";

                echo "<tr>
                        <td>{$id}</td>
                        <td>{$nombre}</td>
                        <td>\${$precio}</td>
                        <td>{$cantidad}</td>
                        <td><img src='{$srcImagen}' alt='{$nombre}' width='50' height='50'></td>
                        <td>
                            <input type='number' id='quantity-{$id}' value='1' min='1' max='{$cantidad}' style='width: 50px;'>
                            <button class='btn' onclick='addToCart({$id}, \"{$nombre}\", {$precio}, {$cantidad})'>Añadir</button>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No hay productos disponibles.</td></tr>";
        }

        $conn->close();
        ?>
    </table>

    <!-- Carrito de compras -->
    <div id="cart">
        <h2>Carrito de Compras</h2>
        <p id="total-items">Total de productos: 0</p>
        <p id="total-price">Total a pagar: $0.00</p>
        <table id="cart-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="cart-table-body">
                <!-- Productos añadidos aparecerán aquí -->
            </tbody>
        </table>
        <button class="btn" onclick="completePurchase()">Comprar</button>
    </div>

    <script>
        let cart = {};
        let totalItems = 0;
        let totalPrice = 0;

        function addToCart(id, name, price, availableQuantity) {
            let quantityInput = document.getElementById('quantity-' + id);
            let quantity = parseInt(quantityInput.value);

            if (quantity > availableQuantity) {
                alert('No puedes añadir más de ' + availableQuantity + ' unidades disponibles de ' + name + '.');
                return;
            }

            if (cart[id]) {
                cart[id].quantity += quantity;
            } else {
                cart[id] = {
                    name: name,
                    price: price,
                    quantity: quantity
                };
            }

            totalItems += quantity;
            totalPrice += price * quantity;
            updateCart();
        }

        function updateCart() {
            document.getElementById('total-items').innerText = `Total de productos: ${totalItems}`;
            document.getElementById('total-price').innerText = `Total a pagar: $${totalPrice.toFixed(2)}`;

            let cartTable = document.getElementById('cart-table-body');
            cartTable.innerHTML = '';

            for (let id in cart) {
                let product = cart[id];
                let totalProductPrice = product.price * product.quantity;

                cartTable.innerHTML += `
                    <tr>
                        <td>${id}</td>
                        <td>${product.name}</td>
                        <td>${product.price.toFixed(2)}</td>
                        <td>${product.quantity}</td>
                        <td>${totalProductPrice.toFixed(2)}</td>
                        <td>
                            <button class="btn" onclick="removeFromCart(${id})">Eliminar</button>
                        </td>
                    </tr>
                `;
            }
        }

        function removeFromCart(id) {
            if (cart[id]) {
                totalItems -= cart[id].quantity;
                totalPrice -= cart[id].price * cart[id].quantity;
                delete cart[id];
                updateCart();
            }
        }

        async function completePurchase() {
            if (Object.keys(cart).length === 0) {
                alert('El carrito está vacío.');
                return;
            }

            const confirmation = confirm('¿Deseas completar tu compra?');
            if (!confirmation) return;

            try {
                // Actualizar cantidades en la base de datos mediante una petición al servidor
                const response = await fetch('procesar_compra.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(cart)
                });

                const result = await response.json();

                if (result.success) {
                    alert('Compra realizada con éxito.');
                    cart = {};
                    totalItems = 0;
                    totalPrice = 0;
                    updateCart();
                    location.reload(); // Refrescar la página para mostrar inventario actualizado
                } else {
                    alert('Error al realizar la compra.');
                }
            } catch (error) {
                console.error(error);
                alert('Ocurrió un error al procesar la compra.');
            }
        }
    </script>
</body>
</html>
