<?php
session_start();
if( isset($_SESSION['bLoggedIn']) && isset($_SESSION['jMyUser'])  )
{
    $bLoggedIn  = "true";
    $jMyUser = json_encode($_SESSION['jMyUser']);
}
else
{
    $bLoggedIn = "false";
    $jMyUser = json_encode(new stdClass());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="Style/bootstrap.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        #map {
            height: 400px;
            width: 400px;
        }
    </style>
</head>
<body>

<button class='btnPages' data-page='getPageOne'>Page 1</button>
<button class='btnPages' data-page='getPageTwo'>Page 2</button>
<button class="btnPages" data-page='getGoogleMaps'>Maps</button>
<button class="btnPages" data-page='getPageProducts'>Products</button>
<button class="btnPages" data-page='getPageUsers'>Users</button>
<button class="btnPages" data-page='getPageLogin'>Login</button>
<button class="btnPages" data-page='getPageLogout'>Logout</button>




<div id="child"></div>

<script>

    var jMyUser = <?php echo $jMyUser ?>;
    var bIsLoggedIn = <?php echo $bLoggedIn?>;

    console.log(jMyUser);
    console.log(bIsLoggedIn);

    document.addEventListener("click",function(e){
        if(e.target.classList.contains("btnPages")) {
            var spText = e.target.getAttribute("data-page");
            window[spText](function(data){
                child.innerHTML = data;
            });
        }
        });



    function getPageLogout(callback) {
        doAjax({"method":"GET","url":"api/login/logout.php"},function(){
            getPageLogin(function(sLoginDiv){
                callback(sLoginDiv);
            });
        });
    }

    function getPageLogin(callback) {
        var sLoginDiv = '<div>\
                  <div id="userInputDiv">\
                    <form id="frmLogin"> \
                        <input class="form-control" type="text" name="txtUserEmail" placeholder="User Name">\
                        <input class="form-control" type="text" name="txtUserPassword" placeholder="User Password">\
                        <button class="btn btn-success btnPages" type="button" data-page="doLogin">Login</button>\
                    </form>\
                  </div>\
                </div>';
        callback(sLoginDiv);
    }

    function doLogin(){
        var jAjaxData = {};
        jAjaxData.method = "POST";
        jAjaxData.url = "api/login/login.php";
        jAjaxData.form = "frmLogin";
        doAjax(jAjaxData,function(user){
            if(user){
                jMyUser = JSON.parse(user);
                bIsLoggedIn = true;
            }
        });
    }

    function getPageUsers(callback) {
        doAjax({"method":"GET","url":"api/user/get-users.php?id="+jMyUser.id+""},function(users){
            var ajUsers = JSON.parse(users);
            var sUsersDiv = "";
            for(var i = 0; i < ajUsers.length; i++) {
                var jUser = ajUsers[i];
                sUsersDiv += generateUserDiv(jUser);
            }
            callback(sUsersDiv);
        });
    }

    function generateUserDiv(user) {
        return '<div>\
                  <div id="userImageDiv">\
                    <img width="250px" height="250px" src="Pictures/'+user.picture+'">\
                  </div>\
                  <div id="userInfoDiv">\
                    <div><span>First name: </span><span>'+user.firstName+'</span></div>\
                    <div><span>Last name: </span><span>'+user.lastName+'</span></div>\
                    <div><span>E-mail: </span><span>'+user.email+'</span></div>\
                  </div>\
                  <div id="userOptions" data-id="'+user.id+'">\
                    <button>Edit</button>\
                    <button>Delete</button>\
                  </div>\
                </div>';
    }

    function getPageProducts(callback) {
        doAjax({"method":"GET","url":"api/product/get-products.php"}, function (products) {
            var jProducts = JSON.parse(products);
            var sProductsDiv = "";
            for(var i = 0; i < jProducts.length; i++) {
                var jProduct = jProducts[i];
                sProductsDiv += generateProductDiv(jProduct)
            }
            callback(sProductsDiv)
        });
    }

    function generateProductDiv(product) {
        return '<div>\
                  <div id="productImageDiv">\
                    <img width="250px" height="250px" src="Pictures/'+product.picture+'">\
                  </div>\
                  <div id="productInfoDiv">\
                    <div><span>Name: </span><span>'+product.productName+'</span></div>\
                    <div><span>Price: </span><span>'+product.productPrice+'</span></div>\
                    <div><span>Quantity: </span><span>'+product.quantity+'</span></div>\
                  </div>\
                  <div id="productOptions" data-id="'+product.id+'">\
                    <button>Edit</button>\
                    <button>Delete</button>\
                  </div>\
                </div>';
    }

    function getPageOne(callback) {
        var sDiv = "This is page one";
        callback(sDiv);

    }
    function getPageTwo(callback) {
        var sDiv = "This is page two";
        callback(sDiv);
    }

    //Kan måske optimeres lidt..
    function doAjax(jData, callback){
        var ajax = new XMLHttpRequest();
        ajax.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var sDataFromServer = this.responseText;
                callback(sDataFromServer);
            }
        }
        ajax.open( jData.method, jData.url, true );
        if(jData.form){
            var oFrmUser = new FormData(document.getElementById(jData.form));
            ajax.send(oFrmUser);
        }
        else {
            ajax.send();
        }
    }



    var jMarkerPos = {};
    var markers = [];
    var mainMap;

    function getGoogleMaps(callback){
        doAjax({"method":"GET","url":"api/subscribe/get-subscribers.php"},function(subscribers){
            navigator.geolocation.getCurrentPosition(function(position){
                initMap(position, function(){
                    //Her når vi til når geolocation og kortet er loadet.
                    var ajSubscribers = JSON.parse(subscribers);
                    for(var i = 0; i < ajSubscribers.length; i++){

                        var jSubscriberPosition =
                            {
                                lat: Number(ajSubscribers[i].latitude),
                                lng: Number(ajSubscribers[i].longtitude)
                            };
                        var subscriberMarker = new google.maps.Marker({
                            map: mainMap,
                            position: jSubscriberPosition
                        });
                        markers.push(subscriberMarker);
                    }
                });
            });

        });
        callback("<h1>Kortet med alle subscribers</h1><div id='map'></div> ");
    }

    function initMap(position, callback) {

        var currentLng = position.coords.longitude;
        var currentLat = position.coords.latitude;

        var uluru = {lat: currentLat, lng: currentLng};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: uluru
        });
        mainMap = map;
        var marker = new google.maps.Marker({
            map: map
        });
        markers.push(marker);
        map.addListener('click', function (e) {
            jMarkerPos.lng = e.latLng.lng();
            jMarkerPos.lat = e.latLng.lat();
            console.log(jMarkerPos);
            marker.setPosition(jMarkerPos);
        });
        callback();
    }



</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCixu9-sUnzMFlELBgUvdcf4IVpV3NF8fA">
</script>

</body>
</html>