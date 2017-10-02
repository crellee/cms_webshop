<?php
$sajUsers = file_get_contents('../../JSON/users.txt');
$ajUsers = json_decode($sajUsers);

//User ID
$sUserId = $_GET['id'];

for($i = 0; $i < count($ajUsers); $i++) {
    if($ajUsers[$i] -> id == $sUserId) {
        echo json_encode($ajUsers[$i]);
        exit; //OR BREAK?
    }
}

?>