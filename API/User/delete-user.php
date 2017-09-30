<?php

$sajUsers = file_get_contents('../../JSON/users.txt');
$ajUsers = json_decode($sajUsers);

$sUserId = $_GET['id'];

if($sUserId) {
    for ($i = 0; $i < count($ajUsers); $i++) {
        if($sUserId == $ajUsers[$i]->id) {
            array_splice($ajUsers, $i, 1);
            $bjUserRemoved = true;
        }
    }
}
if($bjUserRemoved) {
    $sajUsers = json_encode($ajUsers);
    file_put_contents( '../../JSON/users.txt' , $sajUsers );
    echo 'User removed with user id: '.$sUserId;
}

?>