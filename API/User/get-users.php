<?php

$sajUsers = file_get_contents('../../JSON/users.txt');
$ajUsers = json_decode($sajUsers);

$sUserId = $_GET['id'];

for($i = 0; $i < count($ajUsers); $i++) {

    if($ajUsers[$i]->id == $sUserId){
        if($ajUsers[$i]->admin) {
            echo $sajUsers;
        }
        else {
            for($j = 0; $j < count($ajUsers); $j++) {
                unset($ajUsers[$j]->id);
                unset($ajUsers[$j]->password);
            }
            echo json_encode($ajUsers);
        }
        break;
    }
}

?>