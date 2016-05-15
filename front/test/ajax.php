<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script type="text/javascript">
    function $(id){
        return document.getElementById(id);
    }

    function createXhr() {
        var xhr;
        if(window.ActiveXObject){
            xhr=new ActiveXObject("Microsoft.XMLHTTP");;
        }else if(window.XMLHttpRequest){
            xhr=new XMLHttpRequest();
        }
        return xhr;
    }   
    var xhr;

    function test1() {
        xhr=createXhr();
        uri=encodeURI("http://www.h5bupt.cn/dcb/GoCatchIT/index.php");
        if(xhr){
            var url="http://bbs.byr.cn/oauth2/authorize?response_type=token&client_id=f7c47ada566998f3af7465517afa91d8&redirect_uri="+uri+"&state="+Math.random();

            xhr.onreadystatechange=function(){
                if (xhr.readyState==4)
                {   
                    // alert(url);
                    // alert(xhr.responseText);
                }
                alert(xhr.readyState);
            };
            xhr.open('get',url,true);
            // xhr.setRequestHeader('Access-Control-Allow-Origin','*');
            xhr.send();
        }else{
            alert('Error: createXhr()');
        }
    }
    

    </script>
</head>
<body>
    <input type="button" value="test" onclick="test1()"/>
    <input type="text" value='test'/>
</body>
</html>