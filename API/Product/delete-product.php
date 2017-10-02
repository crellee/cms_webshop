<?php

$sajProducts = file_get_contents('../../JSON/products.txt');
$ajProducts = json_decode($sajProducts);

$sProductId = $_GET['id'];

if($sProductId) {
    for ($i = 0; $i < count($ajProducts); $i++) {
        if($sProductId == $ajProducts[$i]->id) {
            unlink('../../'.$ajProducts[$i]->picture);
            array_splice($ajProducts, $i, 1);
            $bjProductRemoved = true;
        }
    }
}
if($bjProductRemoved) {
    $sajProducts = json_encode($ajProducts);
    file_put_contents( '../../JSON/products.txt' , $sajProducts );
    echo 'Product removed with product id: '.$sProductId;
}

?>