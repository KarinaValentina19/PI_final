<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Coș de cumpărături</title>
    <link rel="stylesheet" type="text/css" href="style_cos.css">
    <link rel="stylesheet" type="text/css" href="style_femei.css">
    <link rel="stylesheet" type="text/css" href="style.css">


</head>

<header>

    <a href="cos.php"><img src="imagini/cos1.png" alt="Cos" width="52" align="right"></a>
    <a href="login.php"><img src="imagini/login1.png" alt="Login" width="52" align="right"></a>
    <a href="register.php"><img src="imagini/register1.png" alt="Register" width="50" align="right"></a>
    <h1>Coșul meu de cumpărături</h1>

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
</header>

<ul class="cart-items">
    <?php
    session_start();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_GET['idProdus']) && isset($_GET['numeProdus']) && isset($_GET['pretProdus']) && isset($_GET['imagineProdus'])) {
        $idProdus = $_GET['idProdus'];
        $numeProdus = $_GET['numeProdus'];
        $pretProdus = $_GET['pretProdus'];
        $imagineProdus = isset($_GET['imagineProdus']) ? $_GET['imagineProdus'] : '';

        $produsExistent = false;
        foreach ($_SESSION['cart'] as &$produs) {
            if ($produs['idProdus'] == $idProdus) {
                $produs['cantitate'] = $produs['cantitate'] + 1;
                $produsExistent = true;
                break;
            }
        }
        unset($produs);
        if (!$produsExistent) {
            $nouprodus = array(
                'idProdus' => $idProdus,
                'numeProdus' => $numeProdus,
                'pretProdus' => $pretProdus,
                'imagineProdus' => $imagineProdus,
                'cantitate' => 1
            );
            array_push($_SESSION['cart'], $nouprodus);
        }
    }

    $total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $produs) {
            $total += $produs['pretProdus'] * $produs['cantitate'];
        }
    }
    unset($produs);

    if (isset($_POST['stergeProdus'])) {
        $index = $_POST['index'];
        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }

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

    $isAvailable = true;

    foreach ($_SESSION['cart'] as $index => $produs) {
        $idProdus = $produs['idProdus'];

        $sql = "SELECT stoc FROM produse WHERE id_produse = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idProdus);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stocBazaDeDate = $row['stoc'];

            if ($produs['cantitate'] > $stocBazaDeDate) {
                $isAvailable = false;
                break;
            }
        }
        $stmt->close();
    }
    ?>
    </div>

    <body class="container1">
        <main>
            <div class="cart-items">
                <?php foreach ($_SESSION['cart'] as $index => $produs) { ?>
                    <div class="cart-item">
                        <img src="<?php echo $produs['imagineProdus']; ?>" alt="<?php echo $produs['numeProdus']; ?>">
                        <div class="item-details">
                            <h3>
                                <?php echo $produs['numeProdus']; ?>
                            </h3>
                            <p>Preț:
                                <?php echo $produs['pretProdus']; ?> lei
                            </p>
                            <form method="POST">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <label for="quantity">Cantitate:</label>
                                <input type="number" id="quantity_<?php echo $index; ?>" name="quantity"
                                    value="<?php echo $produs['cantitate']; ?>"
                                    onchange="updateQuantity(this.value, <?php echo $index; ?>)">
                                <button type="submit" name="stergeProdus">Șterge</button>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="total">
                <p>Total: <span id="total">
                        <?php echo $total; ?>
                    </span> lei</p>
            </div>
            <?php
            if ($isAvailable) {
                echo '
    <div class="checkout-button">
        <form action="finalizare_comanda.php" method="POST">
            <input type="submit" value="Finalizează comanda" id="finalizare_comanda" formaction="finalizare_comanda.php" class="checkout-button">
        </form>
    </div>';
            } else {
                echo '
    <div class="checkout-button">
        <input type="submit" value="Finalizează comanda" id="finalizare_comanda" disabled class="checkout-button disabled-button">
        <p>Nu sunt suficiente produse disponibile în stoc pentru a finaliza comanda.</p>
        <p>Sunt ' . $stocBazaDeDate . ' produse pe stoc din parfumul ' . $produs['numeProdus'] . '.' . '</p>
    </div>';
            }

            ?>

        </main>

        <script>
            function updateQuantity(newQuantity, index) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        document.getElementById("total").innerText = this.responseText;
                    }
                };
                xhttp.open("POST", "actualizare_cantitate.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("newQuantity=" + newQuantity + "&index=" + index);
            }
        </script>
    </body>
</ul>
</body>

</html>