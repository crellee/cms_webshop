<?php
session_start();

//get all users
$sajUsers = file_get_contents('../../JSON/users.txt');
$ajUsers = json_decode($sajUsers);

$sUserEmail = $_POST['txtUserEmail'];
$sUserPassword = $_POST['txtUserPassword'];

//Checks if user exists
for($i = 0; $i < count($ajUsers); $i++) {
    if($ajUsers[$i]->email == $sUserEmail &&
        $ajUsers[$i]->password == $sUserPassword) {

        $_SESSION['bLoggedIn'] = true;
        $_SESSION['jMyUser'] = $ajUsers[$i];
        echo json_encode($ajUsers[$i]);
        break;

    }
}

?>