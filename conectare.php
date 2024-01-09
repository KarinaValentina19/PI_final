<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parfumuri";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM produse";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id_produse"] . " - Name: " . $row["nume"] . " - Description: " . $row["descriere"] . "<br>";
    }
} else {
    echo "0 results";
}

$conn->close();
?>