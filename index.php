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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="Style/style.css">
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
    <nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
        <button class="navbar-toggler navbar-toggler-right hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">Webshop</a>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link btnPages" data-page='getPageOne'>Page one</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btnPages" data-page='getPageTwo'>Page Two</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btnPages" data-page='getGoogleMaps'>Maps</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btnPages" data-page='getPageProducts'>Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btnPages" data-page='getPageUsers'>Users</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link btnPages" data-page='getPageLogin'>Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btnPages" data-page='getPageLogout'>Logout</a>
                </li>
            </ul>
        </div>
    </nav>
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
        if(e.target.classList.contains("btnCrudPages")) {
            var spText = e.target.getAttribute("data-page");
            var sId = e.target.parentNode.getAttribute("data-id");
            window[spText](sId, function(data){
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
        var sLoginDivx = '<div>\
                  <div id="userInputDiv">\
                    <form id="frmLogin"> \
                        <input class="form-control" type="text" name="txtUserEmail" placeholder="User Name">\
                        <input class="form-control" type="text" name="txtUserPassword" placeholder="User Password">\
                        <button class="btn btn-success btnPages" type="button" data-page="doLogin">Login</button>\
                    </form>\
                    <button class="btn btn-primary btnPages">Sign Up</button>\
                  </div>\
                </div>';
        var sLoginDiv =  '<div class="row">\
            <div class="col-md-6 mx-auto">\
            <form class="form" id="frmLogin">\
            <h1>Login</h1>\
            <div class="form-group">\
            <label for="txtUserEmail">Email</label>\
            <input type="text" class="form-control" name="txtUserEmail"  placeholder="Enter email">\
            </div>\
            <div class="form-group">\
            <label for="txtUserPassword">Password</label>\
            <input type="password" class="form-control" name="txtUserPassword" placeholder="Enter password">\
            </div>\
            <div class="form-group" style="text-align: center">\
            <button class="btn btn-success btnPages" type="button" data-page="doLogin">Login</button>\
            </div>\
            </form>\
            <div style="text-align: center; margin-top: 30px;">\
            <label for="SignUp">Not a user yet?</label>\
            <br>\
            <button class="btn btn-primary btnPages" data-page="getPageSignUp">Sign Up</button>\
            </div>\
            </div>\
            </div>';
        callback(sLoginDiv);
    }

    function doLogin(callback){
        var jAjaxData = {};
        jAjaxData.method = "POST";
        jAjaxData.url = "api/login/login.php";
        jAjaxData.form = "frmLogin";
        doAjax(jAjaxData,function(user){
            if(user){
                jMyUser = JSON.parse(user);
                bIsLoggedIn = true;
                getPageUsers(function(data){
                    callback(data);
                });
            }
        });
    }

    function getPageSignUp(callback){
        var sSignUpDiv = '<div class="row">\
        <div class="col-md-6 mx-auto">\
            <form class="form" id="frmSignUp">\
                <h1>Create new user</h1>\
                <div class="form-group">\
                    <label for="txtUserFirstName">First Name</label>\
                    <input type="text" class="form-control" name="txtUserFirstName"  placeholder="Enter first name">\
                </div>\
                <div class="form-group">\
                    <label for="txtUserLastName">Last Name</label>\
                    <input type="text" class="form-control" name="txtUserLastName"  placeholder="Enter last name">\
                </div>\
                <div class="form-group">\
                    <label for="txtUserEmail">Email</label>\
                    <input type="text" class="form-control" name="txtUserEmail"  placeholder="Enter email">\
                </div>\
                <div class="form-group">\
                    <label for="txtUserPassword">Password</label>\
                    <input type="password" class="form-control" name="txtUserPassword"  placeholder="Enter password">\
                </div>\
                <div class="form-group">\
                    <label for="fileUserPicture">Profile Picture</label>\
                    <input type="file" class="form-control" name="fileUserPicture"  placeholder="Enter password">\
                </div>\
                <div class="form-group" style="text-align: center">\
                    <button class="btn btn-primary btnPages" type="button" data-page="doSignUp">Sign Up</button>\
                </div>\
            </form>\
        </div>\
        </div>';
        callback(sSignUpDiv);
    }

    function doSignUp(callback){
        var jAjaxData = {};
        jAjaxData.method = "POST";
        jAjaxData.url = "api/user/create-user.php";
        jAjaxData.form = "frmSignUp";
        doAjax(jAjaxData,function(user){
            if(user){
                jMyUser = JSON.parse(user);
                bIsLoggedIn = true;
                getPageUsers(function(data){
                    callback(data);
                });
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
        var productsBody = "<div class='album text-muted'>\
                    <div class='container'>\
                        <div class='row' id='productsContent'>\
                        </div>\
                    </div>\
                 </div>";
        callback(productsBody)
        getProductElements(function (products) {
            productsContent.innerHTML = products;
        })
    }

    function getProductElements(callback) {
        doAjax({"method":"GET","url":"api/product/get-products.php"}, function (products) {
            var ajProducts = JSON.parse(products);
            var sProductDivs = "";
            for(var i = 0; i < ajProducts.length; i++) {
                var jProduct = ajProducts[i];
                sProductDivs += generateProductDiv(jProduct);
            }
            callback(sProductDivs)
        });
    }

    function generateProductDiv(product) {
        return '<div class="card">\
                  <img width="250px" height="250px" src="'+product.picture+'">\
                  <div id="productInfoDiv">\
                    <div><span>Name: </span><span>'+product.productName+'</span></div>\
                    <div><span>Price: </span><span>'+product.productPrice+'</span></div>\
                    <div><span>Quantity: </span><span>'+product.quantity+'</span></div>\
                  </div>\
                  <div id="productOptions" data-id="'+product.id+'">\
                    <button class="btnCrudPages">Add to basket</button>\
                    <button class="btnCrudPages" data-page="getProductPage">Show more</button>\
                  </div>\
                </div>';
    }

    function getProductPage(sId, callback) {
        doAjax({"method":"GET","url":"api/product/get-product.php/?id=" + sId}, function (product) {
            var jProduct = JSON.parse(product);
            var productDiv =
                '<div class="container">\
                    <div class="row">\
                        <div class="col-md-5">\
                           <img width="100%" src="'+jProduct.picture+'"/>\
                        </div>\
                        <div class="col-md-6">\
                          <div class="row">\
                            <div class="col-md-12">\
                                <h3 id="product-editable-info" style="border-bottom: 1px solid #f2f2f2">'+jProduct.productName+'</h3>\
                            </div>\
                          </div>\
                          <div class="row">\
                            <div class="col-md-4">\
                                <p>Price:</p>\
                            </div>\
                            <div class="col-md-6">\
                                <p id="product-editable-info">'+jProduct.productPrice+'</p>\
                            </div>\
                          </div>\
                          <div class="row">\
                            <div class="col-md-4">\
                                <p>Available:</p>\
                            </div>\
                            <div class="col-md-6">\
                                <p >'+jProduct.quantity+'</p>\
                            </div>\
                          </div>\
                          <div class="row product-options">\
                               <div class="col-md-12">\
                                    <div class="row" style="margin-top: 10px">\
                                         <button class="col-md-6 btn btn-success">Add to basket</button>\
                                    </div>\
                                    <div class="row" style="margin-top: 10px">\
                                        <button class="col-md-3 btn btn-secondary">Edit</button>\
                                        <button class="col-md-3 btn btn-danger">Delete</button>\
                                    </div>\
                               </div>\
                          </div>\
                        </div>\
                    </div>\
                </div>';
            callback(productDiv)
        });
    }

    function getPageOne(callback) {
        console.log('hey page one');
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
        else if(jData.formData) {
            ajax.send(jData.formData);
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

        var sSubscribtionDiv = '<form id="frmSubscribe">\
                        <button class="btn btn-success btnPages" type="button" data-page="doSubscribtion">Subscribe!</button>\
                    </form>';

        callback("<h1>Choose your location on the map and click subscribe!</h1><div id='map'></div>" + sSubscribtionDiv);
    }

    function doSubscribtion(callback){

        var formData = new FormData(frmSubscribe);
        formData.append('txtUserId',jMyUser.id);
        formData.append('txtLatitude', jMarkerPos.lat);
        formData.append('txtLongtitude', jMarkerPos.lng);


        doAjax({"method":"POST","url":"api/subscribe/create-subscribtion.php", "formData":formData},function(data){
            console.log(data);
        });
    }

    function initMap(position, callback) {

        var currentLng = position.coords.longitude;
        var currentLat = position.coords.latitude;

        var uluru = {lat: currentLat, lng: currentLng};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
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