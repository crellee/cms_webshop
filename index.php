<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        #map {
            height: 400px;
            width: 50%;
        }
    </style>
</head>
<body>

<button class='btnPages' data-page='getPageOne'>Page 1</button>
<button class='btnPages' data-page='getPageTwo'>Page 2</button>
<button class="btnPages" data-page='getGoogleMaps'>Maps</button>

<div id="child"></div>
<div id="map"></div>

<script>

    document.addEventListener("click",function(e){
        if(e.target.classList.contains("btnPages")) {
            var spText = e.target.getAttribute("data-page");
            window[spText](function(data){
                child.innerHTML = data;
            });
        }
        });


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
            var oFrmUser = new FormData(jData.form);
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
        callback("<h1>Kortet med alle subscribers</h1>");
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