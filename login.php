<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagină de Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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

    <div class="info1" align="center">
        <form action="login.php" method="post">
            <table cellpadding="8">
                <tr>
                    <td>
                        <h2 align="center">Login</h2>
                        <label for="email">Adresă de email:</label><br>
                        <input type="email" id="email" name="email"
                            value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required><br><br>

                        <label for="password">Parolă:</label><br>
                        <input type="password" id="password" name="password" required><br><br>

                        <input type="submit" value="Login" class="register-button"><br><br>
                    </td>
                </tr>
            </table>
        </form>
        <img src="imagini/giordani.jpg" width="195">
    </div>


    <?php
    session_start();

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        header("Location: pagina_li.php");
        exit();
    }

    $errors = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $parola = $_POST['password'];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "parfumuri";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id_client, nume, email, adresa, nr_telefon, parola FROM clienti WHERE email = ?");
        $stmt->bind_param("s", $email);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($parola, $user['parola'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_info'] = $user;
                $_SESSION['welcome_message'] = "Bine ai venit, " . $user['nume'] . "!";
                header("Location: pagina_li.php");
                exit();
            } else {
                $errors[] = "Parolă incorectă!";
            }
        } else {
            $errors[] = "Utilizatorul nu există!";
        }


        $stmt->close();
        $conn->close();
    }


    if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
    ?>


    </div>
</body>

</html>