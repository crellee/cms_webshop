<?php
session_start();
//get all users
$sajUsers = file_get_contents('../../JSON/users.txt');
$ajUsers = json_decode($sajUsers);

$sUserId = $_POST['id'];
$sUserFirstName = $_POST['txtUserFirstName'];
$sUserLastName = $_POST['txtUserLastName'];
//$sUserPassword = $_POST['txtUserPassword'];

for($i = 0; $i < count($ajUsers); $i++) {
    if($ajUsers[$i]->id == $sUserId) {

        $ajUsers[$i]->firstName = $sUserFirstName;
        $ajUsers[$i]->lastName = $sUserLastName;
        echo json_encode($ajUsers[$i]);
        break;
    }
}
$sajUsers = json_encode($ajUsers);
file_put_contents('../../JSON/users.txt', $sajUsers);



?>