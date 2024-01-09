<?php
session_start();
$errors = array();
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_info']) && isset($_SESSION['cart'])) {
    $client_id = $_SESSION['user_info']['id_client'] ?? 0; // Presupunând că există informații despre client în sesiune
    $data_comenzii = date("Y-m-d H:i:s"); // Obține data și ora curentă

    $total = 0;

    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $produs) {
            $total += $produs['pretProdus'] * $produs['cantitate'];
        }
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "parfumuri"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO comenzi (id_client, data_comanda, total) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isd", $client_id, $data_comenzii, $total);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        foreach ($_SESSION['cart'] as $produs) {
            $produs_id = $produs['idProdus'];
            $cantitate = $produs['cantitate'];

            $sql = "UPDATE produse SET stoc = stoc - ? WHERE id_produse = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $cantitate, $produs_id);
            $stmt->execute();

            if ($stmt->affected_rows <= 0) {
                $errors[] = "Eroare la actualizarea stocului pentru produsul cu ID-ul: " . $produs_id;
            }
        }

        if (!empty($errors)) {
        } else {
            $success_message = "<h1>Comanda ta a fost finalizată!</h1>";
        }
    } else {
        $errors[] = "Eroare la finalizarea comenzii.";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <title>Comanda Finalizată</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 300px;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1,
        ul,
        p {
            text-align: center;
            margin: 0;
        }

        ul {
            padding: 0;
        }

        li {
            list-style: none;
            color: red;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if (!empty($errors)): ?>
            <h1>Eroare la finalizarea comenzii</h1>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li>
                        <?php echo $error; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <?php
            if (!empty($success_message)) {
                echo $success_message;
                $total = 0;
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $produs) {
                        $total += $produs['pretProdus'] * $produs['cantitate'];
                    }
                }
                ?>
                <p>Total: <?php echo $total; ?> lei</p>
            <?php } ?>
        <?php endif; ?>
        <p><a href="main.html">Înapoi la pagina principală</a></p>
    </div>
</body>

