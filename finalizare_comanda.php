<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Finalizare comandă</title>
    <link rel="stylesheet" type="text/css" href="style_cos.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        h2 {
            margin-top: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        ul li img {
            margin-right: 10px;
            max-width: 80px;
            height: auto;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p {
            text-align: center;
        }

        .disabled-button {
            pointer-events: none;
            opacity: 0.5;
            cursor: not-allowed;
            background-color: gray;
            color: #fff;
        }
    </style>
</head>

<body>
    <h1>Finalizare comandă</h1>

    <?php
    session_start();

    $total = 0;

    if (!empty($_SESSION['cart'])) {
        echo '<div class="cart-details">';
        echo '<h2>Produsele achiziționate:</h2>';
        echo '<ul>';
        foreach ($_SESSION['cart'] as $produs) {
            echo '<li>';
            echo '<img src="' . $produs['imagineProdus'] . '" alt="' . $produs['numeProdus'] . '">';
            echo $produs['numeProdus'] . ' - ' . $produs['pretProdus'] . ' lei x ' . $produs['cantitate'];
            echo '</li>';

            $total += $produs['pretProdus'] * $produs['cantitate'];
        }
        echo '</ul>';
        echo '</div>';

        echo '<div class="total-section">';
        echo '<p style="text-align: left; margin-top: 50px; font-weight: bold; font-size: 20px; ">Total: ' . $total . ' lei</p>';
        echo '<p style="text-align: left"> Plata va fi efectuată la primirea coletului.';
        echo '</div>';
    }


    if (isset($_SESSION['user_info']['id_client'])): ?>
    <?php else: ?>
        <div class="auth-message">
            <p>Pentru a putea plasa o comandă, te rugăm să te autentifici sau să te înregistrezi.</p>
            <a href="login.php">Autentificare</a> <a href="register.php">Înregistrare</a>
        </div>
    <?php endif;


    ?>

    <h2>Introdu datele pentru finalizarea comenzii:</h2>

    <form action="procesare_comanda.php" method="POST">
        <?php ?>

        <form action="procesare_comanda.php" method="POST">
            <label for="nume">Nume și prenume:</label>
            <input type="text" id="nume" name="nume" required
                value="<?php echo $_SESSION['user_info']['nume'] ?? ''; ?>">

            <label for="email">Adresă de email:</label>
            <input type="email" id="email" name="email" required
                value="<?php echo $_SESSION['user_info']['email'] ?? ''; ?>">

            <label for="telefon">Număr de telefon:</label>
            <input type="tel" id="telefon" name="telefon" pattern="[0-9]{10}" required
                value="<?php echo $_SESSION['user_info']['nr_telefon'] ?? ''; ?>">

            <label for="location">Adresă livrare:</label>
            <textarea id="location" name="location" rows="4" cols="50"
                required><?php echo $_SESSION['user_info']['adresa'] ?? ''; ?></textarea><br><br>

            <form action="procesare_comanda.php" method="POST">
                <input type="submit" value="Finalizează comanda"
                    class="<?php echo isset($_SESSION['user_info']['id_client']) ? '' : 'disabled-button'; ?>">
            </form>
        </form>




        <?php


        if (isset($_SESSION['user_info']['id_client'])) {
            $client_id = $_SESSION['user_info']['id_client'];

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "parfumuri";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

        }
        ?>
        <br><br>

    </form>

    <?php
    $errors = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_SESSION['user_info']['id_client'])) {
            $client_id = $_SESSION['user_info']['id_client'];
        } else {
            $errors[] = "ID client lipsă. Te rugăm să te autentifici pentru a plasa comanda.";
        }
    }
    ?>