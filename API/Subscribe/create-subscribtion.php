<?php

//get all subscribers
$sajSubscribers = file_get_contents('../../JSON/subscribers.txt');
$ajSubscribers = json_decode($sajSubscribers);

$sUserId = $_POST['txtUserId'];

echo $sUserId;

/*
//Checks if the subscriber all ready exists in the database
for($i = 0; $i < count($ajSubscribers); $i++ ){
    if($ajSubscribers[$i]->userId === $_POST['txtUserId']){
        $bExistingUser = true;
        echo 'User is allready subscribed';
        break;
    }
}

if(!$bExistingUser) {

//set all subscriber variables
    $sSubscriberId = uniqid();
    $sUserId = $_POST['txtUserId'];
    $sLatitude = $_POST['txtLatitude'];
    $sLongtitude = $_POST['txtLongtitude'];


//save informations to json
    $jNewSubscriber = new stdClass();
    $jNewSubscriber->id = $sSubscriberId;
    $jNewSubscriber->userId = $sUserId;
    $jNewSubscriber->latitude = $sLatitude;
    $jNewSubscriber->longtitude = $sLongtitude;

    array_push($ajSubscribers, $jNewSubscriber);
    $sajSubscribers = json_encode($ajSubscribers);
    file_put_contents('../../JSON/subscribers.txt', $sajSubscribers);

    echo json_encode($jNewSubscriber);
}
*/
?>