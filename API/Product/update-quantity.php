<?php

$sajProducts = file_get_contents('../../JSON/products.txt');
$ajProducts = json_decode($sajProducts);

$sProductId = $_POST['txtProductId'];
$sAmountSold = $_POST['txtAmountSold'];

for($i = 0; $i < count($ajProducts); $i++) {
    if($ajProducts[$i]->id == $sProductId) {
        $ajProducts[$i]->quantity = $ajProducts[$i]->quantity - $sAmountSold;
        echo json_encode($ajProducts[$i]);
        break;
    }
}

$sajProducts = json_encode($ajProducts);
file_put_contents('../../JSON/products.txt', $sajProducts);

?>