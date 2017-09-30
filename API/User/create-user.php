<?php
session_start();

//get all users
$sajUsers = file_get_contents('../../JSON/users.txt');
$ajUsers = json_decode($sajUsers);

//Checks if the email all ready exists in the database
for($i = 0; $i < count($ajUsers); $i++ ){
    if($ajUsers[$i]->email === $_POST['txtUserEmail']){
        $bExistingUser = true;
        echo 'Email exists in database';
        break;
    }
}

if(!$bExistingUser) {

//set all user variables
    $sUserId = uniqid();
    $sUserFirstName = $_POST['txtUserFirstName'];
    $sUserLastName = $_POST['txtUserLastName'];
    $sUserEmail = $_POST['txtUserEmail'];
    $sUserPassword = $_POST['txtUserPassword'];
    $sFileName = $_FILES['fileUserPicture']['name'];


//save uploaded pic
    $sFolder = '../../Pictures/';
    $sSaveFileTo = $sFolder.$sUserId.'_'.$sFileName;
    move_uploaded_file($_FILES['fileUserPicture']['tmp_name'], $sSaveFileTo);



//save informations to json
    $newUser = new stdClass();
    $newUser->id = $sUserId;
    $newUser->firstName = $sUserFirstName;
    $newUser->lastName = $sUserLastName;
    $newUser->email = $sUserEmail;
    $newUser->password = $sUserPassword;
    $newUser->picture  =$sUserId.'_'.$sFileName;

    array_push($ajUsers, $newUser);
    $sajUsers = json_encode($ajUsers);
    file_put_contents('../../JSON/users.txt', $sajUsers);

    //sets the user in the session and a logged in variable as true
    $_SESSION['bLoggedIn'] = true;
    $_SESSION['jMyUser'] = $newUser;

    echo json_encode($newUser);
}

?>