<?php
session_start();
//get all users
$sajUsers = file_get_contents('../../JSON/users.txt');
$ajUsers = json_decode($sajUsers);

$sUserId = $_POST['id'];
$sUserFirstName = $_POST['txtUserFirstName'];
$sUserLastName = $_POST['txtUserLastName'];
$sUserEmail = $_POST['txtUserEmail'];

$jSessionUser = ($_SESSION['jMyUser']);

$responseObject = json_decode('{}');

for($i = 0; $i < count($ajUsers); $i++) {
    if($ajUsers[$i]->id == $sUserId) {

        $ajUsers[$i]->firstName = $sUserFirstName;
        $ajUsers[$i]->lastName = $sUserLastName;
        $ajUsers[$i]->email = $sUserEmail;

        if($jSessionUser -> id == $ajUsers[$i] -> id) {
            $_SESSION['jMyUser'] = $ajUsers[$i];
            $responseObject -> updateSession = true;
            $responseObject -> jUser = $ajUsers[$i];
        } else {
            $responseObject -> updateSession = false;
            $responseObject -> jUser = $ajUsers[$i];
        }
        echo json_encode($responseObject);
    }
}
$sajUsers = json_encode($ajUsers);
file_put_contents('../../JSON/users.txt', $sajUsers);


?>