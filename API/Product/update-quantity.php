<?php

$sajProducts = file_get_contents('../../JSON/products.txt');
$ajProducts = json_decode($sajProducts);

$sProductId = $_POST['txtProductId'];

for($i = 0; $i < count($ajProducts); $i++) {
    if($ajProducts[$i]->id == $sProductId) {
        if($ajProducts[$i]->quantity != 0) {
            $ajProducts[$i]->quantity = $ajProducts[$i]->quantity - 1;
            echo json_encode($ajProducts[$i]);
        }
        else {

        }

        break;
    }
}

$sajProducts = json_encode($ajProducts);
file_put_contents('../../JSON/products.txt', $sajProducts);

?>