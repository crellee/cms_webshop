<?php

//get all products
$sajProducts = file_get_contents('../../JSON/products.txt');
$ajProducts = json_decode($sajProducts);

//set all product variables
$sProductId = uniqid();
$sProductName = $_POST['txtProductName'];
$sProductPrice = $_POST['txtProductPrice'];
$sProductQuantity = $_POST['txtProductQuantity'];
$sFileName = $_FILES['fileProductPicture']['name'];

//save uploaded pic
$sFolder = '../../Pictures/';
$sSaveFileTo = $sFolder.$sProductId.'_'.$sFileName;
move_uploaded_file($_FILES['fileProductPicture']['tmp_name'], $sSaveFileTo);

//save informations to json
$newProduct = new stdClass();
$newProduct->id = $sProductId;
$newProduct->productName = $sProductName;
$newProduct->productPrice = $sProductPrice;
$newProduct->quantity = $sProductQuantity;
$newProduct->picture  = $sProductId.'_'.$sFileName;

array_push($ajProducts, $newProduct);
$sajProducts = json_encode($ajProducts);
file_put_contents('../../JSON/products.txt', $sajProducts);

echo json_encode($newProduct);


?>