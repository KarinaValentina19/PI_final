<?php
session_start();

if (isset($_POST['newQuantity']) && isset($_POST['index'])) {
    $newQuantity = $_POST['newQuantity'];
    $index = $_POST['index'];

    $newQuantity = max(1, intval($newQuantity));

    if (isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['cantitate'] = $newQuantity;

        $total = 0;
        foreach ($_SESSION['cart'] as $produs) {
            $total += $produs['pretProdus'] * $produs['cantitate'];
        }

        echo $total;
    }
}
?>