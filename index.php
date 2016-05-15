<!-- import JS -->
<script src="http://libs.baidu.com/jquery/2.0.0/jquery.js"></script>
<script>window.jQuery||document.write("<script src=\"front/lib/jquery-1.11.3.min.js\"><\/script>");</script>
<script src="front/lib/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
<!-- MyJS -->
<script src="front/lib/script.js"></script>
<script>
  // delAllCookie();
	var arr=getByrGetByUrl();
	setAllCookie(arr,parseInt(arr['expires_in'])*1000);
	window.setTimeout(function() {
		location.href = "front/index.html";
	}, 2000);
	

	

	console.log(document.cookie);
</script>