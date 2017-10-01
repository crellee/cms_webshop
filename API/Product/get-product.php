<?php
$sajProducts = file_get_contents('../../JSON/products.txt');
$ajDataProducts = json_decode($sajProducts);

//Product ID
$sProductId = $_GET['id'];

for($i = 0; $i < count($ajDataProducts); $i++) {
    if($ajDataProducts[$i] -> id == $sProductId) {
        echo json_encode($ajDataProducts[$i]);
        exit;
    }
}

?>