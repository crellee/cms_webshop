<?php
session_start();
$bLoggedIn = false;
$jMyUser = null;
if( isset($_SESSION['jMyUser']) )
{
    $bLoggedIn = true;
    $jMyUser = $_SESSION['jMyUser'];
}
/*
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
*/
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
            width: 800px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-toggleable-md navbar-inverse fixed-top bg-inverse">
        <button class="navbar-toggler navbar-toggler-right hidden-lg-up" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand btnPages" href="" data-page="getHomePage">Webshop</a>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto" id="navMain">
<!--                <li class="nav-item">
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
                -->
            </ul>
            <ul class="navbar-nav ml-auto" id="navLogin">
                <!--           <li class="nav-item">
                              <a class="nav-link btnPages" data-page='getPageLogin'>Login</a>
                          </li>
                         <li class="nav-item">
                              <a class="nav-link btnPages" data-page='getPageLogout'>Logout</a>
                          </li> -->
            </ul>
        </div>
    </nav>
<div id="parentDiv">

</div>

<script>

    var jMyUser = <?php echo json_encode($jMyUser) ?>;
    var bIsLoggedIn = <?php echo json_encode($bLoggedIn)?>;

    var ajLocalProducts = null;

    console.log(jMyUser);
    console.log(bIsLoggedIn);

    (function(){
        getHomePage(function (homePageDiv) {
            parentDiv.innerHTML = homePageDiv;
        });
        if(bIsLoggedIn){
            getLoggedInNavbar();
        }
        else {
            getLoggedOutNavbar();
        }
    })();

    document.addEventListener("click",function(e){
        if(e.target.classList.contains("btnPages")) {
            var spText = e.target.getAttribute("data-page");
            if(e.target.classList.contains('nav-link')){
                removeActivePage(function(){
                    e.target.className += ' active';
                });
            }
            window[spText](function(data){
                parentDiv.innerHTML = data;
            });
        }
        if(e.target.classList.contains("btnCrudPages")) {
            var spText = e.target.getAttribute("data-page");
            var sId = e.target.parentNode.getAttribute("data-id");
            var currentLocation = e.target.getAttribute("data-location");
            window[spText](sId, function(data){
                parentDiv.innerHTML = data;
            });
        }
        if(e.target.classList.contains("btnEdit")){
            var spText = e.target.getAttribute("data-method");
            var editableInfo = document.querySelectorAll(".editable-info");
            window[spText](e.target, editableInfo);
        }
        if(e.target.classList.contains("btnUpdate")){
            var spText = e.target.getAttribute("data-method");
            var editTextFields = document.querySelectorAll(".edit-textfield");
            window[spText](e.target, editTextFields, function(sDiv){
                parentDiv.innerHTML = sDiv;
            });
        }

        if(e.target.classList.contains("btnDelete")){
            var spText = e.target.getAttribute("data-method"); //delete method
            var sApi = e.target.getAttribute("data-api"); //delete-product
            var sId = e.target.getAttribute('data-id');
            window[spText](sId, sApi, function(data){
                parentDiv.innerHTML = data;
            });
        }

    });

    function removeActivePage(callback){
        var navElements = document.getElementsByClassName('nav-link');
        for(var i = 0; i < navElements.length; i++) {
            navElements[i].classList.remove('active');
        }
        callback();
    }

    function getHomePage(callback) {
        var homePageContent = "";
        jMyUser ? homePageContent =
                '<div class="jumbotron">\
                    <h1>Hi '+jMyUser.firstName+'</h3>\
                </div>'
                : homePageContent =
                '<div class="jumbotron">\
                   <h1>Hi, welcome to this webshop!</h3>\
                </div>';
        callback(homePageContent);
    }

    function getPageLogout(callback) {
        doAjax({"method":"GET","url":"api/login/logout.php"},function(){
            getPageLogin(function(sLoginDiv){
                removeActivePage(function(){
                    jMyUser = null;
                    bIsLoggedIn = false;
                    getLoggedOutNavbar();
                    callback(sLoginDiv);
                });
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
                getHomePage(function(homePageDiv){
                    getLoggedInNavbar();
                    callback(homePageDiv);
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
                    getLoggedInNavbar();
                    callback(data);
                });
            }
        });
    }


    function getPageUsers(callback) {
        var sUsersBody =
            "<div class='container'>\
                <div class='row' id='usersContent'>\
                </div>\
            </div>";
        callback(sUsersBody);
        getUserElements(function(users){
            usersContent.innerHTML = users;
        });
    }
    function getUserElements(callback){
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
                    <img width="250px" height="250px" src="'+user.picture+'">\
                  </div>\
                  <div id="userInfoDiv">\
                    <div><span>First name: </span><span>'+user.firstName+'</span></div>\
                    <div><span>Last name: </span><span>'+user.lastName+'</span></div>\
                    <div><span>E-mail: </span><span>'+user.email+'</span></div>\
                  </div>\
                  <div id="userOptions" data-id="'+user.id+'">\
                    <button class="btnCrudPages" data-page="getUserPage">Show more</button>\
                  </div>\
                </div>';
    }
    
    function getPageProducts(callback) {
        var productsBody =
                    "<div class='container'>\
                        <div class='row' id='adminOptions' style='margin-bottom: 10px; margin-top: 10px'>\
                        </div>\
                        <div class='row'>\
                        <input id='txtSearchBar' class='form-control col-md-6' name='search' placeholder='Search Here' autocomplete='off' autofocus='autofocus' type='text'>\
                        </div>\
                        <div class='row' id='productsContent'>\
                        </div>\
                        <div class=row'>\
                        </div>";

        callback(productsBody);

        txtSearchBar.addEventListener('input',function(){
            var sSearchBarValue = txtSearchBar.value.toLowerCase();
            var sFitleredProductsDiv = "";

            for(var i = 0; i < ajLocalProducts.length; i++) {
                var jProduct = ajLocalProducts[i];
                var sProductName = jProduct.productName.toLowerCase();
                if(sProductName.indexOf(sSearchBarValue) != -1){
                    sFitleredProductsDiv += generateProductDiv(jProduct);
                }
            }
            productsContent.innerHTML = sFitleredProductsDiv;
        });

        if(jMyUser.admin) {
            getAddProductsButton(function (btnAddProductsDiv) {
                adminOptions.innerHTML = btnAddProductsDiv;
            });
        }
        getProductElements(function (products) {
            productsContent.innerHTML = products;
        });
    }

    function getAddProductsButton(callback) {
        var addProductsButtonDiv = "<div class='col-md-12'>\
                <button class='btn btn-success btnPages' data-page='getPageAddProduct'>Add product</button>\
               </div>";
        callback(addProductsButtonDiv)
    }

    function getPageAddProduct(callback) {
       var sDivAddProduct = '<div class="row">\
       <div class="col-md-6 mx-auto">\
           <form class="form" id="frmAddProduct">\
               <h1>Create new product</h1>\
               <div class="form-group">\
                   <label for="txtProductName">Product name</label>\
                   <input type="text" class="form-control" name="txtProductName"  placeholder="Enter product name">\
               </div>\
               <div class="form-group">\
                   <label for="txtProductPrice">Product price</label>\
                   <input type="text" class="form-control" name="txtProductPrice"  placeholder="Enter product price">\
               </div>\
               <div class="form-group">\
                   <label for="txtProductQuantity">Quantity</label>\
                   <input type="number" class="form-control" name="txtProductQuantity"  placeholder="Enter quantity">\
               </div>\
               <div class="form-group">\
                   <label for="fileProductPicture">Profile Picture</label>\
                   <input type="file" class="form-control" name="fileProductPicture">\
               </div>\
               <div class="form-group" style="text-align: center">\
                   <button class="btn btn-primary btnPages" type="button" data-page="saveProduct">Save product</button>\
               </div>\
           </form>\
       </div>\
       </div>';
       callback(sDivAddProduct);
    }

    function saveProduct(callback){
        var jAjaxData = {
            "method" : "POST",
            "url" : "api/product/create-product.php",
            "form" : "frmAddProduct"

        }
        doAjax(jAjaxData, function(product){
            var jProduct = JSON.parse(product);
            var sId = jProduct.id;
            getProductPage(sId, function (data) {
                callback(data);
            })
        });
    }


    function getProductElements(callback) {
        doAjax({"method":"GET","url":"api/product/get-products.php"}, function (products) {
            var ajProducts = JSON.parse(products);
            ajLocalProducts = ajProducts;
            var sProductDivs = "";
            for(var i = 0; i < ajProducts.length; i++) {
                var jProduct = ajProducts[i];
                sProductDivs += generateProductDiv(jProduct);
            }
            callback(sProductDivs)
        });
    }

    function generateProductDiv(jProduct) {
        return '<div class="card">\
                  <img width="250px" height="250px" src="'+jProduct.picture+'">\
                  <div id="productInfoDiv">\
                    <div><span>Name: </span><span>'+jProduct.productName+'</span></div>\
                    <div><span>Price: </span><span>'+jProduct.productPrice+'</span></div>\
                    <div><span>Quantity: </span><span class="productQuantity" data-id="'+jProduct.id+'">'+jProduct.quantity+'</span></div>\
                  </div>\
                  <div id="productOptions" data-id="'+jProduct.id+'">\
                    <button class="btn btn-sm btn-success btnCrudPages" data-location="productsPage" data-page="buyProduct">Add to basket</button>\
                    <button class="btn btn-sm btn-primary btnCrudPages" data-page="getProductPage">Show more</button>\
                  </div>\
                </div>';
    }

    function buyProduct(sId, callback ) {
        console.log(sId);
        var aQuantityElements = document.querySelectorAll(".productQuantity");
        //[{"id":"59cf83eb0c645","productName":"new ashss22","productPrice":"101\u20ac","quantity":"73","picture":"Pictures\/nikeshoe.jpg"}]

        var formData = new FormData();
        formData.append('txtProductId', sId);
        var jAjaxData = {
            "method" : "POST",
            "url" : "api/product/update-quantity.php",
            "formData" : formData
        }
        doAjax(jAjaxData, function(data){
            if(data) {
                var jBoughtItem = JSON.parse(data);
                displayNotification(jBoughtItem.picture, "You have bought: "+jBoughtItem.productName, "Congratulations");
                playSound('Sounds/purchase_success.mp3');
                decrementQuantityinHTML(jBoughtItem.id, aQuantityElements)
            }
            else {
                playSound('Sounds/purchase_error.mp3');
                displayNotification('','Product is not avaible','ERROR');
            }
        });
    }

    function decrementQuantityinHTML(productId, aQuantityElements) {
        for(var i = 0; i < aQuantityElements.length; i++) {
            var sCurrentQuantityElement = aQuantityElements[i];
            var sCurrentQuantityElementDataId = sCurrentQuantityElement.getAttribute('data-id');

            if (productId === sCurrentQuantityElementDataId) {
                var iQuantityValue = parseInt(sCurrentQuantityElement.innerHTML);
                if (iQuantityValue > 0) {
                    iQuantityValue--;
                    sCurrentQuantityElement.innerHTML = iQuantityValue;
                }
            }
        }
    }

    function getProductPage(sId, callback) {
        doAjax({"method":"GET","url":"api/product/get-product.php/?id=" + sId}, function (product) {
            var jProduct = JSON.parse(product);
            var productPageMainInfo = getProductPageMainInfo(jProduct);
            callback(productPageMainInfo);
            productOptions.innerHTML = getProductPageOptions(jProduct, jMyUser);
        });
    }

    function getProductPageMainInfo(jProduct) {
        return '<div class="container">\
                    <div class="row">\
                        <div class="col-md-5">\
                           <img width="100%" src="'+jProduct.picture+'"/>\
                        </div>\
                        <div class="col-md-6">\
                          <div class="row">\
                            <div class="col-md-12">\
                                <h3 class="editable-info" id="txtProductName" style="border-bottom: 1px solid #f2f2f2">'+jProduct.productName+'</h3>\
                            </div>\
                          </div>\
                          <div class="row">\
                            <div class="col-md-4">\
                                <p>Price:</p>\
                            </div>\
                            <div class="col-md-6">\
                                <p class="editable-info" id="txtProductPrice">'+jProduct.productPrice+'</p>\
                            </div>\
                          </div>\
                          <div class="row">\
                            <div class="col-md-4">\
                                <p>Available:</p>\
                            </div>\
                            <div class="col-md-6">\
                                <p class="editable-info productQuantity" data-id="'+jProduct.id+'"  id="txtProductQuantity">'+jProduct.quantity+'</p>\
                            </div>\
                          </div>\
                          <div class="row product-options" id="productOptions">\
                          </div>\
                        </div>\
                    </div>\
                </div>';
    }

    function getProductPageOptions(jProduct, jUser) {
        var productOptions = "";
        jUser.admin ?
            productOptions =
            '<div class="col-md-12">\
                 <div class="row" style="margin-top: 10px">\
                  <button class="col-md-3 btn btn-secondary btnEdit" data-api="edit-product" data-id="'+jProduct.id+'" data-method="changeToEditMode">Edit</button>\
                  <button class="col-md-3 btn btn-danger btnDelete" data-api="delete-product" data-method="deleteObject" data-id="'+jProduct.id+'">Delete</button>\
                </div>\
             </div>' :
            productOptions =
                '<div class="col-md-12">\
                      <div class="row" style="margin-top: 10px" data-id="'+jProduct.id+'">\
                          <button class="col-md-6 btn btn-success btnCrudPages" data-location="productPage" data-page="buyProduct">Add to basket</button>\
                       </div>\
                 </div>';

        return productOptions;
    }

    function deleteObject(sId, sApi, callback){
        var sApiDirectory = sApi.split('-')[1];
        var sObjectUpper = sApiDirectory.charAt(0).toUpperCase() + sApiDirectory.slice(1);
        var sFunctionToCall = "getPage" + sObjectUpper + "s";
        var url = "api/"+sApiDirectory+"/"+sApi+".php/?id="+sId;

        var jAjaxData = {
            "method" : "GET",
            "url" : url
        }
        doAjax(jAjaxData,function(){
            if(sId == jMyUser.id) {
                getPageLogout(function(sLogoutdata){
                    callback(sLogoutdata)
                });
            }
            else {
                window[sFunctionToCall](function(data){
                    callback(data);
                });
            }
            });


    }

    function getPageMyUser(callback){
        callback(generateUserProfileContent(jMyUser));
        userOptions.innerHTML = getProfilePageOptions(jMyUser);

    }

    function getUserPage(sId, callback) {

        if(jMyUser.id == sId) {
            callback(generateUserProfileContent(jMyUser));
            userOptions.innerHTML = getProfilePageOptions(jMyUser);
        }
        else {
            doAjax({"method":"GET","url":"api/user/get-user.php/?id=" + sId}, function (user) {
                var jUser = JSON.parse(user);
                callback(generateUserProfileContent(jUser));
                if(jMyUser.admin) {
                    userOptions.innerHTML = getProfilePageOptions(jUser);
                    console.log(document.getElementsByName('admin'));
                }
            });
        }
    }

    function generateUserProfileContent(jUser) {
        var sUserDiv =
            '<div class="container">\
                <div class="row">\
                    <div class="col-md-5">\
                       <img width="100%" src="'+jUser.picture+'"/>\
                        </div>\
                        <div class="col-md-6">\
                          <div class="row">\
                            <div class="col-md-12">\
                                <h3 class="editable-info" id="txtUserFirstName" style="border-bottom: 1px solid #f2f2f2">'+jUser.firstName+'</h3>\
                            </div>\
                          </div>\
                          <div class="row">\
                            <div class="col-md-4">\
                                <p>Last name:</p>\
                            </div>\
                            <div class="col-md-6">\
                                <p class="editable-info" id="txtUserLastName">'+jUser.lastName+'</p>\
                            </div>\
                          </div>\
                          <div class="row">\
                            <div class="col-md-4">\
                                <p>Email:</p>\
                            </div>\
                            <div class="col-md-6">\
                                <p class="editable-info" id="txtUserEmail">'+jUser.email+'</p>\
                            </div>\
                          </div>\
                          <div class="row user-options" id="userOptions">\
                          </div>\
                        </div>\
                    </div>\
                </div>';
        return sUserDiv;
    }

    function getProfilePageOptions(jUser) {
        return  '<div class="col-md-12">\
                    <div class="row" style="margin-top: 10px">\
                        <button class="col-md-3 btn btn-secondary btnEdit" data-api="edit-user" data-id="'+jUser.id+'" data-method="changeToEditMode">Edit</button>\
                        <button class="col-md-3 btn btn-danger btnDelete" data-api="delete-user" data-method="deleteObject" data-id="'+jUser.id+'">Delete</button>\
                    </div>\
                </div>'
    }


    function changeToEditMode(btn, editableInfo) {
        for(var i = 0; i < editableInfo.length; i++) {
            var editableElement = editableInfo[i];
            var editableElementValue = editableElement.innerHTML;
            var editableElementParent = editableElement.parentNode;
            var editableElementId = editableElement.getAttribute('id');
            var inputField = '<input type="text" id="'+editableElementId+'" class="edit-textfield" value="'+editableElementValue+'">';
            editableElement.remove();
            editableElementParent.innerHTML = inputField;
        }
        var dataId = btn.getAttribute('data-id');
        var sApi = btn.getAttribute('data-api');
        var updateBtn = '<button type="button" class="btn btn-sm btn-primary btnUpdate" data-api="'+sApi+'" data-id="'+dataId+'" data-method="updateObject">Save changes</button>';
        var editButtonParentNode = btn.parentNode;
        btn.remove();
        editButtonParentNode.insertAdjacentHTML("afterbegin", updateBtn);
    }

    function updateObject(btn, editTextFields, callback) {
        var formData = new FormData();
        var btnDataId = btn.getAttribute('data-id');
        var sApi = btn.getAttribute('data-api');
        var sApiDirectory = sApi.split('-')[1];
        var sObjectUpper = sApiDirectory.charAt(0).toUpperCase() + sApiDirectory.slice(1);
        var sFunctionToCall = "get"+sObjectUpper+"Page";
        var url = "api/" + sApiDirectory + "/" + sApi  + ".php";

        for(var i = 0; i < editTextFields.length; i++) {
            var currentField = editTextFields[i];
            var key = currentField.getAttribute('id');
            var value = currentField.value;
            formData.append(key, value);
        }
        formData.append('id', btnDataId);

        var jAjaxData = {
            "method" : "POST",
            "url" : url,
            "formData" : formData
        }

        doAjax(jAjaxData, function (data) {
            var jData = JSON.parse(data);

            if(jData.updateSession) {
                jMyUser = jData.jUser;
            }
            window[sFunctionToCall](btnDataId, function(data){
                callback(data);
            });
        });
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
        if(jMyUser.admin) {
        doAjax({"method":"GET","url":"api/subscribe/get-subscribers.php"},function(subscribers){
            var jAjaxData = {
                "method":"GET",
                "url":"api/user/get-users.php?id="+jMyUser.id
            };
            doAjax(jAjaxData, function(sajUsers){
            var ajUsers = JSON.parse(sajUsers);
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
                        var sSubscriberId = ajSubscribers[i].userId;
                        for(var j = 0; j < ajUsers.length; j++) {
                            if(sSubscriberId == ajUsers[j].id) {
                                createNewMarker(jSubscriberPosition, ajUsers[j]);
                                break;
                            }
                        }


                    }
                });
            });

        });
        });
        }
        else {
            navigator.geolocation.getCurrentPosition(function(position) {
                initMap(position, function () {

                });
            });
        }
        var sSubscribtionDiv = '<form id="frmSubscribe">\
                        <button class="btn btn-success btnPages" type="button" data-page="doSubscribtion">Subscribe!</button>\
                    </form>';

        callback("<h1>Choose your location on the map and click subscribe!</h1><div id='map'></div>" + sSubscribtionDiv);
    }

    function createNewMarker(position, jUser) {

        var sInfoWindowContent = '<div><div>First Name: '+jUser.firstName+'</div>\
            <div>Last Name: '+jUser.lastName+' </div>\
            <div>E-mail: '+jUser.email+' </div>\
            <img width="70px" height="70px" src="'+jUser.picture+'">\
            </div>';

        var subscriberMarker = new google.maps.Marker({
            map: mainMap,
            position: position
        });
        subscriberMarker['infowindow'] = new google.maps.InfoWindow({
            content : sInfoWindowContent
        });

        google.maps.event.addListener(subscriberMarker, 'click', function(){
            this['infowindow'].open(mainMap, this);
        });
        markers.push(subscriberMarker);
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

    function displayNotification(sUrl, sItemId, sTitle) {
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }
        else {
            var notification = new Notification(sTitle, {
                icon: sUrl,
                body: sItemId
            });
        }
    }

    function playSound(soundPath) {
        var oSound = new Audio(soundPath);
        oSound.play();
    }

    function getLoggedInNavbar(){
        navMain.innerHTML = "";
        navLogin.innerHTML = "";

        var sNavbarLogout =
            '<li class="nav-item">\ \
                <a class="nav-link btnPages" data-page="getPageMyUser">My User</a>\            \
             </li>\
             <li class="nav-item">\
                <a class="nav-link btnPages" data-page="getPageLogout">Logout</a>\
            </li>';
        var sNavbarMain =
            '<li class="nav-item">\
                <a class="nav-link btnPages" data-page="getGoogleMaps">Maps</a>\
                </li>\
                <li class="nav-item">\
                <a class="nav-link btnPages" data-page="getPageProducts">Products</a>\
                </li>\
                <li class="nav-item">\
                <a class="nav-link btnPages" data-page="getPageUsers">Users</a>\
            </li>';

        navLogin.insertAdjacentHTML('beforeend',sNavbarLogout);
        navMain.insertAdjacentHTML('afterbegin', sNavbarMain);
    }
    function getLoggedOutNavbar(){
        navMain.innerHTML = "";
        navLogin.innerHTML = "";

        sNavbar = '<li class="nav-item">\
            <a class="nav-link btnPages" data-page="getPageLogin">Login</a>\
            </li>';
        navLogin.insertAdjacentHTML("beforeend",sNavbar);
    }

</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCixu9-sUnzMFlELBgUvdcf4IVpV3NF8fA">
</script>

</body>
</html>