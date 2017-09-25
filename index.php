<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<button class='btnPages' data-page='getPageOne'>Page 1</button>
<button class='btnPages' data-page='getPageTwo'>Page 2</button>

<div id="child">

</div>

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
        ajax.open( oData.method, oData.url, true );
        if(oData.form){
            var oFrmUser = new FormData(oData.form);
            ajax.send(oFrmUser);
        }
        else {
            ajax.send();
        }

    }

</script>

</body>
</html>