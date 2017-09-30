<?php

$sajProducts = file_get_contents('../../JSON/products.txt');
$ajProducts = json_decode($sajProducts);

$sProductId = $_POST['txtProductId'];
$sProductName = $_POST['txtProductName'];
$sProductPrice = $_POST['txtProductPrice'];
$sProductQuantity = $_POST['txtProductQuantity'];

for($i = 0; $i < count($ajProducts); $i++) {
    if($ajProducts[$i]->id == $sProductId) {

        $ajProducts[$i]->productName = $sProductName;
        $ajProducts[$i]->productPrice = $sProductPrice;
        $ajProducts[$i]->quantity = $sProductQuantity;
        echo json_encode($ajProducts[$i]);
        break;
    }
}
$sajProducts = json_encode($ajProducts);
file_put_contents('../../JSON/products.txt', $sajProducts);



?>