<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagină de Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="style_cos.css">
</head>

<body>
    <a href="cos.php"><img src="imagini/cos.png" alt="Cos" width="52" align="right"></a>
    <a href="login.php"><img src="imagini/login.png" alt="Login" width="52" align="right"></a>
    <a href="register.php"><img src="imagini/register.png" alt="Register" width="50" align="right"></a>
    <div class="navbar">
        <a href="main.html">Despre noi</a>
        <div class="dropdown">
            <button class="dropbtn">Parfumuri pentru femei </button>
            <div class="dropdown-content">
                <a href="apa_de_parfum_femei.html">Apă de parfum</a>
                <a href="apa_de_toaleta_femei.html">Apă de toaletă</a>
                <a href="apa_de_colonie_femei.html">Apă de colonie</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropbtn">Parfumuri pentru bărbați</button>
            <div class="dropdown-content">
                <a href="apa_de_parfum_barbati.html">Apă de parfum</a>
                <a href="apa_de_toaleta_barbati.html">Apă de toaletă</a>
                <a href="apa_de_colonie_barbati.html">Apă de colonie</a>
            </div>
        </div>
        <a href="parfumuri_pentru_casa.html">Parfumuri pentru casă</a>
    </div>


    <?php
    session_start();

    if (!isset($_SESSION['loggedin'])) {
        header("Location: login.php");
        exit();
    }

    $user_info = $_SESSION['user_info'];
    $id_utilizator = $user_info['id_client'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "parfumuri";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id_comanda, data_comanda, SUM(total) AS total FROM comenzi WHERE id_client = ? GROUP BY id_comanda, data_comanda");
    $stmt->bind_param("i", $id_utilizator);
    $stmt->execute();
    $result = $stmt->get_result();
    $istoric_comenzi = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
    ?>




    <style>
        .content {
            text-align: center;
        }

        form {
            display: grid;
            gap: 10px;
            justify-content: center;
        }

        label {
            display: inline-block;
            width: 150px;
            text-align: left;
        }

        table {
            margin: 0 auto;
        }

        table tr td {
            padding: 15px;
        }

        input[type="text"],
        input[type="email"],
        input[type="submit"] {
            padding: 8px;
            width: calc(100% - 10px);
            margin-bottom: 10px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #F0E68C;
            color: black;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>






    <!DOCTYPE html>
    <html lang="ro">

    <head>
    </head>

    <body>

        <div class="content">
            <br>
            <h2 align="center">Istoricul comenzilor</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID Comandă</th>
                            <th>Data Comandă</th>
                            <th>Suma Produselor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($istoric_comenzi as $comanda): ?>
                            <tr>
                                <td>
                                    <?php echo $comanda["id_comanda"]; ?>
                                </td>
                                <td>
                                    <?php echo $comanda["data_comanda"]; ?>
                                </td>
                                <td>
                                    <?php echo $comanda["total"]; ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body><br><br><br>

</body>

</html>





<?php

$user_info = $_SESSION['user_info'];
$id_utilizator = $user_info['id_client'];


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parfumuri";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT nume, email, adresa, nr_telefon FROM clienti WHERE id_client = ?");
$stmt->bind_param("i", $id_utilizator);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nume_nou = !empty($_POST['nume']) ? $_POST['nume'] : null;
    $email_nou = !empty($_POST['email']) ? $_POST['email'] : null;
    $adresa_noua = !empty($_POST['adresa']) ? $_POST['adresa'] : null;
    $telefon_nou = !empty($_POST['nr_telefon']) ? $_POST['nr_telefon'] : null;

    if ($nume_nou !== null && $email_nou !== null && $adresa_noua !== null && $telefon_nou !== null) {
        $stmt = $conn->prepare("UPDATE clienti SET nume=?, email=?, adresa=?, nr_telefon=? WHERE id_client=?");
        $stmt->bind_param("ssssi", $nume_nou, $email_nou, $adresa_noua, $telefon_nou, $id_utilizator);
        $stmt->execute();
    } else {
    }
}


?>

<!DOCTYPE html>
<html lang="ro">

<head>
</head>

<body>
    <div class="content">
        <h2>Modificare date personale</h2>
        <form action="" method="post">
            <table>
                <tr>
                    <td><label for="nume">Nume:</label></td>
                    <td><input type="text" id="nume" name="nume" value="<?php echo $user_data['nume']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="email">Email:</label></td>
                    <td><input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="adresa">Adresă:</label></td>
                    <td><input type="text" id="adresa" name="adresa" value="<?php echo $user_data['adresa']; ?>"></td>
                </tr>
                <tr>
                    <td><label for="nr_telefon">Număr telefon:</label></td>
                    <td><input type="text" id="nr_telefon" name="nr_telefon"
                            value="<?php echo $user_data['nr_telefon']; ?>"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Actualizare date"></td>
                </tr>
            </table>
        </form>
    </div>

    <form method="post">
        <input type="submit" name="logout" value="Delogare" class="logout-button">
    </form>


    <?php if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit();

    } ?>
</body>


</html>